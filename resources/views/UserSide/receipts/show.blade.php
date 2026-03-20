@extends('UserSide.layouts.app')

@section('title', 'Receipt #' . $repayment->receipt_number)

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
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 15s ease infinite;
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }
    @media print {
        body {
            background: white !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 50%, #faf5ff 100%);">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(20, 184, 166, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Top Navigation -->
        <div class="bg-white/90 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-50 shadow-sm no-print">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <a href="{{ route('user.receipts.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Receipts
                </a>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold text-sm transition-all" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Receipt Document -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-lg p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-8 pb-8 border-b-2 border-slate-200">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">PAYMENT RECEIPT</h1>
                    <p class="text-slate-600">Loan Repayment Documentation</p>
                </div>

                <!-- Receipt Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column -->
                    <div>
                        <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4">Receipt Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Receipt Number</p>
                                <p class="text-lg font-bold font-mono text-emerald-600">{{ $repayment->receipt_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Issued Date</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $repayment->receipt_issued_at?->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Issued Time</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $repayment->receipt_issued_at?->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4">Member Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Name</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $member->first_name }} {{ $member->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Member ID</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $member->member_id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Email</p>
                                <p class="text-sm font-semibold text-slate-800 break-all">{{ $member->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repayment Details -->
                <div class="mb-8 pb-8 border-t border-b border-slate-200">
                    <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-4 mt-8">Repayment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Loan Info -->
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Loan Number</p>
                                    <p class="text-lg font-bold text-slate-800">#{{ $loan->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Original Amount</p>
                                    <p class="text-sm font-semibold text-slate-800">₱{{ number_format($loan->amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Loan Purpose</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ $loan->loan_purpose ?? 'General Loan' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Payment Amount</p>
                                    <p class="text-2xl font-bold text-emerald-600">₱{{ number_format($repayment->amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Payment Date</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ $repayment->payment_date->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider">Payment Method</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ ucfirst($repayment->payment_method) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if($repayment->reference_number)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Reference Number</p>
                            <p class="text-sm font-mono font-semibold text-slate-800">{{ $repayment->reference_number }}</p>
                        </div>
                    @endif
                </div>

                <!-- Summary -->
                <div class="bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-xl p-6 mb-8 border border-emerald-100">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Repayment Amount:</span>
                            <span class="text-xl font-bold text-emerald-600">₱{{ number_format($repayment->amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-emerald-200">
                            <span class="text-slate-800 font-semibold">Total Paid:</span>
                            <span class="text-2xl font-bold text-emerald-700">₱{{ number_format($repayment->amount, 2) }}</span>
                        </div>
                        @if($loan->remaining_balance !== null)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Remaining Balance:</span>
                                <span class="font-semibold text-slate-800">₱{{ number_format($loan->remaining_balance, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center pt-8 border-t border-slate-200">
                    <p class="text-xs text-slate-500 mb-3">This is an official receipt for the loan repayment transaction.</p>
                    <div class="h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Payment Received & Processed</p>
                    <p class="text-xs text-slate-500 mt-4">{{ config('app.name', 'MPCMS') }} | Cooperative Management System</p>
                    <p class="text-xs text-slate-400 mt-1">Receipt generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>

            <!-- Additional Actions -->
            <div class="mt-6 flex items-center justify-center gap-3 no-print">
                <a href="{{ route('user.receipts.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-slate-800 font-semibold text-sm transition-all border border-slate-300 hover:border-slate-400 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to List
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-white font-semibold text-sm transition-all" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Print Receipt
                </button>
                <a href="{{ route('user.loans.show', $loan->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-white font-semibold text-sm transition-all" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    View Loan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
