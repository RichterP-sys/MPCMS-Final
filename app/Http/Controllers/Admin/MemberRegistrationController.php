<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberRegistrationController extends Controller
{
    /**
     * Display the member registration page
     */
    public function index()
    {
        // Get members who have a User account (registered and confirmed)
        $registeredMembers = Member::whereHas('memberAccount', function($query) {
            $query->where('confirmed', true); // Only confirmed accounts
        })->with('memberAccount')->get();
        
        // Get User accounts that are pending confirmation (created but not yet confirmed)
        $pendingRegistrations = User::where('confirmed', false)
            ->whereNotNull('member_id')
            ->with('member')
            ->get();
        
        // Get members who don't have a User account (unregistered)
        $unregisteredMembers = Member::whereDoesntHave('memberAccount')->get();
        
        // Get total members count
        $totalMembers = Member::count();

        return view('AdminSide.member-registration.index', compact(
            'registeredMembers',
            'pendingRegistrations',
            'unregisteredMembers',
            'totalMembers'
        ));
    }

    /**
     * Store a new member account registration
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:4|max:20|alpha_num|unique:users,name',
            'email' => 'required|email|unique:users|unique:members',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'This username is already taken. Please choose another.',
            'username.alpha_num' => 'Username can only contain letters and numbers.',
            'email.unique' => 'This email is already registered. Please use a different email.',
        ]);
        
        // Generate a unique member_id
        $memberIdPrefix = 'MEM';
        $memberIdNum = Member::count() + 1;
        $memberId = $memberIdPrefix . str_pad($memberIdNum, 6, '0', STR_PAD_LEFT);
        
        // Create member record in members table
        $member = Member::create([
            'member_id' => $memberId,
            'first_name' => '',
            'last_name' => '',
            'email' => $validated['email'],
            'phone' => '',
            'address' => '',
            'password' => Hash::make($validated['password']),
            'status' => 'inactive',
            'join_date' => now(),
        ]);
        
        // Create user account linked to the member
        $user = User::create([
            'name' => $validated['username'], // Store username in name field
            'email' => $validated['email'], // Use provided email
            'password' => Hash::make($validated['password']),
            'member_id' => $member->id, // Link to newly created member
            'confirmed' => false, // Pending admin confirmation
        ]);

        return redirect()->route('admin.member-registration.index')
            ->with('success', "Account and member record created successfully! Member can now login and complete their profile.");
    }

    /**
     * Confirm a pending member registration
     */
    public function confirm(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->confirmed = true;
        $user->save();
        // Optionally, update member status
        if ($user->member) {
            $user->member->status = 'active';
            $user->member->save();
        }
        return redirect()->route('admin.member-registration.index')
            ->with('success', 'Member registration confirmed successfully!');
    }
}
