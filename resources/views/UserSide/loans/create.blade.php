@extends('UserSide.layouts.app')

@section('title', 'Apply for Loan')

@section('content')
<style>
    @keyframes gradient-x {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 15s ease infinite;
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 50%, #faf5ff 100%);">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full opacity-40" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(168, 85, 247, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full opacity-40" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(249, 115, 22, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 25%, #ec4899 50%, #f97316 75%, #6366f1 100%); background-size: 200% 200%;">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div>
                        <p class="text-purple-200 text-sm font-semibold mb-2" style="letter-spacing: 1px; text-transform: uppercase;">Member Portal > Loans</p>
                        <h1 class="text-3xl font-bold text-white mb-2">Apply for a New Loan</h1>
                        <p class="text-purple-100 text-sm">Submit your loan application for review. Your request will be processed by the admin team.</p>
                    </div>
                    <a href="{{ route('user.loans.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Loans
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl flex items-center shadow-sm" style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1px solid #fca5a5;">
                    <svg class="w-5 h-5 mr-3" style="color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color: #991b1b;">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl shadow-sm" style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1px solid #fca5a5;">
                    <h3 class="font-semibold mb-2" style="color: #991b1b;">Please correct the following:</h3>
                    <ul class="list-disc pl-5 space-y-1" style="color: #991b1b;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($isLendingFrozen ?? false)
                <div class="mb-6 p-6 rounded-2xl shadow-sm" style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 2px solid #fca5a5;">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold" style="color: #991b1b;">Loan Applications Temporarily Suspended</h3>
                            <p class="mt-1 text-sm" style="color: #b91c1c;">Cooperative funds are currently below the minimum threshold. New loan applications cannot be submitted until funds are replenished. Please try again later.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Help Box -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-5 mb-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM5 9a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-900 text-sm mb-2">Application Process</h3>
                            <p class="text-sm text-blue-800">
                                <strong>1. Fill the form:</strong> Complete all required fields with accurate information <br>
                                <strong>2. Submit:</strong> Your application will be forwarded to the admin team for review <br>
                                <strong>3. Review:</strong> You'll see the status update on your dashboard <br>
                                <strong>4. Approval:</strong> Once approved, funds will be credited to your account
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-slate-200/60">
                    <div class="p-6 sm:p-8">
                        <form id="loan-application-form" method="POST" action="{{ route('user.loans.store') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Loan Details -->
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-gray-900 border-b pb-3 flex items-center gap-2">
                                    <svg class="w-6 h-6" style="color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Loan Details
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="loan_amount" class="block text-sm font-semibold text-gray-700 mb-2">Loan Amount (₱) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3 text-gray-600">₱</span>
                                            <input type="number" id="loan_amount" name="loan_amount" value="{{ old('loan_amount') }}" placeholder="0.00" step="0.01" min="100" required
                                                class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('loan_amount') border-red-500 @enderror">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">How much money do you need?</p>
                                        @error('loan_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="loan_term" class="block text-sm font-semibold text-gray-700 mb-2">Repayment Period <span class="text-red-500">*</span></label>
                                        <select id="loan_term" name="loan_term" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('loan_term') border-red-500 @enderror">
                                            <option value="">-- Select how long to repay --</option>
                                            <option value="3 months" {{ old('loan_term') == '3 months' ? 'selected' : '' }}>3 months (Fastest)</option>
                                            <option value="6 months" {{ old('loan_term') == '6 months' ? 'selected' : '' }}>6 months</option>
                                            <option value="1 year" {{ old('loan_term') == '1 year' ? 'selected' : '' }}>1 year</option>
                                            <option value="18 months" {{ old('loan_term') == '18 months' ? 'selected' : '' }}>18 months</option>
                                            <option value="2 years" {{ old('loan_term') == '2 years' ? 'selected' : '' }}>2 years</option>
                                            <option value="3 years" {{ old('loan_term') == '3 years' ? 'selected' : '' }}>3 years</option>
                                            <option value="5 years" {{ old('loan_term') == '5 years' ? 'selected' : '' }}>5 years (Extended)</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Longer terms = smaller monthly payments</p>
                                        @error('loan_term')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="loan_purpose" class="block text-sm font-semibold text-gray-700 mb-2">What is this loan for? <span class="text-red-500">*</span></label>
                                        <select id="loan_purpose" name="loan_purpose" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('loan_purpose') border-red-500 @enderror">
                                            <option value="">-- Select loan purpose --</option>
                                            <option value="Business Expansion" {{ old('loan_purpose') == 'Business Expansion' ? 'selected' : '' }}>💼 Business Expansion</option>
                                            <option value="Education" {{ old('loan_purpose') == 'Education' ? 'selected' : '' }}>🎓 Education</option>
                                            <option value="Home Improvement" {{ old('loan_purpose') == 'Home Improvement' ? 'selected' : '' }}>🏠 Home Improvement</option>
                                            <option value="Medical Emergency" {{ old('loan_purpose') == 'Medical Emergency' ? 'selected' : '' }}>⚕️ Medical Emergency</option>
                                            <option value="Agricultural" {{ old('loan_purpose') == 'Agricultural' ? 'selected' : '' }}>🌾 Agricultural</option>
                                            <option value="Vehicle Purchase" {{ old('loan_purpose') == 'Vehicle Purchase' ? 'selected' : '' }}>🚗 Vehicle Purchase</option>
                                            <option value="Debt Consolidation" {{ old('loan_purpose') == 'Debt Consolidation' ? 'selected' : '' }}>💳 Debt Consolidation</option>
                                            <option value="Other" {{ old('loan_purpose') == 'Other' ? 'selected' : '' }}>❓ Other</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">This helps us understand your financial needs</p>
                                        @error('loan_purpose')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div id="other_purpose_section" class="sm:col-span-2" style="display: none;">
                                        <label for="other_purpose_specify" class="block text-sm font-medium text-gray-700 mb-1">Please specify</label>
                                        <input type="text" id="other_purpose_specify" name="other_purpose_specify" value="{{ old('other_purpose_specify') }}" placeholder="Enter loan purpose"
                                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="source_of_fund" class="block text-sm font-medium text-gray-700 mb-1">Source of Fund (How will you repay?)</label>
                                        <textarea id="source_of_fund" name="source_of_fund" rows="3" placeholder="Explain your source of funds for repayment"
                                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('source_of_fund') border-red-500 @enderror">{{ old('source_of_fund') }}</textarea>
                                        @error('source_of_fund')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="repayment_method" class="block text-sm font-medium text-gray-700 mb-1">Repayment Method <span class="text-red-500">*</span></label>
                                        <select id="repayment_method" name="repayment_method" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('repayment_method') border-red-500 @enderror">
                                            <option value="">-- Select Method --</option>
                                            <option value="Cash" {{ old('repayment_method') == 'Cash' ? 'selected' : '' }}>1. Cash</option>
                                            <option value="Bank" {{ old('repayment_method') == 'Bank' ? 'selected' : '' }}>2. Bank</option>
                                            <option value="E-Cash" {{ old('repayment_method') == 'E-Cash' ? 'selected' : '' }}>3. E-Cash (GCash, Maya etc.)</option>
                                        </select>
                                        @error('repayment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>


                                    <div>
                                        <label for="application_date" class="block text-sm font-medium text-gray-700 mb-1">Application Date <span class="text-red-500">*</span></label>
                                        <input type="date" id="application_date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required
                                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('application_date') border-red-500 @enderror">
                                        @error('application_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">Attachments (Optional)</label>
                                        <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG, GIF. Max 5MB each.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                                <a href="{{ route('user.loans.index') }}" class="inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                                    Cancel
                                </a>
                                <button type="button" id="btn-submit-application" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:shadow-xl hover:-translate-y-0.5" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Submit Application
                                </button>
                            </div>

                            <!-- Bank Details Modal (inside form for submission) -->
                            <div id="bank-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
                                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeBankModal()"></div>
                                <div class="fixed inset-0 flex items-center justify-center p-4" onclick="closeBankModal()">
                                    <div class="relative w-full max-w-md rounded-2xl shadow-2xl bg-white border border-slate-200 overflow-hidden" onclick="event.stopPropagation()">
                                        <div class="p-6">
                                            <h3 class="text-lg font-bold text-gray-900 mb-1">Bank Details</h3>
                                            <p class="text-sm text-gray-600 mb-4">Please provide your bank account information for repayment.</p>
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Bank Name <span class="text-red-500">*</span></label>
                                                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. BDO, BPI, Landbank"
                                                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-1">Account Number <span class="text-red-500">*</span></label>
                                                    <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="Enter account number"
                                                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label for="bank_account_name" class="block text-sm font-medium text-gray-700 mb-1">Account Name <span class="text-red-500">*</span></label>
                                                    <input type="text" id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name') }}" placeholder="Name on the account"
                                                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <button type="button" onclick="closeBankModal()" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background: linear-gradient(135deg, #6366f1, #a855f7);">Done</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E-Cash Details Modal (inside form for submission) -->
                            <div id="ecash-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
                                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEcashModal()"></div>
                                <div class="fixed inset-0 flex items-center justify-center p-4" onclick="closeEcashModal()">
                                    <div class="relative w-full max-w-md rounded-2xl shadow-2xl bg-white border border-slate-200 overflow-hidden" onclick="event.stopPropagation()">
                                        <div class="p-6">
                                            <h3 class="text-lg font-bold text-gray-900 mb-1">E-Cash Details</h3>
                                            <p class="text-sm text-gray-600 mb-4">Please provide your e-wallet information for repayment.</p>
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="ecash_provider" class="block text-sm font-medium text-gray-700 mb-1">E-Wallet Provider <span class="text-red-500">*</span></label>
                                                    <select id="ecash_provider" name="ecash_provider" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="">-- Select --</option>
                                                        <option value="GCash" {{ old('ecash_provider') == 'GCash' ? 'selected' : '' }}>GCash</option>
                                                        <option value="Maya" {{ old('ecash_provider') == 'Maya' ? 'selected' : '' }}>Maya</option>
                                                        <option value="GrabPay" {{ old('ecash_provider') == 'GrabPay' ? 'selected' : '' }}>GrabPay</option>
                                                        <option value="Other" {{ old('ecash_provider') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="ecash_mobile_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                                    <input type="text" id="ecash_mobile_number" name="ecash_mobile_number" value="{{ old('ecash_mobile_number') }}" placeholder="09XX XXX XXXX"
                                                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label for="ecash_account_name" class="block text-sm font-medium text-gray-700 mb-1">Account Name <span class="text-red-500">*</span></label>
                                                    <input type="text" id="ecash_account_name" name="ecash_account_name" value="{{ old('ecash_account_name') }}" placeholder="Name registered on e-wallet"
                                                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <button type="button" onclick="closeEcashModal()" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background: linear-gradient(135deg, #6366f1, #a855f7);">Done</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Confirmation Modal -->
                        <div id="loan-confirm-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
                            <div class="fixed inset-0 flex items-center justify-center p-4" onclick="closeConfirmModal()">
                                <div class="relative w-full max-w-md rounded-2xl shadow-2xl overflow-hidden" style="background: linear-gradient(135deg, #ffffff, #f8fafc); border: 2px solid #fca5a5;" onclick="event.stopPropagation()">
                                    <div class="p-6">
                                        <div class="flex items-start gap-4 mb-6">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #f59e0b, #f97316);">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">Confirm Loan Application</h3>
                                                <p class="mt-1 text-sm text-gray-600">Please review the loan details below before submitting. A 5% interest is applied to all loans.</p>
                                            </div>
                                        </div>
                                        <div class="space-y-3 mb-6 p-4 rounded-xl" style="background: linear-gradient(135deg, #fef3c7, #ffedd5); border: 1px solid #fcd34d;">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">Loan Amount (Principal)</span>
                                                <span id="modal-loan-amount" class="font-semibold text-gray-900">—</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">Interest (5%)</span>
                                                <span id="modal-interest" class="font-semibold text-gray-900">—</span>
                                            </div>
                                            <div class="flex justify-between text-sm pt-2 border-t border-amber-200">
                                                <span class="font-medium text-gray-800">Total Amount to Repay</span>
                                                <span id="modal-total" class="font-bold text-gray-900">—</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">Loan Term</span>
                                                <span id="modal-term" class="font-semibold text-gray-900">—</span>
                                            </div>
                                            <div class="flex justify-between text-sm pt-2 border-t border-amber-200">
                                                <span class="font-medium text-gray-800">Monthly Repayment</span>
                                                <span id="modal-monthly" class="font-bold text-gray-900">—</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">Repayment Method</span>
                                                <span id="modal-repayment-method" class="font-semibold text-gray-900">—</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                                                Cancel
                                            </button>
                                            <button type="button" id="btn-confirm-submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                                Confirm & Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('loan_purpose').addEventListener('change', function() {
    const section = document.getElementById('other_purpose_section');
    section.style.display = this.value === 'Other' ? 'block' : 'none';
});
document.getElementById('repayment_method').addEventListener('change', function() {
    const val = this.value;
    if (val === 'Bank') {
        document.getElementById('bank-modal').classList.remove('hidden');
    } else if (val === 'E-Cash') {
        document.getElementById('ecash-modal').classList.remove('hidden');
    }
});
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('loan_purpose').value === 'Other') {
        document.getElementById('other_purpose_section').style.display = 'block';
    }
    const repaymentVal = document.getElementById('repayment_method').value;
    if (repaymentVal === 'Bank' || repaymentVal === 'E-Cash') {
        // Don't auto-open on load - user may have old() values
    }

    function parseLoanTermToMonths(loanTerm) {
        const t = String(loanTerm).trim().toLowerCase();
        const monthsMatch = t.match(/^(\d+)\s*months?$/i);
        if (monthsMatch) return parseInt(monthsMatch[1], 10);
        const yearsMatch = t.match(/^(\d+)\s*years?$/i);
        if (yearsMatch) return parseInt(yearsMatch[1], 10) * 12;
        return 12;
    }

    function formatMoney(n) {
        return '₱' + parseFloat(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.getElementById('btn-submit-application').addEventListener('click', function() {
        const form = document.getElementById('loan-application-form');
        const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
        const loanTerm = document.getElementById('loan_term').value;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        if (loanAmount < 100) {
            alert('Loan amount must be at least ₱100.');
            return;
        }

        const repaymentMethod = document.getElementById('repayment_method').value;
        if (repaymentMethod === 'Bank') {
            const bankName = document.getElementById('bank_name').value.trim();
            const bankAccNum = document.getElementById('bank_account_number').value.trim();
            const bankAccName = document.getElementById('bank_account_name').value.trim();
            if (!bankName || !bankAccNum || !bankAccName) {
                alert('Please fill in all Bank details. Click on Repayment Method and select Bank again to open the form.');
                document.getElementById('bank-modal').classList.remove('hidden');
                return;
            }
        } else if (repaymentMethod === 'E-Cash') {
            const ecashProvider = document.getElementById('ecash_provider').value;
            const ecashMobile = document.getElementById('ecash_mobile_number').value.trim();
            const ecashAccName = document.getElementById('ecash_account_name').value.trim();
            if (!ecashProvider || !ecashMobile || !ecashAccName) {
                alert('Please fill in all E-Cash details. Click on Repayment Method and select E-Cash again to open the form.');
                document.getElementById('ecash-modal').classList.remove('hidden');
                return;
            }
        }

        const months = parseLoanTermToMonths(loanTerm);
        const interest = Math.round(loanAmount * 0.05 * 100) / 100;
        const total = Math.round((loanAmount + interest) * 100) / 100;
        const monthly = months > 0 ? Math.round((total / months) * 100) / 100 : total;

        const repaymentMethodSelect = document.getElementById('repayment_method');
        let repaymentMethodText = repaymentMethodSelect.options[repaymentMethodSelect.selectedIndex]?.text || '—';
        if (repaymentMethodSelect.value === 'Bank') {
            const bankName = document.getElementById('bank_name').value.trim();
            const bankAccNum = document.getElementById('bank_account_number').value.trim();
            const bankAccName = document.getElementById('bank_account_name').value.trim();
            repaymentMethodText = 'Bank — ' + bankName + ' (Acct: ' + bankAccNum + ', ' + bankAccName + ')';
        } else if (repaymentMethodSelect.value === 'E-Cash') {
            const ecashProvider = document.getElementById('ecash_provider').value;
            const ecashMobile = document.getElementById('ecash_mobile_number').value.trim();
            const ecashAccName = document.getElementById('ecash_account_name').value.trim();
            repaymentMethodText = ecashProvider + ' — ' + ecashMobile + ' (' + ecashAccName + ')';
        }

        document.getElementById('modal-loan-amount').textContent = formatMoney(loanAmount);
        document.getElementById('modal-interest').textContent = formatMoney(interest);
        document.getElementById('modal-total').textContent = formatMoney(total);
        document.getElementById('modal-term').textContent = loanTerm;
        document.getElementById('modal-monthly').textContent = formatMoney(monthly);
        document.getElementById('modal-repayment-method').textContent = repaymentMethodText;

        document.getElementById('loan-confirm-modal').classList.remove('hidden');
    });

    document.getElementById('btn-confirm-submit').addEventListener('click', function() {
        document.getElementById('loan-application-form').submit();
    });
});

function closeConfirmModal() {
    document.getElementById('loan-confirm-modal').classList.add('hidden');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('bank-modal').classList.contains('hidden')) closeBankModal();
        else if (!document.getElementById('ecash-modal').classList.contains('hidden')) closeEcashModal();
        else if (!document.getElementById('loan-confirm-modal').classList.contains('hidden')) closeConfirmModal();
    }
});
function closeBankModal() {
    document.getElementById('bank-modal').classList.add('hidden');
}
function closeEcashModal() {
    document.getElementById('ecash-modal').classList.add('hidden');
}
</script>
@endsection
