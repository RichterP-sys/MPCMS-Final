<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Notifications\LoanStatusUpdated;
use App\Services\LoanFinanceService;
use App\Services\LoanRuleViolationException;
use App\Services\NotificationService;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('member')->latest()->paginate(10);
        return view('AdminSide.loans.index', compact('loans'));
    }

    public function create()
    {
        $members = \App\Models\Member::all();
        
        // Get cooperative fund status for lending freeze check
        $loanFinance = app(LoanFinanceService::class);
        $totalFunds = $loanFinance->getTotalCooperativeFunds();
        $isLendingFrozen = $loanFinance->isLendingFrozen();
        $freezeThreshold = LoanFinanceService::LOAN_FREEZE_THRESHOLD;
        $maxLoanable = max(0, $totalFunds - $freezeThreshold);
        
        return view('AdminSide.loans.create', compact(
            'members', 'totalFunds', 'isLendingFrozen', 'freezeThreshold', 'maxLoanable'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cell_phone' => 'required|string|max:20',
            'tin_number' => 'required|string|max:50',
            'sss_gsis_no' => 'required|string|max:50',
            'nature_of_work' => 'required|string|max:255',
            'employer_business_name' => 'required|string|max:255',
            'date_of_employment' => 'required|date',
            'source_of_fund' => 'required|string|max:255',
            'loan_purpose' => 'required|string|max:255',
            'loan_term' => 'required|string|max:50',
            'desired_loan_amount' => 'required|string|max:255',
            'other_amount_specify' => 'nullable|numeric|min:0',
            'application_date' => 'required|date',
        ]);

        // Handle other amount specification
        $loanAmount = $request->desired_loan_amount;
        if ($loanAmount === 'Others' && $request->other_amount_specify) {
            $loanAmount = $request->other_amount_specify;
        }

        // Handle other purpose specification
        $loanPurpose = $request->loan_purpose;
        if ($loanPurpose === 'Other' && $request->other_purpose_specify) {
            $loanPurpose = $request->other_purpose_specify;
        }

        // Enforce eligibility rules without changing the existing persistence shape.
        try {
            /** @var LoanFinanceService $loanFinance */
            $loanFinance = app(LoanFinanceService::class);
            $member = Member::findOrFail($request->member_id);
            $requestedAmount = $loanFinance->parseRequestedAmount($request->desired_loan_amount, $request->other_amount_specify);
            $loanFinance->assertEligible($member, $requestedAmount);
        } catch (LoanRuleViolationException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        Loan::create([
            'member_id' => $request->member_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'maiden_middle_name' => $request->maiden_middle_name,
            'name_extension' => $request->name_extension,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'mothers_maiden_name' => $request->mothers_maiden_name,
            'nationality' => $request->nationality,
            'sex' => $request->sex,
            'marital_status' => $request->marital_status,
            'citizenship' => $request->citizenship,
            'email' => $request->email,
            'cell_phone' => $request->cell_phone,
            'home_telephone' => $request->home_telephone,
            'unit_room_no' => $request->unit_room_no,
            'floor' => $request->floor,
            'building_name' => $request->building_name,
            'lot_no' => $request->lot_no,
            'block_no' => $request->block_no,
            'phase_no' => $request->phase_no,
            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'subdivision' => $request->subdivision,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province_state_country' => $request->province_state_country,
            'zip_code' => $request->zip_code,
            'tin_number' => $request->tin_number,
            'sss_gsis_no' => $request->sss_gsis_no,
            'business_telephone' => $request->business_telephone,
            'nature_of_work' => $request->nature_of_work,
            'employer_business_name' => $request->employer_business_name,
            'employee_id' => $request->employee_id,
            'date_of_employment' => $request->date_of_employment,
            'source_of_fund' => $request->source_of_fund,
            'emp_unit_room_no' => $request->emp_unit_room_no,
            'emp_floor' => $request->emp_floor,
            'emp_building_name' => $request->emp_building_name,
            'emp_lot_no' => $request->emp_lot_no,
            'emp_block_no' => $request->emp_block_no,
            'emp_phase_no' => $request->emp_phase_no,
            'emp_house_no' => $request->emp_house_no,
            'emp_street_name' => $request->emp_street_name,
            'emp_subdivision' => $request->emp_subdivision,
            'emp_barangay' => $request->emp_barangay,
            'emp_municipality_city' => $request->emp_municipality_city,
            'emp_province_state_country' => $request->emp_province_state_country,
            'emp_zip_code' => $request->emp_zip_code,
            'loan_purpose' => $loanPurpose,
            'loan_term' => $request->loan_term,
            'desired_loan_amount' => $loanAmount,
            'status' => 'pending', // All loans are created as pending
            'application_date' => $request->application_date,
        ]);

        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan application submitted successfully and is now pending review.');
    }

    public function show(Loan $loan)
    {
        return view('AdminSide.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $members = Member::where('status', 'active')->get();
        return view('AdminSide.loans.edit', compact('loan', 'members'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'application_date' => 'required|date'
        ]);

        $loan->update($validated);

        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('admin.finance.index', ['tab' => 'loans'])
            ->with('success', 'Loan deleted successfully.');
    }

    public function approve(Loan $loan)
    {
        try {
            /** @var LoanFinanceService $loanFinance */
            $loanFinance = app(LoanFinanceService::class);
            $loanFinance->approveLoan($loan);
        } catch (LoanRuleViolationException $e) {
            return redirect()->route('admin.finance.index', ['tab' => 'loans'])->with('error', $e->getMessage());
        }

        // Log to member activity and notify member
        ActivityLogService::log(
            'loan',
            'Loan #' . $loan->id . ' approved by admin',
            request(),
            $loan->member_id
        );
        
        // Send notification to member
        NotificationService::loanApproved($loan);

        return redirect()->route('admin.finance.index', ['tab' => 'loans'])
            ->with('success', 'Loan approved successfully.');
    }

    public function reject(Loan $loan)
    {
        $loan->update(['status' => 'rejected']);

        // Log to member activity and notify member
        ActivityLogService::log(
            'loan',
            'Loan #' . $loan->id . ' rejected by admin',
            request(),
            $loan->member_id
        );
        
        // Send notification to member
        NotificationService::loanRejected($loan);

        return redirect()->route('admin.finance.index', ['tab' => 'loans'])
            ->with('success', 'Loan rejected successfully.');
    }

    public function getMemberData($memberId)
    {
        try {
            $member = Member::findOrFail($memberId);
            
            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'last_name' => $member->last_name ?? '',
                    'first_name' => $member->first_name ?? '',
                    'email' => $member->email ?? '',
                    'phone' => $member->phone ?? '',
                    'name_extension' => $member->name_extension ?? '',
                    'middle_name' => $member->middle_name ?? '',
                    'maiden_middle_name' => $member->maiden_middle_name ?? '',
                    'date_of_birth' => $member->date_of_birth ?? '',
                    'place_of_birth' => $member->place_of_birth ?? '',
                    'mothers_maiden_name' => $member->mothers_maiden_name ?? '',
                    'nationality' => $member->nationality ?? 'Filipino',
                    'sex' => $member->sex ?? '',
                    'marital_status' => $member->marital_status ?? '',
                    'citizenship' => $member->citizenship ?? 'Filipino',
                    'cell_phone' => $member->cell_phone ?? '',
                    'home_telephone' => $member->home_telephone ?? '',
                    'unit_room_no' => $member->unit_room_no ?? '',
                    'floor' => $member->floor ?? '',
                    'building_name' => $member->building_name ?? '',
                    'lot_no' => $member->lot_no ?? '',
                    'block_no' => $member->block_no ?? '',
                    'phase_no' => $member->phase_no ?? '',
                    'house_no' => $member->house_no ?? '',
                    'street_name' => $member->street_name ?? '',
                    'subdivision' => $member->subdivision ?? '',
                    'barangay' => $member->barangay ?? '',
                    'municipality_city' => $member->municipality_city ?? '',
                    'province_state_country' => $member->province_state_country ?? '',
                    'zip_code' => $member->zip_code ?? '',
                    'no_middle_name' => $member->no_middle_name ?? false,
                    
                    // Employment Information
                    'tin_number' => $member->tin_number ?? '',
                    'sss_gsis_no' => $member->sss_gsis_no ?? '',
                    'business_telephone' => $member->business_telephone ?? '',
                    'nature_of_work' => $member->nature_of_work ?? '',
                    'employer_business_name' => $member->employer_business_name ?? '',
                    'employee_id' => $member->employee_id ?? '',
                    'date_of_employment' => $member->date_of_employment ?? '',
                    'source_of_fund' => $member->source_of_fund ?? '',
                    
                    // Employer/Business Address
                    'emp_unit_room_no' => $member->emp_unit_room_no ?? '',
                    'emp_floor' => $member->emp_floor ?? '',
                    'emp_building_name' => $member->emp_building_name ?? '',
                    'emp_lot_no' => $member->emp_lot_no ?? '',
                    'emp_block_no' => $member->emp_block_no ?? '',
                    'emp_phase_no' => $member->emp_phase_no ?? '',
                    'emp_house_no' => $member->emp_house_no ?? '',
                    'emp_street_name' => $member->emp_street_name ?? '',
                    'emp_subdivision' => $member->emp_subdivision ?? '',
                    'emp_barangay' => $member->emp_barangay ?? '',
                    'emp_municipality_city' => $member->emp_municipality_city ?? '',
                    'emp_province_state_country' => $member->emp_province_state_country ?? '',
                    'emp_zip_code' => $member->emp_zip_code ?? ''
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found or error occurred'
            ], 404);
        }
    }

}