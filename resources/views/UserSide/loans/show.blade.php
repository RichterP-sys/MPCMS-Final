@extends('UserSide.layouts.app')

@section('title', 'Loan Application #' . $loan->id)

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

@php
    $statusConfig = match($loan->status) {
        'approved' => [
            'gradient' => 'linear-gradient(135deg, #10b981, #14b8a6)',
            'bg' => 'linear-gradient(135deg, #d1fae5, #ccfbf1)',
            'border' => '#6ee7b7',
            'text' => '#047857',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'message' => 'Congratulations! Your loan application has been approved. Please contact the cooperative office for next steps.'
        ],
        'pending' => [
            'gradient' => 'linear-gradient(135deg, #f59e0b, #f97316)',
            'bg' => 'linear-gradient(135deg, #fef3c7, #ffedd5)',
            'border' => '#fcd34d',
            'text' => '#b45309',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'message' => 'Your loan application is currently under review. You will receive a notification once a decision has been made.'
        ],
        'rejected' => [
            'gradient' => 'linear-gradient(135deg, #ef4444, #f87171)',
            'bg' => 'linear-gradient(135deg, #fee2e2, #fecaca)',
            'border' => '#fca5a5',
            'text' => '#dc2626',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'message' => 'Unfortunately, your loan application has been rejected. Please contact the cooperative office for more information.'
        ],
        default => [
            'gradient' => 'linear-gradient(135deg, #64748b, #94a3b8)',
            'bg' => 'linear-gradient(135deg, #f1f5f9, #e2e8f0)',
            'border' => '#cbd5e1',
            'text' => '#475569',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'message' => 'Your application status is ' . $loan->status . '.'
        ]
    };
@endphp

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 50%, #faf5ff 100%);">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(249, 115, 22, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 25%, #ec4899 50%, #f97316 75%, #6366f1 100%); background-size: 200% 200%;">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Loan Application #{{ $loan->id }}</h1>
                        <p class="text-purple-100">View your application details and status</p>
                    </div>
                    <a href="{{ route('user.loans.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Loans
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl flex items-center shadow-sm" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); border: 1px solid #6ee7b7;">
                    <svg class="w-5 h-5 mr-3" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color: #065f46;">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Status Alert -->
            <div class="mb-6 p-5 rounded-2xl shadow-sm border-l-4" style="background: {{ $statusConfig['bg'] }}; border-left-color: {{ $statusConfig['border'] }};">
                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background: {{ $statusConfig['gradient'] }};">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold" style="color: {{ $statusConfig['text'] }};">
                            Application Status: <span class="capitalize">{{ $loan->status }}</span>
                        </h3>
                        <p class="mt-1 text-sm" style="color: {{ $statusConfig['text'] }}; opacity: 0.9;">
                            {{ $statusConfig['message'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Application Details Card -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden mb-6 border border-slate-200/60">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Application Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="p-5 rounded-xl" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(168, 85, 247, 0.05));">
                            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" style="color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Personal Information
                            </h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">First Name</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $loan->first_name }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Last Name</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $loan->last_name }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->email }}</dd>
                                </div>
                                <div class="flex justify-between py-2">
                                    <dt class="text-sm text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->cell_phone ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Loan Information -->
                        <div class="p-5 rounded-xl" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(20, 184, 166, 0.05));">
                            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" style="color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Loan Information
                            </h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Loan Amount (Principal)</dt>
                                    <dd class="text-lg font-bold" style="color: #10b981;">₱{{ number_format($loan->amount ?? $loan->desired_loan_amount ?? 0, 2) }}</dd>
                                </div>
                                @if (isset($loan->interest_rate) && $loan->interest_rate > 0)
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Interest ({{ $loan->interest_rate }}%)</dt>
                                    <dd class="text-sm font-semibold text-gray-900">₱{{ number_format($loan->interest_amount ?? 0, 2) }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Total Amount to Repay</dt>
                                    <dd class="text-sm font-bold text-gray-900">₱{{ number_format($loan->total_amount ?? 0, 2) }}</dd>
                                </div>
                                @if (isset($loan->monthly_repayment) && $loan->monthly_repayment > 0)
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Monthly Repayment</dt>
                                    <dd class="text-sm font-semibold text-gray-900">₱{{ number_format($loan->monthly_repayment, 2) }}</dd>
                                </div>
                                @endif
                                @endif
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Loan Purpose</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->loan_purpose }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Loan Term</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->loan_term }}</dd>
                                </div>
                                @if ($loan->repayment_method)
                                <div class="flex justify-between py-2 border-b border-slate-100">
                                    <dt class="text-sm text-gray-500">Repayment Method</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $loan->repayment_method }}
                                        @if ($loan->repayment_details)
                                            @if ($loan->repayment_method === 'Bank')
                                                — {{ $loan->repayment_details['bank_name'] ?? '' }} ({{ $loan->repayment_details['bank_account_number'] ?? '' }})
                                            @elseif ($loan->repayment_method === 'E-Cash')
                                                — {{ $loan->repayment_details['ecash_provider'] ?? '' }} {{ $loan->repayment_details['ecash_mobile_number'] ?? '' }}
                                            @endif
                                        @endif
                                    </dd>
                                </div>
                                @endif
                                <div class="flex justify-between py-2">
                                    <dt class="text-sm text-gray-500">Application Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->application_date->format('F d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Source of Fund -->
                    @if ($loan->source_of_fund)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" style="color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Source of Fund
                            </h3>
                            <p class="text-sm text-gray-900 p-4 rounded-xl" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(249, 115, 22, 0.05));">{{ $loan->source_of_fund }}</p>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" style="color: #a855f7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Timeline
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Application Submitted</p>
                                    <p class="text-sm text-gray-500">{{ $loan->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            @if ($loan->approval_date)
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: {{ $statusConfig['gradient'] }};">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            @if ($loan->status === 'approved')
                                                Application Approved
                                            @else
                                                Decision Made
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $loan->approval_date->format('F d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Supporting Documents -->
                    @php
                        $attachmentPath = 'loan_applications/' . $loan->id;
                        $attachments = \Storage::disk('public')->files($attachmentPath);
                    @endphp
                    
                    @if (count($attachments) > 0)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" style="color: #ec4899;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                Attached Documents
                            </h3>
                            <div class="space-y-2">
                                @foreach ($attachments as $attachment)
                                    @php
                                        $filename = basename($attachment);
                                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                        $fileStyle = match($ext) {
                                            'pdf' => ['bg' => 'linear-gradient(135deg, #ef4444, #f87171)', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                            'doc', 'docx' => ['bg' => 'linear-gradient(135deg, #3b82f6, #6366f1)', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                            'jpg', 'jpeg', 'png', 'gif' => ['bg' => 'linear-gradient(135deg, #10b981, #14b8a6)', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                            default => ['bg' => 'linear-gradient(135deg, #64748b, #94a3b8)', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z']
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between p-4 rounded-xl transition-all hover:shadow-md" style="background: linear-gradient(135deg, rgba(241, 245, 249, 0.5), rgba(226, 232, 240, 0.5));">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center shadow-sm" style="background: {{ $fileStyle['bg'] }};">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $fileStyle['icon'] }}"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $filename }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format(\Storage::disk('public')->size($attachment) / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ \Storage::disk('public')->url($attachment) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-white transition-all hover:-translate-y-0.5" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Information Box -->
            <div class="p-5 rounded-2xl mb-6 shadow-sm" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1)); border: 1px solid rgba(99, 102, 241, 0.2);">
                <div class="flex gap-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Need Help?</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            If you have questions about your loan application or need to update your information, please contact the cooperative office or visit during office hours.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4">
                <a href="{{ route('user.loans.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
