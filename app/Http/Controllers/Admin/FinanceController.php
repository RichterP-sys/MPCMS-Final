<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Contribution;
use App\Services\LoanFinanceService;
use App\Services\NotificationService;
use App\Services\LoanRuleViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display the combined finance management view (loans + contributions in one table).
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        $search = $request->get('search', '');

        $query = DB::table('financial_records')
            ->join('members', 'financial_records.member_id', '=', 'members.id')
            ->select(
                'financial_records.*',
                'members.first_name',
                'members.last_name',
                'members.member_id as member_code'
            )
            ->orderByDesc('financial_records.created_at');

        if ($tab === 'loans') {
            $query->where('financial_records.record_type', 'loan');
        } elseif ($tab === 'contributions') {
            $query->where('financial_records.record_type', 'contribution');
        } elseif ($tab === 'mortuary') {
            $query->where('financial_records.record_type', 'contribution')
                ->where('financial_records.contribution_type', 'mortuary');
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('financial_records.id', 'like', '%' . $search . '%')
                    ->orWhere('members.first_name', 'like', '%' . $search . '%')
                    ->orWhere('members.last_name', 'like', '%' . $search . '%')
                    ->orWhere('members.member_id', 'like', '%' . $search . '%')
                    ->orWhereRaw("CONCAT(members.first_name, ' ', members.last_name) LIKE ?", ['%' . $search . '%'])
                    ->orWhere('financial_records.loan_purpose', 'like', '%' . $search . '%')
                    ->orWhere('financial_records.amount', 'like', '%' . $search . '%');
            });
        }

        $records = $query->paginate(15, ['*'], 'page')->withQueryString();

        // Get totals for stats (query financial_records directly to avoid scope)
        $loans = DB::table('financial_records')->where('record_type', 'loan')->get();
        $contributions = DB::table('financial_records')->where('record_type', 'contribution')->get();

        $totalLoans = $loans->sum('amount');
        $totalContributions = $contributions->sum('amount');
        $pendingLoans = $loans->where('status', 'pending')->count();
        $pendingContributions = $contributions->where('status', 'pending')->count();

        // Get lending freeze status
        $loanFinance = app(LoanFinanceService::class);
        $totalFunds = $loanFinance->getTotalCooperativeFunds();
        $isLendingFrozen = $loanFinance->isLendingFrozen();
        $freezeThreshold = LoanFinanceService::LOAN_FREEZE_THRESHOLD;

        return view('AdminSide.finance.index', compact(
            'records', 'totalLoans', 'totalContributions', 'pendingLoans', 'pendingContributions',
            'totalFunds', 'isLendingFrozen', 'freezeThreshold', 'tab', 'search'
        ));
    }

    /**
     * Display the Repayment Confirmation page (accounts with active loans who haven't paid this month).
     */
    public function repaymentConfirmation(Request $request)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $search = $request->get('search', '');

        $query = Loan::with('member')
            ->whereIn('status', ['approved', 'active'])
            ->where('remaining_balance', '>', 0)
            ->whereNotNull('approval_date')
            ->whereRaw('DATE_ADD(approval_date, INTERVAL 1 MONTH) <= ?', [$endOfMonth])
            ->whereRaw('DATE_ADD(approval_date, INTERVAL COALESCE(term_months, 12) MONTH) >= ?', [$startOfMonth])
            ->whereDoesntHave('repayments', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('financial_records.id', 'like', '%' . $search . '%')
                    ->orWhereHas('member', function ($m) use ($search) {
                        $m->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('member_id', 'like', '%' . $search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                    });
            });
        }

        $activeLoans = $query
            ->orderByRaw('LEAST(DAY(approval_date), DAY(LAST_DAY(CURDATE()))) ASC')
            ->orderBy('approval_date')
            ->paginate(15)
            ->withQueryString();

        return view('AdminSide.finance.repayment-confirmation', compact('activeLoans', 'search'));
    }

    /**
     * Mark loan as "Didn't Pay" and send payment reminder to member.
     */
    public function markDidntPay(Loan $loan)
    {
        if (!in_array($loan->status, ['approved', 'active']) || ($loan->remaining_balance ?? 0) <= 0) {
            return back()->with('error', 'This loan is not eligible.');
        }

        NotificationService::sendPaymentReminder($loan);

        return back()->with('success', 'Payment reminder sent to ' . ($loan->member->first_name ?? '') . ' ' . ($loan->member->last_name ?? '') . '.');
    }

    /**
     * Batch confirm repayments for multiple loans at once.
     */
    public function batchConfirmRepayments(Request $request)
    {
        $validated = $request->validate([
            'loan_ids' => 'required|array',
            'loan_ids.*' => 'required|integer|exists:financial_records,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $loanFinance = app(LoanFinanceService::class);
        $confirmed = 0;
        $errors = [];

        foreach ($validated['loan_ids'] as $loanId) {
            $loan = Loan::with('member')->find($loanId);
            if (!$loan || !in_array($loan->status, ['approved', 'active']) || ($loan->remaining_balance ?? 0) <= 0) {
                continue;
            }
            $hasRepaymentThisMonth = $loan->repayments()->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->exists();
            if ($hasRepaymentThisMonth) {
                continue;
            }
            $amount = (float) min($loan->monthly_repayment ?? 0, $loan->remaining_balance ?? 0);
            if ($amount <= 0) {
                $amount = (float) ($loan->remaining_balance ?? 0);
            }
            if ($amount <= 0) {
                continue;
            }
            try {
                $loanFinance->recordRepayment($loan, $amount, [
                    'payment_date' => $validated['payment_date'],
                    'payment_method' => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'] ?? null,
                ]);
                $loan->refresh();
                NotificationService::repaymentReceived($loan, $amount);
                $confirmed++;
            } catch (LoanRuleViolationException $e) {
                $errors[] = "Loan #{$loan->id}: " . $e->getMessage();
            }
        }

        $successMsg = $confirmed > 0
            ? "{$confirmed} payment(s) confirmed successfully."
            : 'No payments were confirmed.';
        if (!empty($errors)) {
            $msg = $successMsg . ' ' . implode(' ', array_slice($errors, 0, 3));
            return $request->expectsJson()
                ? response()->json(['success' => $confirmed > 0, 'message' => $msg, 'confirmed' => $confirmed])
                : back()->with('error', $msg);
        }
        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $successMsg, 'confirmed' => $confirmed])
            : back()->with('success', $successMsg);
    }
}
