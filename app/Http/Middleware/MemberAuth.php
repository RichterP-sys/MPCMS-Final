<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->route('user.login');
        }

        $member = Auth::guard('member')->user();
        
        // Check if profile is completed
        if (!$member->profile_completed) {
            return redirect()->route('user.profile.complete')
                ->with('warning', 'Please complete your profile first.');
        }
        
        // Check if account is confirmed by admin
        $userAccount = User::where('member_id', $member->id)->first();
        if (!$userAccount || !$userAccount->confirmed) {
            return redirect()->route('user.profile.pending')
                ->with('info', 'Your account is pending admin confirmation.');
        }

        return $next($request);
    }
}
