<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display the member's activity log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // 'access' for login/logout/dashboard only
        $activities = ActivityLogService::getMemberActivities(
            auth()->guard('member')->id(),
            $filter === 'access' ? 'access' : null
        );
        
        return view('UserSide.activity_log', [
            'activities' => $activities,
            'currentFilter' => $filter,
        ]);
    }
}
