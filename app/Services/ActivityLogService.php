<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log an activity.
     *
     * When $memberIdOverride is provided, it will be used even if the user is not
     * authenticated yet (e.g., during registration or password reset flows).
     *
     * @param string $activityType
     * @param string $description
     * @param Request|null $request
     * @param int|null $memberIdOverride
     * @return ActivityLog
     */
    public static function log($activityType, $description, Request $request = null, $memberIdOverride = null)
    {
        $request = $request ?? request();

        // Determine member_id based on provided override or authentication context
        $memberId = $memberIdOverride;
        if ($memberId === null && Auth::guard('member')->check()) {
            $memberId = Auth::guard('member')->id();
        }
        // Note: Admin activities (web guard) will have member_id as null

        return ActivityLog::create([
            'member_id' => $memberId,
            'activity_type' => $activityType,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    /**
     * Get all activities for a member
     *
     * @param int $memberId
     * @param string|null $filter 'access' for login/logout/dashboard_access only, null for all
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMemberActivities($memberId, $filter = null)
    {
        $query = ActivityLog::where('member_id', $memberId)
            ->orderBy('created_at', 'desc');

        if ($filter === 'access') {
            $query->whereIn('activity_type', ['login', 'logout', 'dashboard_access']);
        }

        return $query->get();
    }

    /**
     * Get recent activities
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentActivities($limit = 10)
    {
        return ActivityLog::with('member')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
