@extends('UserSide.layouts.app')

@section('title', 'My Receipts & Repayments')

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
    .animate-float-delayed {
        animation: float 8s ease-in-out infinite;
        animation-delay: -2s;
    }
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 50%, #faf5ff 100%);">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(20, 184, 166, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), rgba(168, 85, 247, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 25%, #06b6d4 50%, #0ea5e9 75%, #10b981 100%); background-size: 200% 200%;">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div>
                        <p class="text-emerald-100 text-sm font-semibold mb-2" style="letter-spacing: 1px; text-transform: uppercase;">Member Dashboard > Receipts</p>
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Payment Receipts</h1>
                        <p class="text-sm sm:text-base text-emerald-50">Download, view, and print your official payment receipts for all loan repayments.</p>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Total Loan Repayments Made</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $repayments->count() }}</p>
                            <p class="text-xs text-slate-500 mt-1">All payments recorded</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Total Amount Paid Back</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-2">₱{{ number_format($totalRepaid, 2) }}</p>
                            <p class="text-xs text-slate-500 mt-1">Cumulative repayment</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #06b6d4, #0ea5e9);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Official Receipts Issued</p>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $receiptsIssued }}</p>
                            <p class="text-xs text-slate-500 mt-1">Available for download</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipts List -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.05), transparent);">
                    <h3 class="text-lg font-semibold text-slate-800">Repayment History</h3>
                </div>

                @if($repayments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.05), transparent);">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Payment Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Receipt #</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($repayments as $repayment)
                                    @php
                                        $loan = $repayment->loan;
                                    @endphp
                                    <tr class="hover:bg-emerald-50/50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-800">#{{ $loan->id }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $repayment->payment_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">₱{{ number_format($repayment->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: linear-gradient(135deg, #d1fae5, #ccfbf1); color: #047857;">
                                                {{ ucfirst($repayment->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($repayment->receipt_number)
                                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $repayment->receipt_number }}</span>
                                            @else
                                                <span class="text-xs text-slate-500 italic">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($repayment->receipt_number)
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('user.receipts.show', $repayment->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition-all" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        View
                                                    </a>
                                                    <a href="{{ route('user.receipts.print', $repayment->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition-all" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                        Print
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-500 italic">No receipt yet</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-slate-600 text-lg font-medium mb-2">No repayments yet</p>
                        <p class="text-slate-500 text-sm">Your repayment records will appear here once you make your first loan repayment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
