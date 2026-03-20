<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanFinanceService;
use App\Services\LoanRuleViolationException;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class LoanRepaymentController extends Controller
{
    public function store(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
        ]);

        try {
            /** @var LoanFinanceService $loanFinance */
            $loanFinance = app(LoanFinanceService::class);
            $loanFinance->recordRepayment($loan, (float) $validated['amount'], $validated);

            // Refresh loan to get updated balance
            $loan->refresh();
            
            // Notify member of repayment received
            NotificationService::repaymentReceived($loan, (float) $validated['amount']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed successfully.',
                ]);
            }

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('success', 'Repayment recorded successfully.');
        } catch (LoanRuleViolationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('error', $e->getMessage());
        }
    }
}
