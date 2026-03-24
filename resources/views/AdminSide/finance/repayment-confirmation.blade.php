@extends('AdminSide.layouts.admin')

@section('title', 'Repayment Confirmation')

@section('content')
<style>
    .table-row-hover {
        transition: all 0.2s ease;
    }
    .table-row-hover:hover {
        background: linear-gradient(90deg, rgba(99, 102, 241, 0.03), rgba(139, 92, 246, 0.03), transparent);
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4338ca 50%, #059669 75%, #10b981 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(16, 185, 129, 0.3);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-indigo-300 text-sm font-semibold mb-2" style="letter-spacing: 1px; text-transform: uppercase;">Finance Management > Repayment Confirmation</p>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Confirm Monthly Repayments</h1>
                <p class="text-indigo-200 mt-2 text-sm">Review and confirm loan repayments from members. Payments automatically generate receipt records and issue receipts to members.</p>
            </div>
            <a href="{{ route('admin.finance.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-xl backdrop-blur border border-white/20 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Finance</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl flex items-start gap-3 shadow-sm bg-emerald-50 border border-emerald-300 border-l-4 border-l-emerald-600">
            <i class="fas fa-check-circle text-emerald-600 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-medium text-emerald-900">Success!</p>
                <p class="text-sm text-emerald-800 mt-0.5">{{ session('success') }} Receipts have been automatically generated for all confirmed payments.</p>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-xl flex items-start gap-3 shadow-sm bg-red-50 border border-red-300 border-l-4 border-l-red-600">
            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-medium text-red-900">Error</p>
                <p class="text-sm text-red-800 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Helper Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-medium text-blue-900 text-sm">How This Works</h4>
                <p class="text-sm text-blue-800 mt-1"><strong>✓ Select</strong> one or more loans from members who should pay this month &nbsp; <strong>✓ Confirm</strong> their payment with date and method &nbsp; <strong>✓ Receipt</strong> is automatically generated and issued</p>
            </div>
        </div>
    </div>

    <!-- Active Loans Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-slate-50 to-emerald-50/30 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold" id="loan-count">0</span>
                    Loans Due This Month
                </h3>
                <p class="text-xs text-slate-500 mt-1">Members who haven't paid their scheduled monthly repayment yet</p>
            </div>
            <span id="batch-actions" class="hidden flex items-center gap-2">
                <span id="selected-count" class="text-sm text-slate-600 bg-blue-100 px-3 py-1.5 rounded-lg font-medium">0 selected</span>
                <button type="button" id="btn-confirm-selected" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white rounded-lg bg-green-600 hover:bg-green-700 shadow-md transition">
                    <i class="fas fa-check-double"></i> Confirm Selected
                </button>
            </span>
            <form method="GET" action="{{ route('admin.finance.repayment-confirmation') }}" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Search by name, member ID, or loan #"
                        class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                    Search
                </button>
                @if(!empty($search))
                <a href="{{ route('admin.finance.repayment-confirmation') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 border border-slate-200 rounded-xl transition">
                    Clear
                </a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="select-all" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Select</span>
                            </label>
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Loan #</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Remaining Balance</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Monthly Repayment</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Due Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activeLoans as $loan)
                    <tr class="table-row-hover" data-loan-id="{{ $loan->id }}" data-monthly="{{ (float) min($loan->monthly_repayment ?? 0, $loan->remaining_balance ?? 0) }}" data-remaining="{{ (float) ($loan->remaining_balance ?? 0) }}">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="loan-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="{{ $loan->id }}">
                        </td>
                        <td class="px-6 py-4">
                            @if($loan->member)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-semibold shadow-lg bg-gradient-to-br from-indigo-500 to-purple-600">
                                    {{ strtoupper(substr($loan->member->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($loan->member->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $loan->member->first_name }} {{ $loan->member->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $loan->member->member_id ?? '—' }}</p>
                                </div>
                            </div>
                            @else
                            <p class="text-sm text-slate-500">—</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.loans.show', $loan->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">#{{ $loan->id }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">₱{{ number_format((float) ($loan->remaining_balance ?? $loan->amount ?? 0), 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-900">₱{{ number_format((float) ($loan->monthly_repayment ?? 0), 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $approvalDate = $loan->approval_date ? \Carbon\Carbon::parse($loan->approval_date) : null;
                                $dueDay = $approvalDate ? min($approvalDate->day, now()->daysInMonth) : now()->day;
                                $dueDate = $approvalDate ? \Carbon\Carbon::create(now()->year, now()->month, $dueDay) : now();
                                $isPastDue = $dueDate->isPast();
                            @endphp
                            <p class="text-sm font-medium {{ $isPastDue ? 'text-red-600' : 'text-slate-700' }}">{{ $dueDate->format('M j') }}</p>
                            <p class="text-xs {{ $isPastDue ? 'text-red-500' : 'text-slate-500' }}">{{ $dueDate->format('l') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openConfirmPaymentModal({{ $loan->id }}, {{ json_encode((float) min($loan->monthly_repayment ?? 0, $loan->remaining_balance ?? 0)) }}, {{ json_encode((float) ($loan->remaining_balance ?? 0)) }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white rounded-lg bg-green-600 hover:bg-green-700 transition">
                                    <i class="fas fa-check"></i> Confirm Payment
                                </button>
                                <form action="{{ route('admin.finance.mark-didnt-pay', $loan) }}" method="POST" class="inline" onsubmit="return confirm('Send payment reminder to this member?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white rounded-lg bg-red-600 hover:bg-red-700 transition">
                                        <i class="fas fa-times"></i> Didn't Pay
                                    </button>
                                </form>
                                <a href="{{ route('admin.loans.show', $loan->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="View Loan">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hand-holding-dollar text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">No overdue payments this month</h3>
                            <p class="text-sm text-slate-500">All members with a payment due this month have paid, or no loans have a payment due this month.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($activeLoans->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $activeLoans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Confirm Payment Modal -->
<div id="confirm-payment-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeConfirmPaymentModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4" onclick="closeConfirmPaymentModal()">
        <div class="relative w-full max-w-md rounded-2xl shadow-2xl bg-white border border-slate-200 overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-gray-900">Record Payment</h3>
                <p class="text-sm text-gray-600 mt-1" id="modal-desc">Fill in the payment details to confirm the repayment</p>
            </div>
            <div class="p-6">
                <form id="confirm-payment-form" method="POST" action="">
                    @csrf
                    <div id="modal-amount-wrap" class="space-y-4">
                        <div id="modal-amount-field">
                            <label for="modal_amount" class="block text-sm font-semibold text-gray-700 mb-2">Payment Amount (₱) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-600 text-sm">₱</span>
                                <input type="number" id="modal_amount" name="amount" step="0.01" min="0.01" placeholder="0.00"
                                    class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the amount received from the member</p>
                        </div>
                        <div>
                            <label for="modal_payment_date" class="block text-sm font-semibold text-gray-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
                            <input type="date" id="modal_payment_date" name="payment_date" required
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="text-xs text-gray-500 mt-1">When was this payment received?</p>
                        </div>
                        <div>
                            <label for="modal_payment_method" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                            <select id="modal_payment_method" name="payment_method" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">— Select payment method —</option>
                                <option value="Cash">Cash (Physical currency)</option>
                                <option value="Bank Transfer">Bank Transfer (Online)</option>
                                <option value="Bank Deposit">Bank Deposit (Over the counter)</option>
                                <option value="GCash">GCash (Mobile wallet)</option>
                                <option value="Maya">Maya (Mobile wallet)</option>
                                <option value="Check">Check (Cheque payment)</option>
                                <option value="Other">Other</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">How did the member pay?</p>
                        </div>
                        <div>
                            <label for="modal_reference" class="block text-sm font-semibold text-gray-700 mb-2">Reference Number (Optional)</label>
                            <input type="text" id="modal_reference" name="reference_number" placeholder="e.g., TXN123456, Check#789"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="text-xs text-gray-500 mt-1">Transaction ID, check number, or other reference</p>
                        </div>
                    </div>
                    <div id="payment-form-errors" class="mt-4 hidden p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700"></div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" onclick="closeConfirmPaymentModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit" id="record-payment-btn" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 shadow-md transition">
                            <i class="fas fa-check mr-2"></i>Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmed Modal -->
<div id="payment-confirmed-modal" class="fixed inset-0 z-[60] hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closePaymentConfirmedModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4" onclick="closePaymentConfirmedModal()">
        <div class="relative w-full max-w-sm rounded-2xl shadow-2xl bg-white border border-slate-200 overflow-hidden text-center p-8" onclick="event.stopPropagation()">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl text-emerald-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Payment Confirmed</h3>
            <p id="payment-confirmed-msg" class="text-sm text-gray-600 mb-6">The payment has been recorded successfully.</p>
            <button type="button" onclick="closePaymentConfirmedModal()" class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                OK
            </button>
        </div>
    </div>
</div>

<script>
const batchConfirmUrl = '{{ route("admin.finance.batch-confirm-repayments") }}';
const singleRepayUrl = '{{ route("admin.loans.repayments.store", ["loan" => 999]) }}';

function openConfirmPaymentModal(loanId, defaultAmount, remainingBalance) {
    const form = document.getElementById('confirm-payment-form');
    form.action = singleRepayUrl.replace('999', loanId);
    form.dataset.mode = 'single';
    form.dataset.loanId = loanId;
    delete form.dataset.loanIds;
    document.getElementById('modal_amount').value = defaultAmount > 0 ? defaultAmount : remainingBalance;
    document.getElementById('modal_amount').required = true;
    document.getElementById('modal-amount-field').style.display = '';
    document.getElementById('modal-desc').textContent = 'Record the loan payment.';
    document.getElementById('modal_payment_date').value = '{{ date("Y-m-d") }}';
    document.getElementById('payment-form-errors').classList.add('hidden');
    document.getElementById('payment-form-errors').textContent = '';
    document.getElementById('confirm-payment-modal').classList.remove('hidden');
}

function openBatchConfirmModal(loanIds) {
    const form = document.getElementById('confirm-payment-form');
    form.action = batchConfirmUrl;
    form.dataset.mode = 'batch';
    form.dataset.loanIds = JSON.stringify(loanIds);
    delete form.dataset.loanId;
    document.getElementById('modal_amount').required = false;
    document.getElementById('modal-amount-field').style.display = 'none';
    document.getElementById('modal-desc').textContent = 'Record payments for ' + loanIds.length + ' selected loan(s). Each will use its monthly repayment amount.';
    document.getElementById('modal_payment_date').value = '{{ date("Y-m-d") }}';
    document.getElementById('payment-form-errors').classList.add('hidden');
    document.getElementById('payment-form-errors').textContent = '';
    document.getElementById('confirm-payment-modal').classList.remove('hidden');
}
function closeConfirmPaymentModal() {
    document.getElementById('confirm-payment-modal').classList.add('hidden');
}
function closePaymentConfirmedModal() {
    document.getElementById('payment-confirmed-modal').classList.add('hidden');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('confirm-payment-modal').classList.contains('hidden')) closeConfirmPaymentModal();
        else if (!document.getElementById('payment-confirmed-modal').classList.contains('hidden')) closePaymentConfirmedModal();
    }
});
function showPaymentConfirmedModal() {
    closeConfirmPaymentModal();
    document.getElementById('payment-confirmed-modal').classList.remove('hidden');
}

document.getElementById('confirm-payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const btn = document.getElementById('record-payment-btn');
    const errorsEl = document.getElementById('payment-form-errors');
    const isBatch = form.dataset.mode === 'batch';

    btn.disabled = true;
    btn.textContent = isBatch ? 'Confirming...' : 'Recording...';
    errorsEl.classList.add('hidden');
    errorsEl.textContent = '';

    const csrf = document.querySelector('input[name="_token"]').value;
    let body;
    if (isBatch) {
        const loanIds = JSON.parse(form.dataset.loanIds || '[]');
        body = {
            _token: csrf,
            loan_ids: loanIds,
            payment_date: form.payment_date.value,
            payment_method: form.payment_method.value,
            reference_number: form.reference_number.value || null
        };
    } else {
        body = {
            _token: csrf,
            amount: form.amount.value,
            payment_date: form.payment_date.value,
            payment_method: form.payment_method.value,
            reference_number: form.reference_number.value || null
        };
    }

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(body)
    })
    .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
    .then(({ ok, status, data }) => {
        btn.disabled = false;
        btn.textContent = 'Record Payment';
        if (ok && data.success) {
            if (isBatch && data.confirmed > 0) {
                const loanIds = JSON.parse(form.dataset.loanIds || '[]');
                loanIds.forEach(function(loanId) {
                    const row = document.querySelector('tr[data-loan-id="' + loanId + '"]');
                    if (row) row.remove();
                });
                document.querySelectorAll('.loan-checkbox:checked').forEach(cb => cb.checked = false);
                document.getElementById('select-all').checked = false;
                updateBatchUI();
            } else if (!isBatch) {
                const loanId = form.dataset.loanId;
                const row = document.querySelector('tr[data-loan-id="' + loanId + '"]');
                if (row) row.remove();
            }
            const tbody = document.querySelector('table tbody');
            if (tbody && !tbody.querySelector('tr[data-loan-id]')) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center"><div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center mx-auto mb-4"><i class="fas fa-hand-holding-dollar text-2xl text-slate-400"></i></div><h3 class="text-sm font-medium text-slate-900 mb-1">No overdue payments this month</h3><p class="text-sm text-slate-500">All members with a payment due this month have paid, or no loans have a payment due this month.</p></td></tr>';
            }
            closeConfirmPaymentModal();
            document.getElementById('payment-confirmed-msg').textContent = data.message || 'The payment(s) have been recorded successfully.';
            showPaymentConfirmedModal();
        } else {
            const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Something went wrong.');
            errorsEl.textContent = msg;
            errorsEl.classList.remove('hidden');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Record Payment';
        errorsEl.textContent = 'Something went wrong. Please try again.';
        errorsEl.classList.remove('hidden');
    });
});

function updateBatchUI() {
    const checked = document.querySelectorAll('.loan-checkbox:checked');
    const count = checked.length;
    const batchActions = document.getElementById('batch-actions');
    const selectedCount = document.getElementById('selected-count');
    if (count > 0) {
        batchActions.classList.remove('hidden');
        selectedCount.textContent = count + ' selected';
    } else {
        batchActions.classList.add('hidden');
    }
}

document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.loan-checkbox').forEach(cb => { cb.checked = this.checked; });
    updateBatchUI();
});

document.querySelectorAll('.loan-checkbox').forEach(function(cb) {
    cb.addEventListener('change', updateBatchUI);
});

document.getElementById('btn-confirm-selected').addEventListener('click', function() {
    const ids = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => parseInt(cb.value, 10));
    if (ids.length > 0) openBatchConfirmModal(ids);
});
</script>
@endsection
