<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\CooperativeFund;
use App\Services\LoanFinanceService;
use Illuminate\Http\Request;

class AmountHeldController extends Controller
{
    /**
     * Display the amount held overview.
     */
    public function index()
    {
        // Get cooperative funds
        $cashOnHand = CooperativeFund::active()->cash()->first();
        $bankAccounts = CooperativeFund::active()->bank()->get();
        
        // Calculate totals
        $totalCash = $cashOnHand ? $cashOnHand->amount : 0;
        $totalBankBalance = $bankAccounts->sum('amount');
        $totalFunds = $totalCash + $totalBankBalance;
        
        // Get contribution and loan totals for reference
        $totalContributions = Contribution::where('status', 'approved')->sum('amount');
        $totalLoans = Loan::where('status', 'approved')->sum('amount');
        
        // Calculate total repayments from loan_repayments table
        $approvedLoanIds = Loan::where('status', 'approved')->pluck('id');
        $totalRepayments = LoanRepayment::whereIn('loan_id', $approvedLoanIds)->sum('amount');
        $outstandingLoans = $totalLoans - $totalRepayments;

        // Lending status
        $isLendingFrozen = $totalFunds <= LoanFinanceService::LOAN_FREEZE_THRESHOLD;
        $freezeThreshold = LoanFinanceService::LOAN_FREEZE_THRESHOLD;

        return view('AdminSide.amount-held.index', compact(
            'cashOnHand',
            'bankAccounts',
            'totalCash',
            'totalBankBalance',
            'totalFunds',
            'totalContributions',
            'totalLoans',
            'totalRepayments',
            'outstandingLoans',
            'isLendingFrozen',
            'freezeThreshold'
        ));
    }

    /**
     * Update cash on hand
     */
    public function updateCash(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $cash = CooperativeFund::active()->cash()->first();
        
        if ($cash) {
            $cash->update([
                'amount' => $request->amount,
                'description' => $request->description,
            ]);
        } else {
            CooperativeFund::create([
                'fund_type' => 'cash',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Cash on Hand',
            ]);
        }

        return back()->with('success', 'Cash on hand updated successfully.');
    }

    /**
     * Store a new bank account
     */
    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:200',
            'amount' => 'required|numeric|min:0',
        ]);

        CooperativeFund::create([
            'fund_type' => 'bank',
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'amount' => $request->amount,
        ]);

        return back()->with('success', 'Bank account added successfully.');
    }

    /**
     * Update bank account balance
     */
    public function updateBank(Request $request, CooperativeFund $fund)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:200',
        ]);

        $fund->update([
            'amount' => $request->amount,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return back()->with('success', 'Bank balance updated successfully.');
    }

    /**
     * Delete bank account
     */
    public function destroyBank(CooperativeFund $fund)
    {
        $fund->delete();
        return back()->with('success', 'Bank account removed successfully.');
    }
}
