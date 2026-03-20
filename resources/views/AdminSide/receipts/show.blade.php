@extends('AdminSide.layouts.admin')

@section('title', 'Receipt #' . $receipt->receipt_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Receipt #{{ $receipt->receipt_number }}</h1>
            <p class="text-sm text-slate-600 mt-1">Viewing receipt details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.receipts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-slate-600 border border-slate-200 hover:border-slate-300 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
            <a href="{{ route('admin.receipts.print', $receipt->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Print
            </a>
        </div>
    </div>

    <!-- Receipt Document -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-8">
        <!-- Header -->
        <div class="text-center mb-8 pb-8 border-b-2 border-slate-200">
            <div class="h-14 w-14 rounded-xl flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">PAYMENT RECEIPT</h1>
            <p class="text-slate-600">Official Transaction Record</p>
        </div>

        <!-- Receipt Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Left Column -->
            <div>
                <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4">Receipt Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Receipt Number</p>
                        <p class="text-lg font-bold font-mono text-emerald-600">{{ $receipt->receipt_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Issued Date</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $receipt->receipt_issued_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Issued Time</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $receipt->receipt_issued_at->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Status</p>
                        <div class="mt-1">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4">Member Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Name</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Member ID</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $receipt->member->member_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm font-semibold text-slate-800 break-all">{{ $receipt->member->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Phone</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $receipt->member->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="mb-8 pb-8 border-t border-b border-slate-200">
            <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4 mt-8">Payment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Transaction Type</p>
                            <p class="text-sm font-semibold text-slate-800 capitalize">{{ $receipt->record_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Record ID</p>
                            <p class="text-sm font-semibold text-slate-800">#{{ $receipt->record_id }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Payment Amount</p>
                            <p class="text-2xl font-bold text-emerald-600">₱{{ number_format($receipt->amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Box -->
        <div class="bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-xl p-6 mb-8 border border-emerald-100">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 font-medium">Receipt Amount:</span>
                    <span class="text-xl font-bold text-emerald-600">₱{{ number_format($receipt->amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-emerald-200">
                    <span class="text-slate-800 font-semibold">Total Amount Due:</span>
                    <span class="text-2xl font-bold text-emerald-700">₱{{ number_format($receipt->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-8 border-t border-slate-200">
            <p class="text-xs text-slate-500 mb-3">This is an official receipt for the transaction.</p>
            <div class="h-14 w-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-700">Payment Received & Processed</p>
            <p class="text-xs text-slate-500 mt-4">{{ config('app.name', 'MPCMS') }} | Cooperative Management System</p>
            <p class="text-xs text-slate-400 mt-1">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</div>
@endsection
