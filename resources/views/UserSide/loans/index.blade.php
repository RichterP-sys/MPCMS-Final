@extends('UserSide.layouts.app')

@section('title', 'My Loans')

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
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(249, 115, 22, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 25%, #a855f7 50%, #ec4899 75%, #3b82f6 100%); background-size: 200% 200%;">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">My Loans</h1>
                        <p class="text-blue-100">Manage and track your loan applications</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('user.loans.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all" style="background: linear-gradient(135deg, #fbbf24, #f97316);">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            New Loan Application
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl flex items-center shadow-sm" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); border: 1px solid #6ee7b7;">
                    <svg class="w-5 h-5 mr-3" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color: #065f46;">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl flex items-center shadow-sm" style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1px solid #fca5a5;">
                    <svg class="w-5 h-5 mr-3" style="color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color: #991b1b;">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Loans -->
                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);">
                    <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center">
                        <div class="h-14 w-14 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm" style="color: rgba(219, 234, 254, 1);">Total Loans</p>
                            <p class="text-3xl font-bold text-white">{{ $loans->total() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Loans -->
                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
                    <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center">
                        <div class="h-14 w-14 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm" style="color: rgba(254, 243, 199, 1);">Pending Loans</p>
                            <p class="text-3xl font-bold text-white">{{ $loans->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Approved Loans -->
                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
                    <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center">
                        <div class="h-14 w-14 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm" style="color: rgba(209, 250, 229, 1);">Approved Loans</p>
                            <p class="text-3xl font-bold text-white">{{ $loans->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Card -->
            <div class="mb-8 p-6 bg-white rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-lg transition-all">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Ready to Apply for a Loan?</h3>
                            <p class="text-sm text-gray-600 mt-1">Submit your loan application with required documents</p>
                        </div>
                    </div>
                    <a href="{{ route('user.loans.create') }}" class="px-8 py-3 text-white font-bold rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2 whitespace-nowrap" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Submit Loan
                    </a>
                </div>
            </div>

            <!-- Loans Table -->
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/60">
                @if ($loans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background: linear-gradient(90deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Loan #</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Term</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Application Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($loans as $loan)
                                    @php
                                        $statusStyle = match($loan->status) {
                                            'approved' => 'background: linear-gradient(90deg, #d1fae5, #ccfbf1); color: #047857;',
                                            'pending' => 'background: linear-gradient(90deg, #fef3c7, #ffedd5); color: #b45309;',
                                            'rejected' => 'background: linear-gradient(90deg, #fee2e2, #fce7f3); color: #dc2626;',
                                            default => 'background: #f1f5f9; color: #475569;'
                                        };
                                    @endphp
                                    <tr class="hover:bg-purple-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold" style="color: #6366f1;">#{{ $loan->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-900">₱{{ number_format($loan->desired_loan_amount, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $loan->loan_purpose }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">{{ $loan->loan_term }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">{{ $loan->application_date->format('M d, Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize" style="{{ $statusStyle }}">
                                                {{ $loan->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('user.loans.show', $loan->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-white transition-all hover:-translate-y-0.5" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100" style="background: linear-gradient(90deg, rgba(241, 245, 249, 0.5), transparent);">
                        {{ $loans->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #e0e7ff, #ddd6fe);">
                            <svg class="w-10 h-10" style="color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Loans Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't submitted any loan applications yet.</p>
                        <a href="{{ route('user.loans.create') }}" class="inline-flex items-center px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Submit New Application
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
