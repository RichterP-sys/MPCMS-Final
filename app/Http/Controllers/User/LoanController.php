<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Services\LoanFinanceService;
use App\Services\LoanRuleViolationException;

class LoanController extends Controller
{
    private const INTEREST_RATE = 5;

    /**
     * Parse loan term string to number of months
     */
    private static function parseLoanTermToMonths(string $loanTerm): int
    {
        $loanTerm = trim(strtolower($loanTerm));
        if (preg_match('/^(\d+)\s*months?$/i', $loanTerm, $m)) {
            return (int) $m[1];
        }
        if (preg_match('/^(\d+)\s*years?$/i', $loanTerm, $m)) {
            return (int) $m[1] * 12;
        }
        if (preg_match('/^1\s*year$/i', $loanTerm)) {
            return 12;
        }
        return 12; // fallback
    }

    /**
     * Show the form for creating a new loan application
     */
    public function create()
    {
        $member = auth()->guard('member')->user();
        $loanFinance = app(LoanFinanceService::class);
        $isLendingFrozen = $loanFinance->isLendingFrozen();
        return view('UserSide.loans.create', compact('member', 'isLendingFrozen'));
    }

    /**
     * Store a newly created loan application
     */
    public function store(Request $request)
    {
        $member = auth()->guard('member')->user();

        $request->validate([
            'loan_purpose' => 'required|string|max:255',
            'loan_amount' => 'required|numeric|min:100',
            'loan_term' => 'required|string|max:50',
            'source_of_fund' => 'nullable|string|max:1000',
            'repayment_method' => 'required|in:Cash,Bank,E-Cash',
            'bank_name' => 'required_if:repayment_method,Bank|nullable|string|max:100',
            'bank_account_number' => 'required_if:repayment_method,Bank|nullable|string|max:50',
            'bank_account_name' => 'required_if:repayment_method,Bank|nullable|string|max:100',
            'ecash_provider' => 'required_if:repayment_method,E-Cash|nullable|string|max:50',
            'ecash_mobile_number' => 'required_if:repayment_method,E-Cash|nullable|string|max:20',
            'ecash_account_name' => 'required_if:repayment_method,E-Cash|nullable|string|max:100',
            'other_purpose_specify' => 'nullable|string|max:255',
            'application_date' => 'required|date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        // Handle other purpose specification
        $loanPurpose = $request->loan_purpose;
        if ($loanPurpose === 'Other' && $request->other_purpose_specify) {
            $loanPurpose = $request->other_purpose_specify;
        }

        // Build repayment_details for Bank or E-Cash
        $repaymentDetails = null;
        if ($request->repayment_method === 'Bank') {
            $repaymentDetails = [
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name' => $request->bank_account_name,
            ];
        } elseif ($request->repayment_method === 'E-Cash') {
            $repaymentDetails = [
                'ecash_provider' => $request->ecash_provider,
                'ecash_mobile_number' => $request->ecash_mobile_number,
                'ecash_account_name' => $request->ecash_account_name,
            ];
        }

        try {
            /** @var LoanFinanceService $loanFinance */
            $loanFinance = app(LoanFinanceService::class);
            $principal = (float) $request->loan_amount;
            $loanFinance->assertEligible($member, $principal);

            $termMonths = self::parseLoanTermToMonths($request->loan_term);
            $interestAmount = round($principal * (self::INTEREST_RATE / 100), 2);
            $totalAmount = round($principal + $interestAmount, 2);
            $monthlyRepayment = $termMonths > 0 ? round($totalAmount / $termMonths, 2) : $totalAmount;

            $loan = Loan::create([
                'member_id' => $member->id,
                'amount' => $principal,
                'remaining_balance' => $totalAmount,
                'interest_rate' => self::INTEREST_RATE,
                'interest_amount' => $interestAmount,
                'total_amount' => $totalAmount,
                'monthly_repayment' => $monthlyRepayment,
                'term_months' => $termMonths,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'middle_name' => $member->middle_name ?? '',
                'email' => $member->email,
                'cell_phone' => $member->phone ?? $member->cell_phone ?? '',
                'loan_purpose' => $loanPurpose,
                'loan_term' => $request->loan_term,
                'desired_loan_amount' => $request->loan_amount,
                'source_of_fund' => $request->source_of_fund,
                'repayment_method' => $request->repayment_method,
                'repayment_details' => $repaymentDetails,
                'status' => 'pending',
                'application_date' => $request->application_date,
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('loan_applications/' . $loan->id, $filename, 'public');
                    
                    // Store in database if you have a loan_attachments table
                    // LoanAttachment::create([
                    //     'loan_id' => $loan->id,
                    //     'filename' => $filename,
                    //     'path' => $path,
                    //     'original_name' => $file->getClientOriginalName(),
                    //     'mime_type' => $file->getMimeType(),
                    //     'size' => $file->getSize(),
                    // ]);
                }
            }

            // Log activity
            ActivityLogService::log(
                'loan_application',
                'Loan application #' . $loan->id . ' submitted for ₱' . number_format($principal, 2) . ' (total ₱' . number_format($totalAmount, 2) . ' with 5% interest)',
                request(),
                $member->id
            );

            $attachmentCount = is_array($request->file('attachments')) ? count($request->file('attachments')) : 0;
            $successMsg = $attachmentCount > 0
                ? "Loan application submitted successfully with {$attachmentCount} document(s). Your application is now pending review."
                : 'Loan application submitted successfully. Your application is now pending review.';

            return redirect()->route('user.loans.show', $loan)
                ->with('success', $successMsg);
        } catch (LoanRuleViolationException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to submit loan application. Please try again.');
        }
    }

    /**
     * Display the specified loan application
     */
    public function show(Loan $loan)
    {
        $member = auth()->guard('member')->user();

        // Ensure the member can only view their own loans
        if ($loan->member_id !== $member->id) {
            abort(403, 'Unauthorized');
        }

        return view('UserSide.loans.show', compact('loan', 'member'));
    }

    /**
     * Display a listing of user's loans
     */
    public function index()
    {
        $member = auth()->guard('member')->user();
        $loans = Loan::where('member_id', $member->id)
            ->latest()
            ->paginate(10);

        return view('UserSide.loans.index', compact('loans', 'member'));
    }
}
