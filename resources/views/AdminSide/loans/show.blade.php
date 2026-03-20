@extends('AdminSide.layouts.admin')

@section('title', 'Loan Details')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="text-sm mb-2">
                <ol class="flex items-center gap-2 text-slate-500">
                    <li><a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="hover:text-blue-600 transition">Loans</a></li>
                    <li><i class="fas fa-chevron-right text-xs text-blue-300"></i></li>
                    <li class="text-slate-900 font-medium">Details</li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-slate-900">Loan Details</h2>
        </div>
        <a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg border border-slate-200 shadow transition">
            <i class="fas fa-arrow-left text-blue-600"></i>
            Back to List
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-5 shadow border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-peso-sign text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Amount</p>
                    <p class="text-2xl font-bold text-blue-600">₱{{ number_format(($loan->amount ?? $loan->desired_loan_amount) ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg p-5 shadow border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-pink-600 flex items-center justify-center">
                    <i class="fas fa-info-circle text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</p>
                    @php
                        $status = strtolower($loan->status ?? 'pending');
                        $statusStyles = match($status) {
                            'approved' => 'bg-green-600',
                            'pending' => 'bg-yellow-600',
                            'rejected' => 'bg-red-600',
                            default => 'bg-gray-500'
                        };
                    @endphp
                    <span class="inline-flex items-center mt-1 px-3 py-1 text-xs font-semibold text-white rounded-full {{ $statusStyles }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg p-5 shadow border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-calendar text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Applied</p>
                    <p class="text-sm font-bold text-slate-900">{{ optional($loan->application_date)->format('M d, Y') }}</p>
                    @if($loan->approval_date)
                        <p class="text-xs text-green-600"><i class="fas fa-check mr-1"></i>Approved: {{ optional($loan->approval_date)->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Member Info -->
        <div class="bg-white rounded-lg shadow border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">Member Information</h3>
            </div>
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg mb-4">
                <div class="w-14 h-14 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(optional($loan->member)->first_name ?? '',0,1)) }}{{ strtoupper(substr(optional($loan->member)->last_name ?? '',0,1)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ optional($loan->member)->first_name }} {{ optional($loan->member)->last_name }}</p>
                    <p class="text-sm text-slate-500">{{ optional($loan->member)->email ?? '—' }}</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Member ID</span>
                    <span class="font-medium text-slate-900 bg-blue-50 px-2 py-0.5 rounded">{{ optional($loan->member)->member_id ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-slate-500">Phone</span>
                    <span class="font-medium text-slate-900">{{ optional($loan->member)->phone ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Loan Details -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-yellow-600 flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">Loan Information</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-xs font-medium text-yellow-700 uppercase tracking-wide">Loan Type</p>
                    <p class="mt-1 font-bold text-yellow-900">{{ $loan->loan_type ?? 'Standard' }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Purpose</p>
                    <p class="mt-1 font-bold text-blue-900">{{ $loan->loan_purpose ?? '—' }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Term</p>
                    <p class="mt-1 font-bold text-green-900">{{ $loan->term_months ?? $loan->loan_term ?? '—' }}</p>
                </div>
                <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                    <p class="text-xs font-medium text-pink-700 uppercase tracking-wide">Interest Rate</p>
                    <p class="mt-1 font-bold text-pink-900">{{ isset($loan->interest_rate) ? $loan->interest_rate . '%' : '—' }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Monthly Payment</p>
                    <p class="mt-1 font-bold text-red-900">{{ isset($loan->monthly_repayment) ? '₱' . number_format($loan->monthly_repayment, 2) : (isset($loan->monthly_payment) ? '₱' . number_format($loan->monthly_payment, 2) : '—') }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">Collateral</p>
                    <p class="mt-1 font-bold text-gray-900">
                        @php
                            $collateral = $loan->collateral;
                        @endphp
                        @if ($collateral)
                            @if (isset($collateral->frozen_amount))
                                ₱{{ number_format($collateral->frozen_amount, 2) }}
                            @elseif (is_string($collateral) && is_array(json_decode($collateral, true)))
                                @php $coll = json_decode($collateral, true); @endphp
                                @if(isset($coll['frozen_amount']))
                                    ₱{{ number_format($coll['frozen_amount'], 2) }}
                                @else
                                    {{ $collateral }}
                                @endif
                            @else
                                {{ $collateral }}
                            @endif
                        @else
                            —
                        @endif
                    </p>
                </div>
                @if ($loan->repayment_method)
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 md:col-span-2">
                    <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Repayment Method</p>
                    <p class="mt-1 font-bold text-blue-900">
                        {{ $loan->repayment_method }}
                        @if ($loan->repayment_details)
                            @if ($loan->repayment_method === 'Bank')
                                — {{ $loan->repayment_details['bank_name'] ?? '' }} (Acct: {{ $loan->repayment_details['bank_account_number'] ?? '' }}, {{ $loan->repayment_details['bank_account_name'] ?? '' }})
                            @elseif ($loan->repayment_method === 'E-Cash')
                                — {{ $loan->repayment_details['ecash_provider'] ?? '' }} {{ $loan->repayment_details['ecash_mobile_number'] ?? '' }} ({{ $loan->repayment_details['ecash_account_name'] ?? '' }})
                            @endif
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Repayments -->
    <div class="bg-white rounded-lg shadow border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-green-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center">
                    <i class="fas fa-receipt text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Repayments</h3>
                    <p class="text-sm text-slate-500">Record payments and track balance</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Remaining Balance</p>
                <p class="text-2xl font-bold text-green-600">₱{{ number_format((float) ($loan->remaining_balance ?? ($loan->amount ?? $loan->desired_loan_amount) ?? 0), 2) }}</p>
            </div>
        </div>

        @if (session('error'))
            <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif

        <div class="p-6">
            <form method="POST" action="{{ route('admin.loans.repayments.store', $loan) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 bg-green-50 rounded-lg border border-green-200 mb-6">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Amount</label>
                    <input name="amount" type="number" step="0.01" min="0.01" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-300" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Payment Date</label>
                    <input name="payment_date" type="date" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-300" value="{{ now()->toDateString() }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Method</label>
                    <input name="payment_method" type="text" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-300" placeholder="Cash / Bank" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Reference</label>
                    <input name="reference_number" type="text" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-300" placeholder="Optional">
                </div>
                <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fas fa-plus"></i>
                        Record Payment
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide">Method</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($loan->repayments()->latest('payment_date')->get() as $repayment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3.5 text-sm text-slate-700">{{ optional($repayment->payment_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3.5 text-sm font-bold text-green-600">₱{{ number_format((float) $repayment->amount, 2) }}</td>
                                <td class="px-4 py-3.5 text-sm text-slate-700">{{ $repayment->payment_method }}</td>
                                <td class="px-4 py-3.5 text-sm text-slate-500">{{ $repayment->reference_number ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-receipt text-gray-400"></i>
                                        </div>
                                        <p>No repayments recorded yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
