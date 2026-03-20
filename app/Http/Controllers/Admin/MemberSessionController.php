<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberSessionController extends Controller
{
    /**
     * Show all member accounts with last login and online status.
     */
    public function index()
    {
        $onlineThresholdMinutes = 15;
        $onlineSince = Carbon::now()->subMinutes($onlineThresholdMinutes);

        // Aggregate activity data per member in a subquery to avoid ONLY_FULL_GROUP_BY issues
        $activitySub = ActivityLog::select(
                'member_id',
                DB::raw("MAX(CASE WHEN activity_type = 'login' THEN created_at END) as last_login_at"),
                DB::raw('MAX(created_at) as last_activity_at')
            )
            ->groupBy('member_id');

        $members = Member::leftJoinSub($activitySub, 'activity_stats', function ($join) {
                $join->on('activity_stats.member_id', '=', 'members.id');
            })
            ->select('members.*', 'activity_stats.last_login_at', 'activity_stats.last_activity_at')
            ->orderByDesc('activity_stats.last_activity_at')
            ->orderBy('members.last_name')
            ->orderBy('members.first_name')
            ->get()
            ->map(function ($member) use ($onlineSince) {
                $member->last_login_at = $member->last_login_at ? Carbon::parse($member->last_login_at) : null;
                $member->last_activity_at = $member->last_activity_at ? Carbon::parse($member->last_activity_at) : null;
                $member->is_online = $member->last_activity_at && $member->last_activity_at->greaterThan($onlineSince);
                return $member;
            });

        return view('AdminSide.members.sessions', [
            'members' => $members,
            'onlineThresholdMinutes' => $onlineThresholdMinutes,
        ]);
    }
}

