<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Dividend;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $totalContributions = Contribution::where('status', 'approved')->sum('amount');
        $totalLoans = Loan::where('status', 'approved')->sum('amount');
        $totalDividends = Dividend::sum('dividend_amount');
        
        // Monthly contributions data for chart (last 12 months)
        $monthlyContributions = [];
        $monthlyLoans = [];
        $chartLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            
            $monthlyContributions[] = (float) Contribution::where('status', 'approved')
                ->whereYear('contribution_date', $date->year)
                ->whereMonth('contribution_date', $date->month)
                ->sum('amount');
                
            $monthlyLoans[] = (float) Loan::where('status', 'approved')
                ->whereYear('application_date', $date->year)
                ->whereMonth('application_date', $date->month)
                ->sum('amount');
        }
        
        // Member growth data (last 12 months)
        $memberGrowthLabels = [];
        $memberGrowthData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $memberGrowthLabels[] = $date->format('M');
            $memberGrowthData[] = Member::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        // Prescriptive Analytics Recommendations
        $recommendations = $this->generateRecommendations();
        
        return view('AdminSide.reports.index', compact(
            'totalMembers',
            'activeMembers',
            'totalContributions',
            'totalLoans',
            'totalDividends',
            'chartLabels',
            'monthlyContributions',
            'monthlyLoans',
            'memberGrowthLabels',
            'memberGrowthData',
            'recommendations'
        ));
    }

    public function contributions(Request $request)
    {
        $query = Contribution::with('member');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($mq) use ($search) {
                    $mq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('member_id', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('contribution_type', $request->type);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('contribution_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('contribution_date', '<=', $request->date_to);
        }

        $contributions = $query->latest('contribution_date')->paginate(20)->appends($request->all());

        // Stats
        $totalAmount = Contribution::where('status', 'approved')->sum('amount');
        $totalCount = Contribution::count();
        $approvedCount = Contribution::where('status', 'approved')->count();
        $pendingCount = Contribution::where('status', 'pending')->count();
        $thisMonthAmount = Contribution::where('status', 'approved')
            ->whereYear('contribution_date', now()->year)
            ->whereMonth('contribution_date', now()->month)
            ->sum('amount');
        $lastMonthAmount = Contribution::where('status', 'approved')
            ->whereYear('contribution_date', now()->subMonth()->year)
            ->whereMonth('contribution_date', now()->subMonth()->month)
            ->sum('amount');
        $growthPercent = $lastMonthAmount > 0 ? round(($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount * 100, 1) : 0;

        // Chart: Monthly contributions (last 6 months)
        $contribChartLabels = [];
        $contribChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $contribChartLabels[] = $date->format('M');
            $contribChartData[] = (float) Contribution::where('status', 'approved')
                ->whereYear('contribution_date', $date->year)
                ->whereMonth('contribution_date', $date->month)
                ->sum('amount');
        }

        // Contribution types for filter
        $contributionTypes = Contribution::select('contribution_type')
            ->whereNotNull('contribution_type')
            ->distinct()
            ->pluck('contribution_type');

        return view('AdminSide.reports.contributions', compact(
            'contributions',
            'totalAmount',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'thisMonthAmount',
            'growthPercent',
            'contribChartLabels',
            'contribChartData',
            'contributionTypes'
        ));
    }

    public function loans(Request $request)
    {
        $query = Loan::with(['member', 'repayments']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($mq) use ($search) {
                    $mq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('member_id', 'like', "%{$search}%");
                })
                ->orWhere('id', $search);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('application_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('application_date', '<=', $request->date_to);
        }

        $loans = $query->latest('application_date')->paginate(20)->appends($request->all());

        // Stats
        $totalLoanAmount = Loan::where('status', 'approved')->sum('amount');
        $totalCount = Loan::count();
        $approvedCount = Loan::where('status', 'approved')->count();
        $pendingCount = Loan::where('status', 'pending')->count();
        $rejectedCount = Loan::where('status', 'rejected')->count();
        $totalRepayments = LoanRepayment::sum('amount');
        $outstandingBalance = $totalLoanAmount - $totalRepayments;
        $repaymentRate = $totalLoanAmount > 0 ? round($totalRepayments / $totalLoanAmount * 100, 1) : 0;

        // Chart: Monthly loan disbursements (last 6 months)
        $loanChartLabels = [];
        $loanChartData = [];
        $repayChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $loanChartLabels[] = $date->format('M');
            $loanChartData[] = (float) Loan::where('status', 'approved')
                ->whereYear('application_date', $date->year)
                ->whereMonth('application_date', $date->month)
                ->sum('amount');
            $repayChartData[] = (float) LoanRepayment::whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->sum('amount');
        }

        // Status distribution for doughnut chart
        $statusDistribution = [
            'approved' => Loan::where('status', 'approved')->count(),
            'pending' => $pendingCount,
            'rejected' => $rejectedCount,
            'completed' => Loan::where('status', 'completed')->count(),
        ];

        return view('AdminSide.reports.loans', compact(
            'loans',
            'totalLoanAmount',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'totalRepayments',
            'outstandingBalance',
            'repaymentRate',
            'loanChartLabels',
            'loanChartData',
            'repayChartData',
            'statusDistribution'
        ));
    }

    public function dividends(Request $request)
    {
        $year = $request->get('year', now()->year);
        $dividends = Dividend::with('member')
            ->where('year', $year)
            ->orderByDesc('dividend_amount')
            ->get();
        
        $totalDividends = $dividends->sum('dividend_amount');
        $membersEligible = $dividends->count();
        $averageDividend = $membersEligible > 0 ? $totalDividends / $membersEligible : 0;
        $releasedCount = $dividends->where('status', 'released')->count();
        $pendingCount = $dividends->where('status', 'pending')->count();
        
        $availableYears = Dividend::select('year')->distinct()->orderByDesc('year')->pluck('year');
        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        // Chart data: top 10 members by dividend amount
        $topMembers = $dividends->take(10);
        $dividendChartLabels = $topMembers->map(function ($d) {
            return ($d->member->first_name ?? '') . ' ' . substr($d->member->last_name ?? '', 0, 1) . '.';
        })->values()->toArray();
        $dividendChartData = $topMembers->pluck('dividend_amount')->map(fn($v) => (float) $v)->values()->toArray();
        $contributionChartData = $topMembers->pluck('total_contributions')->map(fn($v) => (float) $v)->values()->toArray();

        return view('AdminSide.reports.dividends', compact(
            'dividends',
            'year',
            'totalDividends',
            'membersEligible',
            'averageDividend',
            'releasedCount',
            'pendingCount',
            'availableYears',
            'dividendChartLabels',
            'dividendChartData',
            'contributionChartData'
        ));
    }

    public function calculateDividends(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . now()->year,
            'dividend_rate' => 'required|numeric|min:0.01|max:100',
        ]);

        $year = $request->year;
        $rate = $request->dividend_rate / 100;

        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('contribution_date', $year);
            }])
            ->get();

        $count = 0;
        foreach ($members as $member) {
            $totalContributions = $member->contributions->sum('amount');
            
            if ($totalContributions <= 0) {
                continue;
            }

            $dividendAmount = $totalContributions * $rate;

            Dividend::updateOrCreate(
                ['member_id' => $member->id, 'year' => $year],
                [
                    'total_contributions' => $totalContributions,
                    'dividend_rate' => $rate,
                    'dividend_amount' => $dividendAmount,
                    'status' => 'pending',
                ]
            );
            $count++;
        }

        return redirect()->route('admin.reports.dividends', ['year' => $year])
            ->with('success', "Dividends calculated for {$count} members at " . ($rate * 100) . "% rate.");
    }

    public function releaseDividends(Request $request)
    {
        $request->validate(['year' => 'required|integer']);

        Dividend::where('year', $request->year)
            ->where('status', 'pending')
            ->update([
                'status' => 'released',
                'released_at' => now(),
            ]);

        return redirect()->route('admin.reports.dividends', ['year' => $request->year])
            ->with('success', 'All pending dividends have been released.');
    }

    public function schedule(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        // Monthly data for Cash on Hand
        $cashOnHand = [];
        $totalCashCollection = 0;
        $totalCashDisbursement = 0;
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::create($year, $m, 1)->endOfMonth();
            $collection = (float) Contribution::where('status', 'approved')
                ->whereYear('contribution_date', $year)
                ->whereMonth('contribution_date', $m)
                ->sum('amount');
            $disbursement = (float) Loan::where('status', 'approved')
                ->whereYear('approval_date', $year)
                ->whereMonth('approval_date', $m)
                ->sum('amount');
            $cashOnHand[] = [
                'date' => $date->format('n/j/Y'),
                'collection' => $collection,
                'disbursement' => $disbursement,
            ];
            $totalCashCollection += $collection;
            $totalCashDisbursement += $disbursement;
        }

        $cashBalBeg = (float) $request->get('cash_beg', 0);
        $cashBalEnd = $totalCashCollection + $cashBalBeg - $totalCashDisbursement;

        // Monthly data for Loans Receivable
        $loansReceivable = [];
        $totalLoanReleases = 0;
        $totalLoanRepayment = 0;
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::create($year, $m, 1)->endOfMonth();
            $releases = (float) Loan::where('status', 'approved')
                ->whereYear('approval_date', $year)
                ->whereMonth('approval_date', $m)
                ->sum('amount');
            $repayment = (float) LoanRepayment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $m)
                ->sum('amount');
            $loansReceivable[] = [
                'date' => $date->format('n/j/Y'),
                'releases' => $releases,
                'repayment' => $repayment,
            ];
            $totalLoanReleases += $releases;
            $totalLoanRepayment += $repayment;
        }

        $loansBalBeg = (float) $request->get('loans_beg', 0);
        $loansShortOver = (float) $request->get('loans_short_over', 0);
        $loansBalEnd = $totalLoanReleases + $loansBalBeg + $loansShortOver - $totalLoanRepayment;

        // CBU / SSFD / Members Savings: monthly by contribution_type
        $types = [
            'cbu' => ['type' => 'regular', 'label' => 'CAPITAL BUILD UP'],
            'ssfd' => ['type' => 'special', 'label' => 'SPECIAL SAVINGS DEPOSIT FUND'],
            'savings' => ['type' => 'emergency', 'label' => 'MEMBERS SAVINGS DEPOSIT'],
        ];
        $cbuSavingsData = [];
        foreach ($types as $key => $config) {
            $monthly = [];
            $totalWithdraw = 0;
            $totalContrib = 0;
            for ($m = 1; $m <= 12; $m++) {
                $date = Carbon::create($year, $m, 1)->endOfMonth();
                $contribQuery = Contribution::where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->whereMonth('contribution_date', $m);
                $contrib = (float) ($config['type'] === 'regular'
                    ? $contribQuery->where(function ($q) {
                        $q->where('contribution_type', 'regular')->orWhereNull('contribution_type');
                    })->sum('amount')
                    : $contribQuery->where('contribution_type', $config['type'])->sum('amount'));
                $withdraw = 0; // Withdrawals not tracked in current schema
                $monthly[] = [
                    'date' => $date->format('n/j/Y'),
                    'withdraw' => $withdraw,
                    'contribution' => $contrib,
                ];
                $totalWithdraw += $withdraw;
                $totalContrib += $contrib;
            }
            $balBeg = (float) $request->get("{$key}_beg", 0);
            $balEnd = $totalContrib + $balBeg - $totalWithdraw;
            $cbuSavingsData[$key] = [
                'label' => $config['label'],
                'monthly' => $monthly,
                'total_withdraw' => $totalWithdraw,
                'total_contribution' => $totalContrib,
                'bal_beg' => $balBeg,
                'bal_end' => $balEnd,
            ];
        }

        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();

        return view('AdminSide.reports.schedule', compact(
            'year',
            'cashOnHand',
            'totalCashCollection',
            'totalCashDisbursement',
            'cashBalBeg',
            'cashBalEnd',
            'loansReceivable',
            'totalLoanReleases',
            'totalLoanRepayment',
            'loansBalBeg',
            'loansShortOver',
            'loansBalEnd',
            'cbuSavingsData',
            'availableYears'
        ));
    }

    public function scheduleCbu(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where(function ($mq) {
                        $mq->where('contribution_type', 'regular')->orWhereNull('contribution_type');
                    });
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) {
                $amount = $m->contributions->sum('amount');
                return ['no' => 0, 'name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $amount];
            })
            ->filter(fn($r) => $r['amount'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $blocks = $members->chunk(27)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-cbu', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleLoansReceivable(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['loans' => function ($q) {
                $q->where('status', 'approved')->with('repayments');
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) {
                $outstanding = $m->loans->sum(function ($loan) {
                    $bal = $loan->remaining_balance ?? ($loan->amount - $loan->repayments->sum('amount'));
                    return max(0, (float) $bal);
                });
                return ['no' => 0, 'name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $outstanding];
            })
            ->filter(fn($r) => $r['amount'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $blocks = $members->chunk(27)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-loans-receivable', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleSavings(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where('contribution_type', 'emergency');
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) {
                $amount = $m->contributions->sum('amount');
                return ['no' => 0, 'name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $amount];
            })
            ->filter(fn($r) => $r['amount'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $blocks = $members->chunk(27)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-savings', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleSsfd(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where('contribution_type', 'special');
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) {
                $amount = $m->contributions->sum('amount');
                return ['no' => 0, 'name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $amount];
            })
            ->filter(fn($r) => $r['amount'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $blocks = $members->chunk(27)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-ssfd', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleMortuaryAid(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where('contribution_type', 'mortuary');
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) {
                $amount = $m->contributions->sum('amount');
                return ['no' => 0, 'name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $amount];
            })
            ->filter(fn($r) => $r['amount'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $blocks = $members->chunk(26)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-mortuary-aid', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleMonthlyMortuaryAid(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where('contribution_type', 'mortuary');
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions
                        ->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)
                        ->sum('amount');
                }
                $total = array_sum($monthly);
                return [
                    'no' => 0,
                    'name' => trim($m->first_name . ' ' . $m->last_name),
                    'monthly' => $monthly,
                    'total' => $total,
                ];
            })
            ->filter(fn($r) => $r['total'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $grandTotal = $members->sum('total');
        $monthTotals = [];
        for ($mo = 1; $mo <= 12; $mo++) {
            $monthTotals[$mo] = $members->sum(fn($m) => $m['monthly'][$mo]);
        }
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-monthly-mortuary-aid', compact('members', 'grandTotal', 'monthTotals', 'year', 'availableYears'));
    }

    public function scheduleMonthlyCbu(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')
                    ->whereYear('contribution_date', $year)
                    ->where(function ($mq) {
                        $mq->where('contribution_type', 'regular')->orWhereNull('contribution_type');
                    });
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions
                        ->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)
                        ->sum('amount');
                }
                $total = array_sum($monthly);
                return [
                    'no' => 0,
                    'name' => trim($m->first_name . ' ' . $m->last_name),
                    'monthly' => $monthly,
                    'total' => $total,
                ];
            })
            ->filter(fn($r) => $r['total'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $grandTotal = $members->sum('total');
        $monthTotals = [];
        for ($mo = 1; $mo <= 12; $mo++) {
            $monthTotals[$mo] = $members->sum(fn($m) => $m['monthly'][$mo]);
        }
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-monthly-cbu', compact('members', 'grandTotal', 'monthTotals', 'year', 'availableYears'));
    }

    public function scheduleInterestContribution(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Dividend::where('year', $year)
            ->with('member')
            ->orderByDesc('dividend_amount')
            ->get()
            ->map(function ($d, $i) {
                return [
                    'no' => $i + 1,
                    'name' => trim(($d->member->first_name ?? '') . ' ' . ($d->member->last_name ?? '')),
                    'amount' => (float) $d->dividend_amount,
                ];
            })
            ->values();
        $blocks = $members->chunk(24)->values();
        $grandTotal = $members->sum('amount');
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-interest-contribution', compact('members', 'blocks', 'grandTotal', 'year', 'availableYears'));
    }

    public function scheduleMonthlyContribution(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('contribution_date', $year);
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions
                        ->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)
                        ->sum('amount');
                }
                $total = array_sum($monthly);
                return [
                    'no' => 0,
                    'name' => trim($m->first_name . ' ' . $m->last_name),
                    'monthly' => $monthly,
                    'total' => $total,
                ];
            })
            ->filter(fn($r) => $r['total'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $grandTotal = $members->sum('total');
        $monthTotals = [];
        for ($mo = 1; $mo <= 12; $mo++) {
            $monthTotals[$mo] = $members->sum(fn($m) => $m['monthly'][$mo]);
        }
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-monthly-contribution', compact('members', 'grandTotal', 'monthTotals', 'year', 'availableYears'));
    }

    public function scheduleContributions(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('contribution_date', $year);
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions
                        ->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)
                        ->sum('amount');
                }
                $total = array_sum($monthly);
                return [
                    'no' => 0,
                    'name' => trim($m->first_name . ' ' . $m->last_name),
                    'monthly' => $monthly,
                    'total' => $total,
                ];
            })
            ->filter(fn($r) => $r['total'] > 0)
            ->values()
            ->map(fn($m, $i) => array_merge($m, ['no' => $i + 1]));
        $grandTotal = $members->sum('total');
        $monthTotals = [];
        for ($mo = 1; $mo <= 12; $mo++) {
            $monthTotals[$mo] = $members->sum(fn($m) => $m['monthly'][$mo]);
        }
        $availableYears = collect(range(max(2020, now()->year - 5), now()->year))->reverse()->values();
        return view('AdminSide.reports.schedule-contributions', compact('members', 'grandTotal', 'monthTotals', 'year', 'availableYears'));
    }

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('member')->latest();

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        if ($request->filled('type')) {
            $query->where('activity_type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->appends($request->all());
        $members = Member::orderBy('first_name')->get();
        $activityTypes = ActivityLog::select('activity_type')->distinct()->pluck('activity_type');

        $totalLogs = ActivityLog::count();
        $todayLogs = ActivityLog::whereDate('created_at', today())->count();
        $loginLogs = ActivityLog::where('activity_type', 'login')->count();

        return view('AdminSide.reports.activity-logs', compact(
            'logs',
            'members',
            'activityTypes',
            'totalLogs',
            'todayLogs',
            'loginLogs'
        ));
    }

    public function exportContributions(Request $request)
    {
        $query = Contribution::with('member')->where('status', 'approved');

        if ($request->filled('date_from')) {
            $query->whereDate('contribution_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('contribution_date', '<=', $request->date_to);
        }

        $contributions = $query->latest('contribution_date')->get();

        $filename = 'contributions_report_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contributions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['#', 'Member ID', 'Member Name', 'Amount', 'Date', 'Type', 'Status']);

            foreach ($contributions as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->member->member_id ?? '',
                    ($c->member->first_name ?? '') . ' ' . ($c->member->last_name ?? ''),
                    number_format($c->amount, 2),
                    $c->contribution_date ? Carbon::parse($c->contribution_date)->format('M d, Y') : '',
                    ucfirst($c->contribution_type ?? 'Regular'),
                    ucfirst($c->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportLoans(Request $request)
    {
        $query = Loan::with(['member', 'repayments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('application_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('application_date', '<=', $request->date_to);
        }

        $loans = $query->latest('application_date')->get();

        $filename = 'loans_report_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($loans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Loan #', 'Member ID', 'Member Name', 'Amount', 'Repaid', 'Balance', 'Term', 'Date', 'Status']);

            foreach ($loans as $loan) {
                $repaid = $loan->repayments->sum('amount');
                $balance = max($loan->amount - $repaid, 0);
                fputcsv($file, [
                    $loan->id,
                    $loan->member->member_id ?? '',
                    ($loan->member->first_name ?? '') . ' ' . ($loan->member->last_name ?? ''),
                    number_format($loan->amount, 2),
                    number_format($repaid, 2),
                    number_format($balance, 2),
                    $loan->loan_term ?? '—',
                    $loan->application_date ? Carbon::parse($loan->application_date)->format('M d, Y') : '',
                    ucfirst($loan->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportDividends(Request $request)
    {
        $year = $request->get('year', now()->year);
        $dividends = Dividend::with('member')->where('year', $year)->orderByDesc('dividend_amount')->get();

        $filename = 'dividends_report_' . $year . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($dividends, $year) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Member ID', 'Member Name', 'Total Contributions', 'Rate (%)', 'Dividend Amount', 'Status', 'Year']);

            foreach ($dividends as $d) {
                fputcsv($file, [
                    $d->member->member_id ?? '',
                    ($d->member->first_name ?? '') . ' ' . ($d->member->last_name ?? ''),
                    number_format($d->total_contributions, 2),
                    number_format($d->dividend_rate * 100, 2),
                    number_format($d->dividend_amount, 2),
                    ucfirst($d->status),
                    $year,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportSchedule(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $cashOnHand = [];
        $totalCashCollection = 0;
        $totalCashDisbursement = 0;
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::create($year, $m, 1)->endOfMonth();
            $collection = (float) Contribution::where('status', 'approved')->whereYear('contribution_date', $year)->whereMonth('contribution_date', $m)->sum('amount')
                + (float) LoanRepayment::whereYear('payment_date', $year)->whereMonth('payment_date', $m)->sum('amount');
            $disbursement = (float) Loan::where('status', 'approved')->whereYear('approval_date', $year)->whereMonth('approval_date', $m)->sum('amount');
            $cashOnHand[] = ['date' => $date->format('n/j/Y'), 'collection' => $collection, 'disbursement' => $disbursement];
            $totalCashCollection += $collection;
            $totalCashDisbursement += $disbursement;
        }
        $cashBalBeg = (float) $request->get('cash_beg', 0);
        $cashShortOver = (float) $request->get('cash_short_over', 0);
        $cashBalEnd = $totalCashCollection + $cashBalBeg + $cashShortOver - $totalCashDisbursement;

        $loansReceivable = [];
        $totalLoanReleases = 0;
        $totalLoanRepayment = 0;
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::create($year, $m, 1)->endOfMonth();
            $releases = (float) Loan::where('status', 'approved')->whereYear('approval_date', $year)->whereMonth('approval_date', $m)->sum('amount');
            $repayment = (float) LoanRepayment::whereYear('payment_date', $year)->whereMonth('payment_date', $m)->sum('amount');
            $loansReceivable[] = ['date' => $date->format('n/j/Y'), 'releases' => $releases, 'repayment' => $repayment];
            $totalLoanReleases += $releases;
            $totalLoanRepayment += $repayment;
        }
        $loansBalBeg = (float) $request->get('loans_beg', 0);
        $loansShortOver = (float) $request->get('loans_short_over', 0);
        $loansBalEnd = $totalLoanReleases + $loansBalBeg + $loansShortOver - $totalLoanRepayment;

        $filename = 'schedule_report_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($cashOnHand, $totalCashCollection, $totalCashDisbursement, $cashBalBeg, $cashBalEnd, $loansReceivable, $totalLoanReleases, $totalLoanRepayment, $loansBalBeg, $loansBalEnd) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SCHEDULE OF CASH ON HAND / LOANS RECEIVABLE']);
            fputcsv($file, ['Date', 'Cash Collection', 'Cash Disbursement']);
            foreach ($cashOnHand as $r) {
                fputcsv($file, [$r['date'], number_format($r['collection'], 2), number_format($r['disbursement'], 2)]);
            }
            fputcsv($file, ['Total', number_format($totalCashCollection, 2), number_format($totalCashDisbursement, 2)]);
            fputcsv($file, ['Bal. Beg.', number_format($cashBalBeg, 2), '']);
            fputcsv($file, ['Bal. End', number_format($cashBalEnd, 2), '']);
            fputcsv($file, []);
            fputcsv($file, ['Date', 'Loan Releases', 'Loan Repayment']);
            foreach ($loansReceivable as $r) {
                fputcsv($file, [$r['date'], number_format($r['releases'], 2), number_format($r['repayment'], 2)]);
            }
            fputcsv($file, ['Total', number_format($totalLoanReleases, 2), number_format($totalLoanRepayment, 2)]);
            fputcsv($file, ['Bal. Beg.', number_format($loansBalBeg, 2), '']);
            fputcsv($file, ['Bal. End', number_format($loansBalEnd, 2), '']);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleCbu(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('contribution_date', $year)
                    ->where(function ($mq) { $mq->where('contribution_type', 'regular')->orWhereNull('contribution_type'); });
            }])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(fn($m) => ['name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $m->contributions->sum('amount')])
            ->filter(fn($r) => $r['amount'] > 0)->values();
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_cbu_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Amount']);
            foreach ($members as $i => $m) {
                fputcsv($file, [$i + 1, $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleLoansReceivable(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['loans' => fn($q) => $q->where('status', 'approved')->with('repayments')])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(function ($m) {
                $outstanding = $m->loans->sum(fn($loan) => max(0, (float) ($loan->remaining_balance ?? ($loan->amount - $loan->repayments->sum('amount')))));
                return ['name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => $outstanding];
            })
            ->filter(fn($r) => $r['amount'] > 0)->values();
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_loans_receivable_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Outstanding Balance']);
            foreach ($members as $i => $m) {
                fputcsv($file, [$i + 1, $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleSavings(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)->where('contribution_type', 'emergency')])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(fn($m) => ['name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $m->contributions->sum('amount')])
            ->filter(fn($r) => $r['amount'] > 0)->values();
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_savings_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Amount']);
            foreach ($members as $i => $m) {
                fputcsv($file, [$i + 1, $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleSsfd(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)->where('contribution_type', 'special')])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(fn($m) => ['name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $m->contributions->sum('amount')])
            ->filter(fn($r) => $r['amount'] > 0)->values();
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_ssfd_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Amount']);
            foreach ($members as $i => $m) {
                fputcsv($file, [$i + 1, $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleMortuaryAid(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)->where('contribution_type', 'mortuary')])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(fn($m) => ['name' => trim($m->first_name . ' ' . $m->last_name), 'amount' => (float) $m->contributions->sum('amount')])
            ->filter(fn($r) => $r['amount'] > 0)->values();
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_mortuary_aid_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Amount']);
            foreach ($members as $i => $m) {
                fputcsv($file, [$i + 1, $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleInterestContribution(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Dividend::where('year', $year)->with('member')->orderByDesc('dividend_amount')->get()
            ->map(fn($d, $i) => ['no' => $i + 1, 'name' => trim(($d->member->first_name ?? '') . ' ' . ($d->member->last_name ?? '')), 'amount' => (float) $d->dividend_amount]);
        $grandTotal = $members->sum('amount');
        $filename = 'schedule_interest_contribution_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $grandTotal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No.', 'Name', 'Amount']);
            foreach ($members as $m) {
                fputcsv($file, [$m['no'], $m['name'], number_format($m['amount'], 2)]);
            }
            fputcsv($file, ['', 'GRAND TOTAL', number_format($grandTotal, 2)]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleMonthlyMortuaryAid(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)->where('contribution_type', 'mortuary')])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)->sum('amount');
                }
                return ['name' => trim($m->first_name . ' ' . $m->last_name), 'monthly' => $monthly, 'total' => array_sum($monthly)];
            })
            ->filter(fn($r) => $r['total'] > 0)->values();
        $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $filename = 'schedule_monthly_mortuary_aid_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $monthNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_merge(['No.', 'Name'], array_slice($monthNames, 1), ['Total']));
            foreach ($members as $i => $m) {
                $row = [$i + 1, $m['name']];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $row[] = number_format($m['monthly'][$mo], 2);
                }
                $row[] = number_format($m['total'], 2);
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleMonthlyCbu(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => function ($q) use ($year) {
                $q->where('status', 'approved')->whereYear('contribution_date', $year)
                    ->where(function ($mq) {
                        $mq->where('contribution_type', 'regular')->orWhereNull('contribution_type');
                    });
            }])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)->sum('amount');
                }
                return ['name' => trim($m->first_name . ' ' . $m->last_name), 'monthly' => $monthly, 'total' => array_sum($monthly)];
            })
            ->filter(fn($r) => $r['total'] > 0)->values();
        $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $filename = 'schedule_monthly_cbu_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $monthNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_merge(['No.', 'Name'], array_slice($monthNames, 1), ['Total']));
            foreach ($members as $i => $m) {
                $row = [$i + 1, $m['name']];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $row[] = number_format($m['monthly'][$mo], 2);
                }
                $row[] = number_format($m['total'], 2);
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleMonthlyContribution(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)->sum('amount');
                }
                return ['name' => trim($m->first_name . ' ' . $m->last_name), 'monthly' => $monthly, 'total' => array_sum($monthly)];
            })
            ->filter(fn($r) => $r['total'] > 0)->values();
        $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $filename = 'schedule_monthly_contribution_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $monthNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_merge(['No.', 'Name'], array_slice($monthNames, 1), ['Total']));
            foreach ($members as $i => $m) {
                $row = [$i + 1, $m['name']];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $row[] = number_format($m['monthly'][$mo], 2);
                }
                $row[] = number_format($m['total'], 2);
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportScheduleContributions(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $members = Member::where('status', 'active')
            ->with(['contributions' => fn($q) => $q->where('status', 'approved')->whereYear('contribution_date', $year)])
            ->orderBy('last_name')->orderBy('first_name')->get()
            ->map(function ($m) use ($year) {
                $monthly = [];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $monthly[$mo] = (float) $m->contributions->filter(fn($c) => $c->contribution_date && $c->contribution_date->month == $mo)->sum('amount');
                }
                return ['name' => trim($m->first_name . ' ' . $m->last_name), 'monthly' => $monthly, 'total' => array_sum($monthly)];
            })
            ->filter(fn($r) => $r['total'] > 0)->values();
        $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $filename = 'schedule_contributions_' . $year . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($members, $monthNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_merge(['No.', 'Name'], array_slice($monthNames, 1), ['Total']));
            foreach ($members as $i => $m) {
                $row = [$i + 1, $m['name']];
                for ($mo = 1; $mo <= 12; $mo++) {
                    $row[] = number_format($m['monthly'][$mo], 2);
                }
                $row[] = number_format($m['total'], 2);
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportActivityLogs(Request $request)
    {
        $query = ActivityLog::with('member')->latest();
        if ($request->filled('member_id')) $query->where('member_id', $request->member_id);
        if ($request->filled('type')) $query->where('activity_type', $request->type);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);
        $logs = $query->limit(10000)->get();
        $filename = 'activity_logs_' . now()->format('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['#', 'Member', 'Activity Type', 'Description', 'IP Address', 'Date']);
            foreach ($logs as $log) {
                $memberName = $log->member ? trim($log->member->first_name . ' ' . $log->member->last_name) : '—';
                fputcsv($file, [
                    $log->id,
                    $memberName,
                    ucfirst(str_replace('_', ' ', $log->activity_type ?? '')),
                    $log->description ?? '',
                    $log->ip_address ?? '',
                    $log->created_at ? Carbon::parse($log->created_at)->format('Y-m-d H:i:s') : '',
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    private function generateRecommendations()
    {
        $recommendations = [];

        $inactiveWithContributions = Member::where('status', 'inactive')
            ->whereHas('contributions')
            ->count();
        if ($inactiveWithContributions > 0) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fa-user-clock',
                'title' => 'Inactive Members with Contributions',
                'message' => "{$inactiveWithContributions} inactive member(s) have contribution records. Consider reaching out for re-engagement or review their accounts.",
                'action' => route('admin.members.index', ['status' => 'inactive']),
                'action_text' => 'View Inactive Members',
            ];
        }

        $overdueLoans = Loan::where('status', 'approved')
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
        if ($overdueLoans > 0) {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Overdue Loan Payments',
                'message' => "{$overdueLoans} loan(s) are past their due date. Immediate follow-up is recommended to minimize default risk.",
                'action' => route('admin.finance.index', ['tab' => 'loans']),
                'action_text' => 'Review Loans',
            ];
        }

        $pendingLoans = Loan::where('status', 'pending')->count();
        if ($pendingLoans > 0) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'fa-clock',
                'title' => 'Pending Loan Applications',
                'message' => "{$pendingLoans} loan application(s) awaiting review. Timely processing improves member satisfaction.",
                'action' => route('admin.finance.index', ['tab' => 'loans']),
                'action_text' => 'Review Applications',
            ];
        }

        $lastMonth = Contribution::where('status', 'approved')
            ->whereBetween('contribution_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');
        $twoMonthsAgo = Contribution::where('status', 'approved')
            ->whereBetween('contribution_date', [now()->subMonths(2)->startOfMonth(), now()->subMonths(2)->endOfMonth()])
            ->sum('amount');
        if ($twoMonthsAgo > 0 && $lastMonth < $twoMonthsAgo * 0.8) {
            $decline = round((1 - $lastMonth / $twoMonthsAgo) * 100);
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fa-chart-line',
                'title' => 'Declining Contributions',
                'message' => "Contributions dropped by {$decline}% last month compared to the previous month. Consider a member engagement campaign or contribution drive.",
                'action' => route('admin.reports.contributions'),
                'action_text' => 'View Contribution Report',
            ];
        }

        $noRecentContribs = Member::where('status', 'active')
            ->whereDoesntHave('contributions', function ($q) {
                $q->where('contribution_date', '>=', now()->subMonths(3));
            })->count();
        if ($noRecentContribs > 3) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'fa-users',
                'title' => 'Members Without Recent Contributions',
                'message' => "{$noRecentContribs} active member(s) haven't made contributions in 3 months. Send reminders or schedule follow-up meetings.",
                'action' => route('admin.members.index'),
                'action_text' => 'View Members',
            ];
        }

        $currentYear = now()->year;
        $existingDividend = Dividend::where('year', $currentYear)->exists();
        $totalApprovedContribs = Contribution::where('status', 'approved')
            ->whereYear('contribution_date', $currentYear)
            ->sum('amount');
        if (!$existingDividend && $totalApprovedContribs > 0 && now()->month >= 10) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fa-percent',
                'title' => 'Year-End Dividend Calculation',
                'message' => "Year-end approaching with ₱" . number_format($totalApprovedContribs, 0) . " in contributions. Consider calculating and distributing dividends to members.",
                'action' => route('admin.reports.dividends'),
                'action_text' => 'Calculate Dividends',
            ];
        }

        $activeMembers = Member::where('status', 'active')->count();
        $membersWithLoans = Loan::where('status', 'approved')
            ->where('remaining_balance', '>', 0)
            ->distinct('member_id')
            ->count('member_id');
        if ($activeMembers > 0 && ($membersWithLoans / $activeMembers) > 0.6) {
            $pct = round(($membersWithLoans / $activeMembers) * 100);
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fa-scale-balanced',
                'title' => 'High Loan Utilization Rate',
                'message' => "{$pct}% of active members have outstanding loans. Monitor liquidity and consider adjusting loan policies to manage risk.",
                'action' => route('admin.reports.loans'),
                'action_text' => 'View Loan Report',
            ];
        }

        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fa-check-circle',
                'title' => 'All Systems Healthy',
                'message' => 'No immediate actions needed. The cooperative is running smoothly with all metrics within normal ranges.',
                'action' => null,
                'action_text' => null,
            ];
        }

        return $recommendations;
    }
}
