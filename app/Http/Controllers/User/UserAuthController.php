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
        return view('UserSide.auth.login');
    }

    public function showRegistrationForm()
    {
        return view('UserSide.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'phone' => 'required|string|max:20|unique:members',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Generate unique member_id
        $today = date('Ymd');
        $prefix = 'M' . $today;
        
        // Find the highest member_id for today
        $lastMember = Member::where('member_id', 'LIKE', $prefix . '%')
            ->orderBy('member_id', 'desc')
            ->first();
        
        if ($lastMember) {
            $lastSequence = (int) substr($lastMember->member_id, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }
        
        $memberId = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

        $member = Member::create([
            'member_id' => $memberId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'join_date' => now(),
            'status' => 'pending'
        ]);

        // Log the registration activity (tie explicitly to the new member)
        ActivityLogService::log(
            'registration',
            'New member registration',
            $request,
            $member->id
        );

        // Automatically log in the user after registration
        Auth::guard('member')->login($member);

        return redirect()->route('user.dashboard')
            ->with('status', 'Registration successful! Welcome to the cooperative.');
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
            
            // If account not confirmed, redirect to profile completion
            if (!$user->confirmed) {
                return redirect()->route('user.profile.complete');
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
     * Show the password reset form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm()
    {
        return view('UserSide.auth.passwords.reset');
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors([
                'email' => 'We can\'t find a member with that email address.',
            ]);
        }

        $member->password = bcrypt($request->password);
        $member->save();

        // Log the password reset activity (ensure it's tied to the member)
        ActivityLogService::log(
            'password_reset',
            'Member reset their password',
            $request,
            $member->id
        );

        return redirect()->route('user.login')
            ->with('status', 'Password has been reset successfully.');
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
     * Generate a new password for the member and display it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $plainPassword = \Illuminate\Support\Str::random(12);

        // Check User first (admin-created accounts)
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($plainPassword);
            $user->save();
            $member = $user->member;
            if ($member) {
                $member->password = Hash::make($plainPassword);
                $member->save();
            }
            ActivityLogService::log('password_reset', 'Member requested generated password', $request, $member?->id);
            return redirect()->route('user.password.generate')
                ->with('generated_password', $plainPassword);
        }

        // Check Member (direct registration)
        $member = Member::where('email', $request->email)->first();
        if ($member) {
            $member->password = Hash::make($plainPassword);
            $member->save();
            ActivityLogService::log('password_reset', 'Member requested generated password', $request, $member->id);
            return redirect()->route('user.password.generate')
                ->with('generated_password', $plainPassword);
        }

        return back()->withErrors([
            'email' => 'We can\'t find a member with that email address.',
        ]);
    }
}
