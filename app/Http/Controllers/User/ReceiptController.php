<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LoanRepayment;
use Illuminate\Auth\Access\AuthorizationException;

class ReceiptController extends Controller
{
    /**
     * Display receipts listing for the current member
     */
    public function index()
    {
        $member = auth()->guard('member')->user();

        // Get all repayments for this member's loans
        $repayments = LoanRepayment::whereHas('loan', function ($query) use ($member) {
            $query->where('member_id', $member->id);
        })
            ->with('loan')
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalRepaid = $repayments->sum('amount');
        $receiptsIssued = $repayments->filter(fn ($r) => $r->receipt_number)->count();

        return view('UserSide.receipts.index', [
            'repayments' => $repayments,
            'totalRepaid' => $totalRepaid,
            'receiptsIssued' => $receiptsIssued,
        ]);
    }

    /**
     * Display a specific receipt
     */
    public function show(LoanRepayment $repayment)
    {
        $member = auth()->guard('member')->user();
        $loan = $repayment->loan;

        // Verify the repayment belongs to the current member
        if ($loan->member_id !== $member->id) {
            throw new AuthorizationException('You are not authorized to view this receipt.');
        }

        // Ensure receipt is issued
        if (!$repayment->receipt_number) {
            return back()->with('error', 'This repayment does not have a receipt yet.');
        }

        return view('UserSide.receipts.show', [
            'member' => $member,
            'loan' => $loan,
            'repayment' => $repayment,
        ]);
    }

    /**
     * Print a receipt
     */
    public function print(LoanRepayment $repayment)
    {
        $member = auth()->guard('member')->user();
        $loan = $repayment->loan;

        // Verify the repayment belongs to the current member
        if ($loan->member_id !== $member->id) {
            throw new AuthorizationException('You are not authorized to print this receipt.');
        }

        // Ensure receipt is issued
        if (!$repayment->receipt_number) {
            return back()->with('error', 'This repayment does not have a receipt yet.');
        }

        return view('UserSide.receipts.print', [
            'member' => $member,
            'loan' => $loan,
            'repayment' => $repayment,
        ]);
    }
}
