@extends('AdminSide.layouts.admin')

@section('title', 'Receipt Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4338ca 50%, #059669 75%, #10b981 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(16, 185, 129, 0.3);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-3xl" style="background: rgba(99, 102, 241, 0.3);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-indigo-300 text-sm font-semibold mb-2" style="letter-spacing: 1px; text-transform: uppercase;">Finance Management > Receipts</p>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Receipt Management</h1>
                <p class="text-indigo-200 mt-2 text-sm">View, print, and manage all generated payment receipts. Receipts are automatically created when repayments are confirmed.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.finance.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-xl backdrop-blur border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to Finance</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-indigo-200 uppercase tracking-wide">Total Receipts</p>
                    <p class="text-2xl font-bold">{{ $totalReceipts }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-emerald-200 uppercase tracking-wide">Pending Issues</p>
                    <p class="text-2xl font-bold">{{ $pendingReceipts }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-amber-200 uppercase tracking-wide">Total Amount</p>
                    <p class="text-2xl font-bold">₱{{ number_format($totalAmount, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="rounded-xl p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-xl p-4 bg-red-50 border border-red-200 text-red-800 flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
        <form method="GET" action="{{ route('admin.receipts.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-slate-700 mb-2">Search by Member or Receipt #</label>
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Name, Member ID, or Receipt Number..."
                        class="pl-9 pr-4 py-2.5 w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="min-w-48">
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="min-w-48">
                <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Types</option>
                    <option value="loan" {{ request('type') === 'loan' ? 'selected' : '' }}>Loan</option>
                    <option value="contribution" {{ request('type') === 'contribution' ? 'selected' : '' }}>Contribution</option>
                    <option value="repayment" {{ request('type') === 'repayment' ? 'selected' : '' }}>Repayment</option>
                </select>
            </div>
            <div class="flex gap-2 pt-6">
                <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                    Filter
                </button>
                @if(!empty($search) || !empty(request('status')) || !empty(request('type')))
                <a href="{{ route('admin.receipts.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 border border-slate-200 hover:border-slate-300 rounded-xl transition">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Receipts Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Receipt #</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Amount</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($receipts as $receipt)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900 font-mono">{{ $receipt->receipt_number }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-semibold shadow-lg" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                    {{ strtoupper(substr($receipt->member->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($receipt->member->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $receipt->member->member_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($receipt->record_type === 'loan')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white rounded-full" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Loan
                            </span>
                            @elseif($receipt->record_type === 'repayment')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white rounded-full" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Repayment
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white rounded-full" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Contribution
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900">₱{{ number_format($receipt->amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($receipt->receipt_status === 'issued')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white rounded-full bg-emerald-500">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></path></svg>
                                Issued
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white rounded-full bg-amber-500">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0zm0 3a1 1 0 11-2 0 1 1 0 012 0zm0 3a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/></path></svg>
                                Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-900">{{ $receipt->receipt_issued_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.receipts.show', $receipt->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.receipts.print', $receipt->id) }}" target="_blank" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Print">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </a>
                                @if($receipt->receipt_status === 'pending')
                                <form action="{{ route('admin.receipts.confirm', $receipt->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Confirm Issue" onclick="return confirm('Mark this receipt as issued?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">No receipts yet</h3>
                            <p class="text-sm text-slate-500">Receipts will appear here once you issue them from the finance page.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($receipts->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $receipts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
