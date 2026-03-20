<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            $totalMembers = Member::count();
            $activeMembers = Member::where('status', 'active')->count();
            $activeLoans = Loan::whereIn('status', ['approved', 'active'])
                ->where('remaining_balance', '>', 0)
                ->count();
            $totalContributions = Contribution::where('status', 'approved')->sum('amount');
            $recentActivities = ActivityLog::with('member')->latest()->take(10)->get();
            $dueLoans = $this->getOverdueLoansCount();
            $unreadMessages = ContactMessage::unread()->count();

            // Month-over-month comparison
            $membersChange = $this->getMembersChange();
            $loansChange = $this->getActiveLoansChange();
            $contributionsChange = $this->getContributionsChange();

            // Chart data - last 12 months using financial_records
            $monthlyOverview = $this->getMonthlyOverview(12);
            $monthlyLoans = $monthlyOverview['loans'];
            $monthlyContributions = $monthlyOverview['contributions'];
            $monthlyLabels = $monthlyOverview['labels'];

            // Member growth by quarters (current year)
            $memberGrowth = $this->getMemberGrowthByQuarters();

            // Loan status distribution
            $loanStatusData = [
                'approved' => Loan::where('status', 'approved')->count(),
                'pending' => Loan::where('status', 'pending')->count(),
                'rejected' => Loan::where('status', 'rejected')->count(),
                'completed' => Loan::where('status', 'completed')->count()
            ];

            // Monthly revenue (approved contributions, last 6 months)
            $monthlyRevenueData = $this->getMonthlyRevenue(6);
            $monthlyRevenue = $monthlyRevenueData['data'];
            $revenueLabels = $monthlyRevenueData['labels'];

        } catch (\Exception $e) {
            $totalMembers = 0;
            $activeMembers = 0;
            $activeLoans = 0;
            $totalContributions = 0;
            $recentActivities = collect();
            $dueLoans = 0;
            $unreadMessages = 0;
            $membersChange = 0;
            $loansChange = 0;
            $contributionsChange = 0;
            $monthlyLoans = array_fill(0, 12, 0);
            $monthlyContributions = array_fill(0, 12, 0);
            $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $memberGrowth = [0, 0, 0, 0];
            $loanStatusData = [
                'approved' => 0,
                'pending' => 0,
                'rejected' => 0,
                'completed' => 0
            ];
            $monthlyRevenue = array_fill(0, 6, 0);
            $revenueLabels = [];
            for ($i = 5; $i >= 0; $i--) {
                $revenueLabels[] = now()->subMonths($i)->format('M');
            }
        }

        return view('AdminSide.dashboard.index', compact(
            'totalMembers',
            'activeMembers',
            'activeLoans',
            'totalContributions',
            'recentActivities',
            'dueLoans',
            'unreadMessages',
            'membersChange',
            'loansChange',
            'contributionsChange',
            'monthlyLoans',
            'monthlyContributions',
            'monthlyLabels',
            'memberGrowth',
            'loanStatusData',
            'monthlyRevenue',
            'revenueLabels'
        ));
    }

    private function getOverdueLoansCount(): int
    {
        return Loan::whereIn('status', ['approved', 'active'])
            ->where('remaining_balance', '>', 0)
            ->whereNotNull('approval_date')
            ->get()
            ->filter(function ($loan) {
                $months = 12;
                if ($loan->loan_term) {
                    if (preg_match('/(\d+)\s*month/i', $loan->loan_term, $m)) {
                        $months = (int) $m[1];
                    } elseif (preg_match('/(\d+)\s*year/i', $loan->loan_term, $m)) {
                        $months = (int) $m[1] * 12;
                    }
                }
                $dueDate = Carbon::parse($loan->approval_date)->addMonths($months);
                return $dueDate->isPast();
            })->count();
    }

    private function getMembersChange(): float
    {
        $thisMonth = Member::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $lastMonth = Member::whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();
        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : ($thisMonth > 0 ? 100 : 0);
    }

    private function getActiveLoansChange(): float
    {
        $thisMonth = Loan::whereIn('status', ['approved', 'active'])
            ->where('remaining_balance', '>', 0)
            ->whereYear('approval_date', now()->year)
            ->whereMonth('approval_date', now()->month)
            ->count();
        $lastMonth = Loan::whereIn('status', ['approved', 'active'])
            ->where('remaining_balance', '>', 0)
            ->whereYear('approval_date', now()->subMonth()->year)
            ->whereMonth('approval_date', now()->subMonth()->month)
            ->count();
        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : ($thisMonth > 0 ? 100 : 0);
    }

    private function getContributionsChange(): float
    {
        $thisMonth = Contribution::where('status', 'approved')
            ->whereYear('contribution_date', now()->year)
            ->whereMonth('contribution_date', now()->month)
            ->sum('amount');
        $lastMonth = Contribution::where('status', 'approved')
            ->whereYear('contribution_date', now()->subMonth()->year)
            ->whereMonth('contribution_date', now()->subMonth()->month)
            ->sum('amount');
        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : ($thisMonth > 0 ? 100 : 0);
    }

    /**
     * Get monthly overview (loans & contributions) for last N months using financial_records.
     */
    private function getMonthlyOverview(int $months): array
    {
        $loans = [];
        $contributions = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');

            $loans[] = (float) Loan::where('status', 'approved')
                ->whereYear('application_date', $date->year)
                ->whereMonth('application_date', $date->month)
                ->sum('amount');

            $contributions[] = (float) Contribution::where('status', 'approved')
                ->whereYear('contribution_date', $date->year)
                ->whereMonth('contribution_date', $date->month)
                ->sum('amount');
        }

        return [
            'loans' => $loans,
            'contributions' => $contributions,
            'labels' => $labels,
        ];
    }

    private function getMemberGrowthByQuarters(): array
    {
        $year = now()->year;
        $result = DB::select("
            SELECT QUARTER(created_at) as quarter, COUNT(*) as count
            FROM members
            WHERE YEAR(created_at) = ?
            GROUP BY QUARTER(created_at)
            ORDER BY quarter
        ", [$year]);

        $growth = [0, 0, 0, 0];
        foreach ($result as $row) {
            $quarter = (int) $row->quarter - 1;
            if ($quarter >= 0 && $quarter < 4) {
                $growth[$quarter] = (int) $row->count;
            }
        }
        return $growth;
    }

    /**
     * Get monthly revenue (approved contributions) for last N months.
     */
    private function getMonthlyRevenue(int $months): array
    {
        $data = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');
            $data[] = (float) Contribution::where('status', 'approved')
                ->whereYear('contribution_date', $date->year)
                ->whereMonth('contribution_date', $date->month)
                ->sum('amount');
        }

        return ['data' => $data, 'labels' => $labels];
    }
}
