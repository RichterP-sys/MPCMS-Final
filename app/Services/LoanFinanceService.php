<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\CooperativeFund;
use App\Models\Loan;
use App\Models\LoanCollateral;
use App\Models\LoanRepayment;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use App\Services\ReceiptService;

class LoanFinanceService
{
    /**
     * Minimum cooperative fund balance before lending is frozen.
     * When total funds fall to or below this amount, no new loans can be approved.
     */
    public const LOAN_FREEZE_THRESHOLD = 100000.00;

    public function approvedContributionCount(Member $member): int
    {
        return Contribution::query()
            ->where('member_id', $member->id)
            ->where('status', 'approved')
            ->count();
    }

    public function approvedContributionTotal(Member $member): float
    {
        return (float) Contribution::query()
            ->where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('amount');
    }

    public function netContributionTotal(Member $member): float
    {
        $approvedTotal = $this->approvedContributionTotal($member);
        $offsetAmount = (float) ($member->offset_contribution_amount ?? 0);

        return max(0.0, $approvedTotal - $offsetAmount);
    }

    public function frozenContributionTotal(Member $member): float
    {
        return (float) LoanCollateral::query()
            ->where('member_id', $member->id)
            ->sum('frozen_amount');
    }

    public function availableCollateral(Member $member): float
    {
        return max(0.0, $this->netContributionTotal($member) - $this->frozenContributionTotal($member));
    }

    public function loanToContributionRatio(): float
    {
        $ratio = (float) config('loans.limits.loan_to_contribution_ratio', 3.0);
        return $ratio > 0 ? $ratio : 1.0;
    }

    public function maxAdditionalLoanAmount(Member $member): float
    {
        return $this->availableCollateral($member) * $this->loanToContributionRatio();
    }

    public function cooperativeLoanableCapital(): float
    {
        $totalApprovedContributions = (float) Contribution::query()
            ->where('status', 'approved')
            ->sum('amount');

        $totalOffsets = (float) Member::query()->sum('offset_contribution_amount');

        $totalOutstanding = (float) Loan::query()
            ->whereIn('status', ['approved', 'active'])
            ->sum(DB::raw('COALESCE(remaining_balance, amount, 0)'));

        return max(0.0, ($totalApprovedContributions - $totalOffsets) - $totalOutstanding);
    }

    /**
     * Get the total cooperative funds (cash on hand + all bank accounts).
     */
    public function getTotalCooperativeFunds(): float
    {
        return (float) CooperativeFund::active()->sum('amount');
    }

    /**
     * Check if lending is currently frozen due to low cooperative funds.
     */
    public function isLendingFrozen(): bool
    {
        return $this->getTotalCooperativeFunds() <= self::LOAN_FREEZE_THRESHOLD;
    }

    /**
     * Deduct the approved loan amount from cooperative funds (cash on hand).
     * Falls back to bank accounts if cash is insufficient.
     */
    public function deductFundsForLoan(float $amount): void
    {
        $remaining = $amount;

        // First deduct from cash on hand
        $cash = CooperativeFund::active()->cash()->first();
        if ($cash && $cash->amount > 0) {
            $deductFromCash = min($remaining, (float) $cash->amount);
            $cash->amount = round((float) $cash->amount - $deductFromCash, 2);
            $cash->save();
            $remaining = round($remaining - $deductFromCash, 2);
        }

        // If cash wasn't enough, deduct from bank accounts
        if ($remaining > 0) {
            $banks = CooperativeFund::active()->bank()->where('amount', '>', 0)->get();
            foreach ($banks as $bank) {
                if ($remaining <= 0) break;
                $deductFromBank = min($remaining, (float) $bank->amount);
                $bank->amount = round((float) $bank->amount - $deductFromBank, 2);
                $bank->save();
                $remaining = round($remaining - $deductFromBank, 2);
            }
        }
    }

    /**
     * Add repayment amount back to cooperative funds (cash on hand).
     */
    public function replenishFundsFromRepayment(float $amount): void
    {
        $cash = CooperativeFund::active()->cash()->first();
        if ($cash) {
            $cash->amount = round((float) $cash->amount + $amount, 2);
            $cash->save();
        } else {
            // Create a cash on hand record if none exists
            CooperativeFund::create([
                'fund_type' => 'cash',
                'amount' => $amount,
                'description' => 'Cash on Hand',
                'is_active' => true,
            ]);
        }
    }

    /**
     * @throws LoanRuleViolationException
     */
    public function assertEligible(Member $member, float $requestedPrincipal): void
    {
        // Check if lending is frozen due to low cooperative funds
        $totalFunds = $this->getTotalCooperativeFunds();
        if ($totalFunds <= self::LOAN_FREEZE_THRESHOLD) {
            throw new LoanRuleViolationException(
                'Loan applications are temporarily suspended. Cooperative funds are at ₱' . number_format($totalFunds, 2) .
                ' which is at or below the minimum threshold of ₱' . number_format(self::LOAN_FREEZE_THRESHOLD, 2) .
                '. Lending will resume once funds are replenished.',
                [
                    'current_funds' => $totalFunds,
                    'freeze_threshold' => self::LOAN_FREEZE_THRESHOLD,
                ]
            );
        }

        // Check if approving this loan would bring funds below threshold
        $fundsAfterLoan = $totalFunds - $requestedPrincipal;
        if ($fundsAfterLoan < self::LOAN_FREEZE_THRESHOLD) {
            throw new LoanRuleViolationException(
                'Cannot approve this loan. The requested amount of ₱' . number_format($requestedPrincipal, 2) .
                ' would reduce cooperative funds below the minimum threshold of ₱' . number_format(self::LOAN_FREEZE_THRESHOLD, 2) .
                '. Maximum loanable amount is ₱' . number_format(max(0, $totalFunds - self::LOAN_FREEZE_THRESHOLD), 2) . '.',
                [
                    'requested' => $requestedPrincipal,
                    'current_funds' => $totalFunds,
                    'funds_after_loan' => $fundsAfterLoan,
                    'max_loanable' => max(0, $totalFunds - self::LOAN_FREEZE_THRESHOLD),
                ]
            );
        }

        if (config('loans.eligibility.require_active_member', true) && $member->status !== 'active') {
            throw new LoanRuleViolationException('Member is not active and cannot apply for a loan.');
        }

        // Contributions do not affect eligibility; only the admin decides.
        if (!config('loans.eligibility.contributions_affect_eligibility', false)) {
            return;
        }

        $minCount = (int) config('loans.eligibility.min_approved_contributions_count', 0);
        $minAmount = (float) config('loans.eligibility.min_approved_contributions_amount', 0);

        $approvedCount = $this->approvedContributionCount($member);
        $approvedTotal = $this->approvedContributionTotal($member);

        if ($approvedCount < $minCount) {
            throw new LoanRuleViolationException('Insufficient contribution history for loan eligibility.', [
                'approved_contribution_count' => $approvedCount,
                'required_count' => $minCount,
            ]);
        }

        if ($approvedTotal < $minAmount) {
            throw new LoanRuleViolationException('Insufficient total contributions for loan eligibility.', [
                'approved_contribution_total' => $approvedTotal,
                'required_total' => $minAmount,
            ]);
        }

        $maxLoan = $this->maxAdditionalLoanAmount($member);
        if ($requestedPrincipal > $maxLoan) {
            throw new LoanRuleViolationException('Requested amount exceeds the maximum allowed based on contributions.', [
                'requested' => $requestedPrincipal,
                'max_allowed' => $maxLoan,
            ]);
        }

        $pool = $this->cooperativeLoanableCapital();
        if ($requestedPrincipal > $pool) {
            throw new LoanRuleViolationException('Insufficient cooperative capital available for this loan amount.', [
                'requested' => $requestedPrincipal,
                'available_pool' => $pool,
            ]);
        }
    }

    public function parseRequestedAmount(mixed $rawAmount, mixed $otherAmount = null): float
    {
        // Supports numeric, "Others", strings with commas/currency.
        if (is_string($rawAmount) && strtolower(trim($rawAmount)) === 'others') {
            $rawAmount = $otherAmount;
        }

        if (is_numeric($rawAmount)) {
            return max(0.0, (float) $rawAmount);
        }

        if (is_string($rawAmount)) {
            $sanitized = preg_replace('/[^\d.]/', '', $rawAmount);
            return $sanitized !== '' ? max(0.0, (float) $sanitized) : 0.0;
        }

        return 0.0;
    }

    /**
     * Approves/disburses a loan and freezes collateral.
     *
     * @throws LoanRuleViolationException
     */
    public function approveLoan(Loan $loan): void
    {
        DB::transaction(function () use ($loan) {
            $loan->refresh();
            $member = $loan->member()->lockForUpdate()->firstOrFail();

            $principal = $this->parseRequestedAmount($loan->amount ?? $loan->desired_loan_amount, $loan->other_amount_specify);
            if ($principal <= 0) {
                throw new LoanRuleViolationException('Invalid loan amount.');
            }

            $this->assertEligible($member, $principal);

            $contributionsAffectEligibility = config('loans.eligibility.contributions_affect_eligibility', false);

            if ($contributionsAffectEligibility) {
                $ratio = $this->loanToContributionRatio();
                $requiredCollateral = $principal / $ratio;

                // Freeze only the collateral required by the ratio, not the entire contribution balance.
                $availableCollateral = $this->availableCollateral($member);
                if ($requiredCollateral > $availableCollateral) {
                    throw new LoanRuleViolationException('Insufficient available contributions to secure this loan.', [
                        'required_collateral' => $requiredCollateral,
                        'available_collateral' => $availableCollateral,
                    ]);
                }

                LoanCollateral::updateOrCreate(
                    ['loan_id' => $loan->id],
                    [
                        'member_id' => $member->id,
                        'frozen_amount' => $requiredCollateral,
                    ]
                );
            }

            $loan->update([
                'status' => 'approved',
                'approval_date' => now(),
                'amount' => $principal,
                'remaining_balance' => $loan->remaining_balance ?? $principal,
            ]);

            // Deduct the loan amount from cooperative funds
            $this->deductFundsForLoan($principal);
        });
    }

    /**
     * Records a repayment and updates remaining balance and collateral requirement.
     *
     * @throws LoanRuleViolationException
     */
    public function recordRepayment(Loan $loan, float $paymentAmount, array $repaymentData): LoanRepayment
    {
        return DB::transaction(function () use ($loan, $paymentAmount, $repaymentData) {
            $loan = Loan::query()->lockForUpdate()->findOrFail($loan->id);

            if (!in_array($loan->status, ['approved', 'active', 'completed'], true)) {
                throw new LoanRuleViolationException('Loan is not eligible for repayment in its current status.');
            }

            $principal = $this->parseRequestedAmount($loan->amount ?? $loan->desired_loan_amount, $loan->other_amount_specify);
            $remaining = (float) ($loan->remaining_balance ?? $principal);

            if ($remaining <= 0) {
                throw new LoanRuleViolationException('Loan has no remaining balance.');
            }

            if ($paymentAmount <= 0) {
                throw new LoanRuleViolationException('Repayment amount must be greater than zero.');
            }

            if ($paymentAmount > $remaining) {
                throw new LoanRuleViolationException('Repayment amount exceeds remaining loan balance.', [
                    'remaining_balance' => $remaining,
                    'payment_amount' => $paymentAmount,
                ]);
            }

            $repayment = LoanRepayment::create([
                'loan_id' => $loan->id,
                'amount' => $paymentAmount,
                'payment_date' => $repaymentData['payment_date'],
                'payment_method' => $repaymentData['payment_method'],
                'reference_number' => $repaymentData['reference_number'] ?? null,
            ]);

            $newRemaining = round($remaining - $paymentAmount, 2);

            $loan->remaining_balance = $newRemaining;
            if ($newRemaining <= 0) {
                $loan->status = 'completed';
            }
            $loan->save();

            // Recalculate required collateral based on remaining balance.
            $ratio = $this->loanToContributionRatio();
            $newRequiredCollateral = $newRemaining > 0 ? ($newRemaining / $ratio) : 0.0;

            LoanCollateral::query()
                ->where('loan_id', $loan->id)
                ->update(['frozen_amount' => $newRequiredCollateral]);

            // Add the repayment amount back to cooperative funds
            $this->replenishFundsFromRepayment($paymentAmount);

            // Issue receipt for the repayment
            ReceiptService::issueReceipt($repayment);

            return $repayment;
        });
    }

    /**
     * Applies an offset from member contributions against a defaulted loan.
     * Non-obvious: offset reduces both outstanding balance AND member net contributions, so cooperative pool stays consistent.
     *
     * @throws LoanRuleViolationException
     */
    public function offsetOnDefault(Loan $loan): void
    {
        DB::transaction(function () use ($loan) {
            $loan = Loan::query()->lockForUpdate()->findOrFail($loan->id);
            $member = $loan->member()->lockForUpdate()->firstOrFail();

            $principal = $this->parseRequestedAmount($loan->amount ?? $loan->desired_loan_amount, $loan->other_amount_specify);
            $remaining = (float) ($loan->remaining_balance ?? $principal);

            if ($remaining <= 0) {
                return;
            }

            $netContributions = $this->netContributionTotal($member);
            $availableToOffset = max(0.0, $netContributions - $this->frozenContributionTotal($member));
            $offset = min($remaining, $availableToOffset);

            if ($offset <= 0) {
                throw new LoanRuleViolationException('No available contributions to offset against this loan.');
            }

            $loan->remaining_balance = round($remaining - $offset, 2);
            $loan->status = $loan->remaining_balance <= 0 ? 'completed' : 'defaulted';
            $loan->save();

            $member->offset_contribution_amount = round(((float) $member->offset_contribution_amount) + $offset, 2);
            $member->save();
        });
    }
}

