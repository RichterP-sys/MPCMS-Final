@extends('AdminSide.layouts.admin')

@section('title', 'Create Loan Application')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Create Loan Application</h2>
                <p class="text-gray-600 mt-1">Register a new loan application for a member</p>
            </div>
            <a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Back to Loans
            </a>
        </div>
    </div>

    <!-- Lending Freeze Alert -->
    @if($isLendingFrozen)
    <div class="relative overflow-hidden rounded-2xl p-6 border-2 border-red-300 bg-gradient-to-r from-red-50 to-rose-50">
        <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl" style="background: rgba(239, 68, 68, 0.15);"></div>
        <div class="relative flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/25 flex-shrink-0">
                <i class="fas fa-ban text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-red-800">Lending Temporarily Suspended</h3>
                <p class="text-sm text-red-700 mt-1">
                    Cooperative funds are currently at <strong>₱{{ number_format($totalFunds, 2) }}</strong>, which is at or below the minimum threshold of <strong>₱{{ number_format($freezeThreshold, 2) }}</strong>.
                </p>
                <p class="text-sm text-red-600 mt-2">
                    New loan applications cannot be submitted until funds are replenished above the threshold through member repayments or additional deposits.
                </p>
                <a href="{{ route('admin.amount-held.index') }}" class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition">
                    <i class="fas fa-wallet"></i> View Cooperative Funds
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Fund Status Info -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-slate-200/60">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-wallet text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Cooperative Funds: ₱{{ number_format($totalFunds, 2) }}</p>
                    <p class="text-xs text-slate-500">Max loanable: ₱{{ number_format($maxLoanable, 2) }} · Threshold: ₱{{ number_format($freezeThreshold, 2) }}</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Lending Active
            </span>
        </div>
    </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <h3 class="font-semibold mb-2">Please correct the following errors:</h3>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.loans.store') }}" class="space-y-6" id="loanForm">
                @csrf

                <!-- Member Selection -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">Select Member</h2>
                    
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Member <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="member_id" 
                            name="member_id" 
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('member_id') border-red-500 @enderror"
                            onchange="loadMemberData()"
                        >
                            <option value="">-- Select a Member --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" data-first-name="{{ $member->first_name }}" data-last-name="{{ $member->last_name }}" data-email="{{ $member->email }}" data-phone="{{ $member->phone_number }}" data-nature-of-work="{{ $member->nature_of_work }}" data-employer-business-name="{{ $member->employer_business_name }}" data-date-of-employment="{{ $member->date_of_employment }}" data-tin-number="{{ $member->tin_number }}" data-sss-gsis-no="{{ $member->sss_gsis_no }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->first_name }} {{ $member->last_name }} ({{ $member->member_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Member Info Display -->
                    <div id="memberInfo" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="memberFirstName" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed mt-1">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="memberLastName" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed mt-1">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="memberEmail" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed mt-1">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" id="memberPhone" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed mt-1">
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields for Member Data -->
                <input type="hidden" id="first_name" name="first_name" value="{{ old('first_name') }}">
                <input type="hidden" id="last_name" name="last_name" value="{{ old('last_name') }}">
                <input type="hidden" id="email" name="email" value="{{ old('email') }}">
                <input type="hidden" id="cell_phone" name="cell_phone" value="{{ old('cell_phone') }}">

                <!-- Hidden Fields for Member Data -->
                <input type="hidden" id="first_name" name="first_name" value="{{ old('first_name') }}">
                <input type="hidden" id="last_name" name="last_name" value="{{ old('last_name') }}">
                <input type="hidden" id="email" name="email" value="{{ old('email') }}">
                <input type="hidden" id="cell_phone" name="cell_phone" value="{{ old('cell_phone') }}">
                <input type="hidden" id="nature_of_work" name="nature_of_work" value="{{ old('nature_of_work') }}">
                <input type="hidden" id="employer_business_name" name="employer_business_name" value="{{ old('employer_business_name') }}">
                <input type="hidden" id="date_of_employment" name="date_of_employment" value="{{ old('date_of_employment') }}">
                <input type="hidden" id="tin_number" name="tin_number" value="{{ old('tin_number') }}">
                <input type="hidden" id="sss_gsis_no" name="sss_gsis_no" value="{{ old('sss_gsis_no') }}">

                <!-- Required Member Information -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">Member Information (Auto-filled)</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name_display" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" id="first_name_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="last_name_display" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="last_name_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="email_display" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="cell_phone_display" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="cell_phone_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="nature_of_work_display" class="block text-sm font-medium text-gray-700 mb-1">Nature of Work</label>
                            <input type="text" id="nature_of_work_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="employer_business_name_display" class="block text-sm font-medium text-gray-700 mb-1">Employer/Business Name</label>
                            <input type="text" id="employer_business_name_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="date_of_employment_display" class="block text-sm font-medium text-gray-700 mb-1">Date of Employment</label>
                            <input type="date" id="date_of_employment_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="tin_number_display" class="block text-sm font-medium text-gray-700 mb-1">TIN Number</label>
                            <input type="text" id="tin_number_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="sss_gsis_no_display" class="block text-sm font-medium text-gray-700 mb-1">SSS/GSIS Number</label>
                            <input type="text" id="sss_gsis_no_display" readonly class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">Loan Details</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="desired_loan_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Desired Loan Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input 
                                    type="number" 
                                    id="desired_loan_amount" 
                                    name="desired_loan_amount" 
                                    value="{{ old('desired_loan_amount') }}"
                                    placeholder="0.00" 
                                    step="0.01" 
                                    min="100"
                                    required
                                    class="block w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('desired_loan_amount') border-red-500 @enderror"
                                >
                            </div>
                            @error('desired_loan_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loan_term" class="block text-sm font-medium text-gray-700 mb-1">
                                Loan Term <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="loan_term" 
                                name="loan_term" 
                                required
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_term') border-red-500 @enderror"
                            >
                                <option value="">-- Select Loan Term --</option>
                                <option value="3 months" {{ old('loan_term') == '3 months' ? 'selected' : '' }}>3 months</option>
                                <option value="6 months" {{ old('loan_term') == '6 months' ? 'selected' : '' }}>6 months</option>
                                <option value="1 year" {{ old('loan_term') == '1 year' ? 'selected' : '' }}>1 year</option>
                                <option value="18 months" {{ old('loan_term') == '18 months' ? 'selected' : '' }}>18 months</option>
                                <option value="2 years" {{ old('loan_term') == '2 years' ? 'selected' : '' }}>2 years</option>
                                <option value="3 years" {{ old('loan_term') == '3 years' ? 'selected' : '' }}>3 years</option>
                                <option value="5 years" {{ old('loan_term') == '5 years' ? 'selected' : '' }}>5 years</option>
                            </select>
                            @error('loan_term')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loan_purpose" class="block text-sm font-medium text-gray-700 mb-1">
                                Loan Purpose <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="loan_purpose" 
                                name="loan_purpose" 
                                required
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_purpose') border-red-500 @enderror"
                            >
                                <option value="">-- Select Loan Purpose --</option>
                                <option value="Business Expansion" {{ old('loan_purpose') == 'Business Expansion' ? 'selected' : '' }}>Business Expansion</option>
                                <option value="Education" {{ old('loan_purpose') == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Home Improvement" {{ old('loan_purpose') == 'Home Improvement' ? 'selected' : '' }}>Home Improvement</option>
                                <option value="Medical Emergency" {{ old('loan_purpose') == 'Medical Emergency' ? 'selected' : '' }}>Medical Emergency</option>
                                <option value="Agricultural" {{ old('loan_purpose') == 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="Vehicle Purchase" {{ old('loan_purpose') == 'Vehicle Purchase' ? 'selected' : '' }}>Vehicle Purchase</option>
                                <option value="Debt Consolidation" {{ old('loan_purpose') == 'Debt Consolidation' ? 'selected' : '' }}>Debt Consolidation</option>
                                <option value="Other" {{ old('loan_purpose') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('loan_purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="other_purpose_section" style="display: none;">
                            <label for="other_purpose_specify" class="block text-sm font-medium text-gray-700 mb-1">
                                Please specify other purpose
                            </label>
                            <input 
                                type="text" 
                                id="other_purpose_specify" 
                                name="other_purpose_specify"
                                value="{{ old('other_purpose_specify') }}"
                                placeholder="Enter loan purpose"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="source_of_fund" class="block text-sm font-medium text-gray-700 mb-1">
                            Source of Fund <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="source_of_fund" 
                            name="source_of_fund"
                            rows="3"
                            required
                            placeholder="Explain the source of funds for repayment"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('source_of_fund') border-red-500 @enderror"
                        >{{ old('source_of_fund') }}</textarea>
                        @error('source_of_fund')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="application_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Application Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="application_date" 
                            name="application_date" 
                            value="{{ old('application_date', date('Y-m-d')) }}"
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('application_date') border-red-500 @enderror"
                        >
                        @error('application_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="inline-flex items-center px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $isLendingFrozen ? 'bg-slate-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} transition-colors" {{ $isLendingFrozen ? 'disabled' : '' }}>
                        @if($isLendingFrozen)
                            <i class="fas fa-ban mr-2"></i> Lending Suspended
                        @else
                            <i class="fas fa-plus mr-2"></i> Create Loan Application
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadMemberData() {
    const memberSelect = document.getElementById('member_id');
    const selectedOption = memberSelect.options[memberSelect.selectedIndex];
    
    if (selectedOption.value) {
        const firstName = selectedOption.getAttribute('data-first-name');
        const lastName = selectedOption.getAttribute('data-last-name');
        const email = selectedOption.getAttribute('data-email');
        const phone = selectedOption.getAttribute('data-phone');
        const natureOfWork = selectedOption.getAttribute('data-nature-of-work');
        const employerBusinessName = selectedOption.getAttribute('data-employer-business-name');
        const dateOfEmployment = selectedOption.getAttribute('data-date-of-employment');
        const tinNumber = selectedOption.getAttribute('data-tin-number');
        const sssGsisNo = selectedOption.getAttribute('data-sss-gsis-no');
        
        // Update display fields
        document.getElementById('first_name_display').value = firstName || '';
        document.getElementById('last_name_display').value = lastName || '';
        document.getElementById('email_display').value = email || '';
        document.getElementById('cell_phone_display').value = phone || '';
        document.getElementById('nature_of_work_display').value = natureOfWork || '';
        document.getElementById('employer_business_name_display').value = employerBusinessName || '';
        document.getElementById('date_of_employment_display').value = dateOfEmployment || '';
        document.getElementById('tin_number_display').value = tinNumber || '';
        document.getElementById('sss_gsis_no_display').value = sssGsisNo || '';
        
        // Update hidden fields
        document.getElementById('first_name').value = firstName || '';
        document.getElementById('last_name').value = lastName || '';
        document.getElementById('email').value = email || '';
        document.getElementById('cell_phone').value = phone || '';
        document.getElementById('nature_of_work').value = natureOfWork || '';
        document.getElementById('employer_business_name').value = employerBusinessName || '';
        document.getElementById('date_of_employment').value = dateOfEmployment || '';
        document.getElementById('tin_number').value = tinNumber || '';
        document.getElementById('sss_gsis_no').value = sssGsisNo || '';
    } else {
        // Clear all fields
        document.getElementById('first_name_display').value = '';
        document.getElementById('last_name_display').value = '';
        document.getElementById('email_display').value = '';
        document.getElementById('cell_phone_display').value = '';
        document.getElementById('nature_of_work_display').value = '';
        document.getElementById('employer_business_name_display').value = '';
        document.getElementById('date_of_employment_display').value = '';
        document.getElementById('tin_number_display').value = '';
        document.getElementById('sss_gsis_no_display').value = '';
        
        document.getElementById('first_name').value = '';
        document.getElementById('last_name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('cell_phone').value = '';
        document.getElementById('nature_of_work').value = '';
        document.getElementById('employer_business_name').value = '';
        document.getElementById('date_of_employment').value = '';
        document.getElementById('tin_number').value = '';
        document.getElementById('sss_gsis_no').value = '';
    }
}

document.getElementById('loan_purpose').addEventListener('change', function() {
    const otherPurposeSection = document.getElementById('other_purpose_section');
    if (this.value === 'Other') {
        otherPurposeSection.style.display = 'block';
    } else {
        otherPurposeSection.style.display = 'none';
    }
});

// Show other purpose section on page load if "Other" is selected
window.addEventListener('DOMContentLoaded', function() {
    const loanPurpose = document.getElementById('loan_purpose');
    if (loanPurpose.value === 'Other') {
        document.getElementById('other_purpose_section').style.display = 'block';
    }

    // Load member data if a member is already selected
    const memberSelect = document.getElementById('member_id');
    if (memberSelect.value) {
        loadMemberData();
    }
});
</script>
@endsection
