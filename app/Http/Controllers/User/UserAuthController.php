<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\User;
use App\Services\ActivityLogService;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // First try to find and login as User (admin-created accounts)
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login the user using member guard (for consistency with dashboard routes)
            Auth::guard('member')->login($user->member);
            $request->session()->regenerate();
            
            ActivityLogService::log(
                'login',
                'User logged in successfully',
                $request
            );
            
            // Check if profile is completed
            $member = $user->member;
            
            // If profile not completed, redirect to profile completion
            if (!$member->profile_completed) {
                return redirect()->route('user.profile.complete');
            }
            
            // If profile completed but account not confirmed, redirect to pending confirmation
            if ($member->profile_completed && !$user->confirmed) {
                return redirect()->route('user.profile.pending');
            }
            
            return redirect()->intended(route('user.dashboard'));
        }
        
        // Fallback to Member model authentication
        if (Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            ActivityLogService::log(
                'login',
                'Member logged in successfully',
                $request
            );
            
            // Check member profile status
            $member = Auth::guard('member')->user();
            
            // If profile not completed, redirect to profile completion
            if (!$member->profile_completed) {
                return redirect()->route('user.profile.complete');
            }
            
            // If profile completed but status is pending, redirect to pending confirmation
            if ($member->profile_completed && $member->status === 'pending') {
                return redirect()->route('user.profile.pending');
            }
            
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function showProfileComplete()
    {
        // Only show this if user is logged in and not confirmed
        $member = Auth::guard('member')->user();
        
        if (!$member) {
            return redirect()->route('user.login');
        }
        
        return view('UserSide.auth.profile-complete');
    }

    public function completeProfile(Request $request)
    {
        $member = Auth::guard('member')->user();
        
        if (!$member) {
            return redirect()->route('user.login');
        }
        
        // Validate the profile data (form uses phone_number, we map to phone)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'nature_of_work' => 'required|string|max:255',
            'employer_business_name' => 'required|string|max:255',
            'date_of_employment' => 'required|date',
            'tin_number' => 'required|string|max:50',
            'sss_gsis_no' => 'required|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone_number'],
            'address' => $validated['address'],
            'nature_of_work' => $validated['nature_of_work'],
            'employer_business_name' => $validated['employer_business_name'],
            'date_of_employment' => $validated['date_of_employment'],
            'tin_number' => $validated['tin_number'],
            'sss_gsis_no' => $validated['sss_gsis_no'],
            'profile_completed' => true,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');
            $updateData['profile_photo'] = $path;
        }

        // Update member profile
        Member::where('id', $member->id)->update($updateData);
        
        // Mark the User account as pending confirmation (admin will confirm)
        $userAccount = User::where('member_id', $member->id)->first();
        if ($userAccount) {
            $userAccount->confirmed = false; // Keep as false, admin will approve
            $userAccount->save();
        }
        
        return redirect()->route('user.profile.pending')
            ->with('success', 'Profile completed successfully! Your account is now pending admin confirmation.');
    }
    
    public function showPendingConfirmation()
    {
        $member = Auth::guard('member')->user();
        
        if (!$member) {
            return redirect()->route('user.login');
        }
        
        return view('UserSide.auth.pending-confirmation');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:indigo,emerald,rose',
        ]);

        $member = Auth::guard('member')->user();
        if ($member) {
            $member->update([
                'theme' => $request->theme,
            ]);
        }

        $request->session()->put('theme', $request->theme);

        return back();
    }

    public function logout(Request $request)
    {
        // Log the logout activity before actually logging out
        if (Auth::guard('member')->check()) {
            ActivityLogService::log(
                'logout',
                'Member logged out',
                $request
            );
        }

        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    /**
     * Show the generate password form.
     *
     * @return \Illuminate\View\View
     */
    public function showGeneratePasswordForm()
    {
        return view('UserSide.auth.passwords.generate');
    }

    /**
     * Send password change request to admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reason' => 'nullable|string|max:500',
        ]);

        // Check if member exists
        $user = User::where('email', $request->email)->first();
        $member = Member::where('email', $request->email)->first();
        
        if (!$user && !$member) {
            return back()->withErrors([
                'email' => 'We can\'t find a member with that email address.',
            ]);
        }

        // Get member name
        $memberName = 'Unknown Member';
        if ($user && $user->member) {
            $memberName = trim($user->member->first_name . ' ' . $user->member->last_name);
        } elseif ($member) {
            $memberName = trim($member->first_name . ' ' . $member->last_name);
        }

        // Create a contact message for admin
        \App\Models\ContactMessage::create([
            'name' => $memberName,
            'email' => $request->email,
            'subject' => 'Password Change Request',
            'message' => "Member {$memberName} ({$request->email}) has requested a password change.\n\n" . 
                        ($request->reason ? "Reason: {$request->reason}" : "No reason provided."),
            'is_read' => false,
        ]);

        return redirect()->route('user.password.generate')
            ->with('success', 'Your password change request has been sent to the administrator. You will be contacted shortly.');
    }
}
