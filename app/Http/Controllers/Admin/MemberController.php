<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting logic
        $sort = $request->get('sort', 'latest');
        $direction = $request->get('direction', 'desc');
        switch ($sort) {
            case 'name':
                $query->orderBy('first_name', $direction)->orderBy('last_name', $direction);
                break;
            case 'contact':
                $query->orderBy('email', $direction)->orderBy('phone', $direction);
                break;
            case 'status':
                $query->orderBy('status', $direction);
                break;
            case 'join_date':
                $query->orderBy('join_date', $direction);
                break;
            case 'member_id':
                $query->orderBy('member_id', $direction);
                break;
            default:
                $query->latest();
        }

        $members = $query->paginate(10)->appends($request->all());

        // Prepare graph data using the same filters as the table
        $groupBy = $request->get('group_by', 'day');
        if ($groupBy === 'month') {
            $memberJoinData = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->selectRaw("COUNT(*) as count, DATE_FORMAT(join_date, '%Y-%m') as date")
                ->groupBy(DB::raw("DATE_FORMAT(join_date, '%Y-%m')"))
                ->orderBy('date', 'asc')
                ->get();
            $activeJoinData = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->where('status', 'active')
                ->selectRaw("COUNT(*) as count, DATE_FORMAT(join_date, '%Y-%m') as date")
                ->groupBy(DB::raw("DATE_FORMAT(join_date, '%Y-%m')"))
                ->orderBy('date', 'asc')
                ->get();
            $year = $request->get('year', now()->year);
            $labels = [];
            $counts = [];
            $activeCounts = [];
            for ($m = 1; $m <= 12; $m++) {
                $label = sprintf('%04d-%02d', $year, $m);
                $labels[] = date('M Y', strtotime($label.'-01'));
                $found = $memberJoinData->firstWhere('date', $label);
                $counts[] = $found ? $found->count : 0;
                $activeFound = $activeJoinData->firstWhere('date', $label);
                $activeCounts[] = $activeFound ? $activeFound->count : 0;
            }
            $joinLabels = collect($labels);
            $joinCounts = collect($counts);
            $activeJoinCounts = collect($activeCounts);
        } elseif ($groupBy === 'year') {
            $memberJoinData = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->selectRaw("COUNT(*) as count, YEAR(join_date) as date")
                ->groupBy(DB::raw("YEAR(join_date)"))
                ->orderBy('date', 'asc')
                ->get();
            $activeJoinData = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->where('status', 'active')
                ->selectRaw("COUNT(*) as count, YEAR(join_date) as date")
                ->groupBy(DB::raw("YEAR(join_date)"))
                ->orderBy('date', 'asc')
                ->get();
            $years = range($memberJoinData->min('date') ?? now()->year, $memberJoinData->max('date') ?? now()->year);
            $labels = [];
            $counts = [];
            $activeCounts = [];
            foreach ($years as $y) {
                $labels[] = (string)$y;
                $found = $memberJoinData->firstWhere('date', $y);
                $counts[] = $found ? $found->count : 0;
                $activeFound = $activeJoinData->firstWhere('date', $y);
                $activeCounts[] = $activeFound ? $activeFound->count : 0;
            }
            $joinLabels = collect($labels);
            $joinCounts = collect($counts);
            $activeJoinCounts = collect($activeCounts);
        } else {
            $year = $request->get('year', now()->year);
            $start = strtotime("$year-01-01");
            $end = strtotime("$year-12-31");
            $labels = [];
            $counts = [];
            $activeCounts = [];
            $dateMap = $memberJoinData = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->selectRaw('COUNT(*) as count, DATE(join_date) as date')
                ->groupBy(DB::raw('DATE(join_date)'))
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');
            $activeDateMap = DB::table('members')
                ->when($request->filled('status'), function($q) use ($request) {
                    return $q->where('status', $request->status);
                })
                ->where('status', 'active')
                ->selectRaw('COUNT(*) as count, DATE(join_date) as date')
                ->groupBy(DB::raw('DATE(join_date)'))
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');
            for ($d = $start; $d <= $end; $d = strtotime('+1 day', $d)) {
                $label = date('Y-m-d', $d);
                $labels[] = date('M d, Y', $d);
                $counts[] = isset($dateMap[$label]) ? $dateMap[$label]->count : 0;
                $activeCounts[] = isset($activeDateMap[$label]) ? $activeDateMap[$label]->count : 0;
            }
            $joinLabels = collect($labels);
            $joinCounts = collect($counts);
            $activeJoinCounts = collect($activeCounts);
        }

        return view('AdminSide.members.index', compact('members', 'joinLabels', 'joinCounts', 'activeJoinCounts'));
    }

    public function create()
    {
        return view('AdminSide.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Use database transaction to prevent race conditions
        DB::transaction(function () use ($validated) {
            // Generate member_id
            $today = date('Ymd');
            $prefix = 'M' . $today;
            
            // Find the highest member_id for today with row locking
            $lastMember = Member::where('member_id', 'LIKE', $prefix . '%')
                ->orderBy('member_id', 'desc')
                ->lockForUpdate()
                ->first();
            
            if ($lastMember) {
                // Extract the sequence number and increment
                $lastSequence = (int) substr($lastMember->member_id, -4);
                $nextSequence = $lastSequence + 1;
            } else {
                // First member for today
                $nextSequence = 1;
            }
            
            $validated['member_id'] = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
            
            // Hash the password before saving
            $validated['password'] = bcrypt($validated['password']);

            // Remove password_confirmation field as it's not needed in the database
            unset($validated['password_confirmation']);

            Member::create($validated);
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        return view('AdminSide.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('AdminSide.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Update password only if provided
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Remove password_confirmation field as it's not needed in the database
        unset($validated['password_confirmation']);

        // Get the original data before update
        $originalData = $member->getOriginal();
        $updatedFields = [];
        
        // Check which fields were updated
        foreach ($validated as $key => $value) {
            if ($key !== 'password' && $key !== 'password_confirmation' && $originalData[$key] != $value) {
                $updatedFields[$key] = $value;
            }
        }
        
        // Update the member
        $member->update($validated);
        
        // If any fields were updated, send notification
        if (!empty($updatedFields) || $request->filled('password')) {
            // Add password to updated fields if it was changed
            if ($request->filled('password')) {
                $updatedFields['password'] = '********'; // Don't show actual password
            }
            
            // Send notification to the member
            $member->sendAccountUpdatedNotification(
                (Auth::guard('web')->user()->name ?? 'Administrator'),
                $updatedFields
            );
        }

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }

    public function activate(Member $member)
    {
        $member->update(['status' => 'active']);
        
        // Send welcome notification to member
        NotificationService::welcomeMember($member);
        
        return response()->json([
            'success' => true,
            'message' => 'Member activated successfully.'
        ]);
    }

    public function deactivate(Member $member)
    {
        $member->update(['status' => 'inactive']);
        
        return response()->json([
            'success' => true,
            'message' => 'Member deactivated successfully.'
        ]);
    }
} 