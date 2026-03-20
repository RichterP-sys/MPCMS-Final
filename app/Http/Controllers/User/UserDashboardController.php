<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $member = auth()->guard('member')->user();
        
        // Log dashboard access
        ActivityLogService::log(
            'dashboard_access',
            'Accessed member dashboard',
            request()
        );
        
        $recentContributions = Contribution::where('member_id', $member->id)
            ->latest()
            ->take(5)
            ->get();

        $activeLoans = Loan::where('member_id', $member->id)
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->get();

        // Net balance: approved contributions − outstanding loan balance
        $totalContributionsApproved = Contribution::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('amount');
        $totalOutstandingLoans = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum(DB::raw('COALESCE(remaining_balance, amount, 0)'));
        $netBalance = $totalContributionsApproved - $totalOutstandingLoans;

        // Get recent activity logs for the member
        $activityLogs = ActivityLog::where('member_id', $member->id)
            ->latest()
            ->take(10)
            ->get();

        // Get access logs (login/logout activities)
        $accessLogs = ActivityLog::where('member_id', $member->id)
            ->whereIn('activity_type', ['login', 'logout', 'dashboard_access'])
            ->latest()
            ->take(10)
            ->get();

        // Get recent repayments/receipts for the member
        $recentRepayments = LoanRepayment::whereHas('loan', function ($query) use ($member) {
            $query->where('member_id', $member->id);
        })
            ->with('loan')
            ->latest('payment_date')
            ->take(5)
            ->get();

        return view('UserSide.dashboard.index', [
            'member' => $member,
            'recentContributions' => $recentContributions,
            'activeLoans' => $activeLoans,
            'activityLogs' => $activityLogs,
            'accessLogs' => $accessLogs,
            'netBalance' => $netBalance,
            'totalOutstandingLoans' => $totalOutstandingLoans,
            'recentRepayments' => $recentRepayments,
        ]);
    }
}
