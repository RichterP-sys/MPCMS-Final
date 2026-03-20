<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Member;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function index()
    {
        $contributions = Contribution::with('member')->latest()->paginate(10);
        return view('AdminSide.contributions.index', compact('contributions'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->get();
        return view('AdminSide.contributions.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'contribution_type' => 'required|in:regular,special,emergency,mortuary',
            'contribution_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $contribution = Contribution::create($validated);
        
        // Notify member that contribution was recorded
        NotificationService::contributionRecorded($contribution);

        return redirect()->route('admin.finance.index', ['tab' => 'contributions'])
            ->with('success', 'Contribution created successfully.');
    }

    public function show(Contribution $contribution)
    {
        return view('AdminSide.contributions.show', compact('contribution'));
    }

    public function edit(Contribution $contribution)
    {
        $members = \App\Models\Member::all();
        return view('AdminSide.contributions.edit', compact('contribution', 'members'));
    }

    public function update(Request $request, Contribution $contribution)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'contribution_type' => 'required|in:regular,special,emergency,mortuary',
            'contribution_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $contribution->update($validated);

        return redirect()->route('admin.finance.index', ['tab' => 'contributions'])
            ->with('success', 'Contribution updated successfully.');
    }

    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

        return redirect()->route('admin.finance.index', ['tab' => 'contributions'])
            ->with('success', 'Contribution deleted successfully.');
    }

    public function approve(Contribution $contribution)
    {
        $contribution->update(['status' => 'approved']);
        
        // Notify member that contribution was approved
        NotificationService::contributionApproved($contribution);
        
        return redirect()->route('admin.finance.index', ['tab' => 'contributions'])
            ->with('success', 'Contribution approved successfully.');
    }

    public function reject(Contribution $contribution)
    {
        $contribution->update(['status' => 'rejected']);
        
        return redirect()->route('admin.finance.index', ['tab' => 'contributions'])
            ->with('success', 'Contribution rejected successfully.');
    }
}
