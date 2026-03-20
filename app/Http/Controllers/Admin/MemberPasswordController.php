<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberPasswordController extends Controller
{
    /**
     * Show the member password lookup/generate form.
     */
    public function index()
    {
        return view('AdminSide.member-password.index');
    }

    /**
     * Generate or set a password for the member.
     * Search by email or username (User.name).
     */
    public function generate(Request $request)
    {
        $rules = [
            'email_or_username' => 'required|string|max:255',
        ];

        $isCustom = $request->input('password_mode') === 'custom';
        if ($isCustom) {
            $rules['custom_password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $search = trim($request->email_or_username);
        $plainPassword = $isCustom
            ? $request->custom_password
            : \Illuminate\Support\Str::random(12);

        // Check User first (by email or username/name)
        $user = User::where('email', $search)
            ->orWhere('name', $search)
            ->first();

        if ($user) {
            $user->password = Hash::make($plainPassword);
            $user->save();
            $member = $user->member;
            if ($member) {
                $member->password = Hash::make($plainPassword);
                $member->save();
            }
            ActivityLogService::log('password_reset', 'Admin set new password for member', $request, $member?->id);
            return redirect()->route('admin.member-password.index')
                ->with('generated_password', $plainPassword)
                ->with('password_mode', $isCustom ? 'custom' : 'generate')
                ->with('member_name', $member ? trim($member->first_name . ' ' . $member->last_name) : $user->name)
                ->with('member_email', $user->email);
        }

        // Check Member (by email or member_id)
        $member = Member::where('email', $search)
            ->orWhere('member_id', $search)
            ->first();

        if ($member) {
            $member->password = Hash::make($plainPassword);
            $member->save();
            $userAccount = $member->memberAccount;
            if ($userAccount) {
                $userAccount->password = Hash::make($plainPassword);
                $userAccount->save();
            }
            ActivityLogService::log('password_reset', 'Admin set new password for member', $request, $member->id);
            return redirect()->route('admin.member-password.index')
                ->with('generated_password', $plainPassword)
                ->with('password_mode', $isCustom ? 'custom' : 'generate')
                ->with('member_name', trim($member->first_name . ' ' . $member->last_name))
                ->with('member_email', $member->email);
        }

        return back()->withErrors([
            'email_or_username' => 'No member found with that email or username.',
        ]);
    }
}
