<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Contribution;
use App\Models\Receipt;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display all receipts
     */
    public function index(Request $request)
    {
        $query = Receipt::query()
            ->with(['member'])
            ->latest('receipt_issued_at');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('member_id', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('receipt_status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('record_type', $request->type);
        }

        $receipts = $query->paginate(15);
        $totalReceipts = Receipt::count();
        $pendingReceipts = Receipt::where('receipt_status', 'pending')->count();
        $totalAmount = Receipt::sum('amount');

        return view('AdminSide.receipts.index', [
            'receipts' => $receipts,
            'totalReceipts' => $totalReceipts,
            'pendingReceipts' => $pendingReceipts,
            'totalAmount' => $totalAmount,
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Show single receipt
     */
    public function show(Receipt $receipt)
    {
        $receipt->load(['member']);
        return view('AdminSide.receipts.show', compact('receipt'));
    }

    /**
     * Print receipt
     */
    public function print(Receipt $receipt)
    {
        $receipt->load(['member']);
        return view('AdminSide.receipts.print', compact('receipt'));
    }

    /**
     * Issue receipt for approved record
     */
    public function issue(Request $request)
    {
        $recordId = $request->input('record_id');
        $recordType = $request->input('record_type', 'loan');
        $amount = $request->input('amount');
        $memberId = $request->input('member_id');

        // Check if receipt already exists
        $existingReceipt = Receipt::where('record_id', $recordId)
            ->where('record_type', $recordType)
            ->first();

        if ($existingReceipt) {
            return back()->with('error', 'Receipt already issued for this record.');
        }

        // Create receipt
        $receipt = Receipt::create([
            'record_id' => $recordId,
            'record_type' => $recordType,
            'member_id' => $memberId,
            'amount' => $amount,
            'receipt_number' => ReceiptService::generateReceiptNumber(),
            'receipt_status' => 'pending',
            'receipt_issued_at' => now(),
        ]);

        return back()->with('success', 'Receipt issued successfully. Receipt #: ' . $receipt->receipt_number);
    }

    /**
     * Confirm receipt as issued
     */
    public function confirm(Receipt $receipt)
    {
        $receipt->update(['receipt_status' => 'issued']);
        return back()->with('success', 'Receipt marked as issued.');
    }
}
