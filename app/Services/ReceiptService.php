<?php

namespace App\Services;

use App\Models\LoanRepayment;
use App\Models\Receipt;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate a unique receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $timestamp = date('Ymd');
        $random = strtoupper(Str::random(6));
        
        // Check if receipt number already exists
        $receiptNumber = $prefix . $timestamp . $random;
        while (LoanRepayment::where('receipt_number', $receiptNumber)->exists() || 
               Receipt::where('receipt_number', $receiptNumber)->exists()) {
            $random = strtoupper(Str::random(6));
            $receiptNumber = $prefix . $timestamp . $random;
        }
        
        return $receiptNumber;
    }

    /**
     * Issue a receipt for a repayment
     */
    public static function issueReceipt(LoanRepayment $repayment): LoanRepayment
    {
        if (!$repayment->receipt_number) {
            $receiptNumber = self::generateReceiptNumber();
            
            // Update LoanRepayment with receipt info
            $repayment->update([
                'receipt_number' => $receiptNumber,
                'receipt_issued_at' => now(),
            ]);
            
            // Create Receipt record in admin receipts table
            Receipt::create([
                'record_id' => $repayment->id,
                'record_type' => 'repayment',
                'member_id' => $repayment->loan->member_id,
                'amount' => $repayment->amount,
                'receipt_number' => $receiptNumber,
                'receipt_status' => 'issued',
                'receipt_issued_at' => now(),
            ]);
        }
        
        return $repayment;
    }

    /**
     * Get receipt details with member and loan information
     */
    public static function getReceiptDetails(LoanRepayment $repayment)
    {
        return [
            'receipt_number' => $repayment->receipt_number,
            'receipt_issued_at' => $repayment->receipt_issued_at,
            'member' => $repayment->loan->member,
            'loan' => $repayment->loan,
            'repayment' => $repayment,
            'amount' => $repayment->amount,
            'payment_date' => $repayment->payment_date,
            'payment_method' => $repayment->payment_method,
            'reference_number' => $repayment->reference_number,
        ];
    }
}
