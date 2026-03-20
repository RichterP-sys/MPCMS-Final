@extends('UserSide.layouts.app')

@section('title', 'Member Reports')

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
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(59, 130, 246, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 25%, #6366f1 50%, #a855f7 75%, #06b6d4 100%); background-size: 200% 200%;">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full blur-3xl animate-float-delayed" style="background: rgba(255,255,255,0.1);"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Member Reports</h1>
                        <p class="text-cyan-100">View your contributions, loans, and dividends</p>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(207, 250, 254, 1);">Total Contributions</p>
                            <p class="text-2xl font-bold text-white">₱{{ number_format($contributions->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(243, 232, 255, 1);">Active Loans</p>
                            <p class="text-2xl font-bold text-white">{{ $loans->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(209, 250, 229, 1);">Total Dividends</p>
                            <p class="text-2xl font-bold text-white">₱{{ isset($dividends) ? number_format($dividends->sum('dividend_amount'), 2) : '0.00' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contribution Reports -->
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/60 mb-8">
                <div class="px-6 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(6, 182, 212, 0.1), rgba(59, 130, 246, 0.1));">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Contribution Reports</h3>
                            <p class="text-sm text-gray-500">Your contribution history</p>
                        </div>
                    </div>
                </div>
                @if($contributions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($contributions as $contribution)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-cyan-50/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $contribution->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $contribution->type ?? 'Regular Contribution' }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold" style="color: #0891b2;">+₱{{ number_format($contribution->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #e0f2fe, #dbeafe);">
                            <svg class="w-8 h-8" style="color: #0891b2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-gray-500">No contributions found.</p>
                    </div>
                @endif
            </div>

            <!-- Loan Status -->
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/60 mb-8">
                <div class="px-6 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(168, 85, 247, 0.1), rgba(124, 58, 237, 0.1));">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Loan Status</h3>
                            <p class="text-sm text-gray-500">Your loan applications and status</p>
                        </div>
                    </div>
                </div>
                @if($loans->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($loans as $loan)
                            @php
                                $loanStatusStyle = match($loan->status) {
                                    'approved' => 'background: linear-gradient(90deg, #d1fae5, #ccfbf1); color: #047857;',
                                    'pending' => 'background: linear-gradient(90deg, #fef3c7, #ffedd5); color: #b45309;',
                                    'rejected' => 'background: linear-gradient(90deg, #fee2e2, #fce7f3); color: #dc2626;',
                                    default => 'background: #f1f5f9; color: #475569;'
                                };
                            @endphp
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-purple-50/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Loan #{{ $loan->id }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold capitalize" style="{{ $loanStatusStyle }}">
                                                {{ $loan->status }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Released: {{ $loan->released_at ? $loan->released_at->format('M d, Y') : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold" style="color: #7c3aed;">₱{{ number_format($loan->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #f3e8ff, #ede9fe);">
                            <svg class="w-8 h-8" style="color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-gray-500">No loans found.</p>
                    </div>
                @endif
            </div>

            <!-- Dividend Projections or Releases -->
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/60">
                <div class="px-6 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.1));">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Dividend Projections / Releases</h3>
                            <p class="text-sm text-gray-500">Your dividend earnings and projections</p>
                        </div>
                    </div>
                </div>
                @if(isset($dividends) && $dividends->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($dividends as $dividend)
                            @php
                                $dividendStatusStyle = $dividend->status == 'released' 
                                    ? 'background: linear-gradient(90deg, #d1fae5, #ccfbf1); color: #047857;'
                                    : 'background: linear-gradient(90deg, #fef3c7, #ffedd5); color: #b45309;';
                            @endphp
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-emerald-50/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $dividend->year }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mt-1" style="{{ $dividendStatusStyle }}">
                                            {{ $dividend->status == 'released' ? 'Released' : 'Projected' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-sm font-bold" style="color: #059669;">₱{{ number_format($dividend->dividend_amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #d1fae5, #ccfbf1);">
                            <svg class="w-8 h-8" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-gray-500">No dividend records found.</p>
                    </div>
                @endif
            </div>

            <!-- Info Card -->
            <div class="mt-8 p-5 rounded-2xl shadow-sm" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(59, 130, 246, 0.1)); border: 1px solid rgba(6, 182, 212, 0.2);">
                <div class="flex gap-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">About Your Reports</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            This page shows a summary of all your financial activities with the cooperative. For detailed statements or questions about your account, please visit the cooperative office.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
