@extends('UserSide.layouts.app')

@section('title', 'Activity Log')

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
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(236, 72, 153, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.15));"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 25%, #ec4899 50%, #f97316 75%, #7c3aed 100%); background-size: 200% 200%;">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full blur-3xl animate-float-delayed" style="background: rgba(255,255,255,0.1);"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Activity Log</h1>
                        <p class="text-purple-100">Track all your account activities and history</p>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filter Tabs: All Activities | Access Logs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('user.activity-log') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition {{ ($currentFilter ?? null) !== 'access' ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    All Activities
                </a>
                <a href="{{ route('user.activity-log', ['filter' => 'access']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition {{ ($currentFilter ?? null) === 'access' ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Access Logs (Login/Logout)
                </a>
            </div>
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(243, 232, 255, 1);">Total Activities</p>
                            <p class="text-2xl font-bold text-white">{{ $activities->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(209, 250, 229, 1);">Login Activities</p>
                            <p class="text-2xl font-bold text-white">{{ $activities->where('activity_type', 'login')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl p-5 shadow-lg" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
                    <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6" style="background: rgba(255,255,255,0.15);"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <div>
                            <p class="text-sm" style="color: rgba(254, 243, 199, 1);">Logout Activities</p>
                            <p class="text-2xl font-bold text-white">{{ $activities->where('activity_type', 'logout')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Table -->
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/60">
                @if($activities->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, #e0e7ff, #ddd6fe);">
                            <svg class="w-10 h-10" style="color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Activities Yet</h3>
                        <p class="text-gray-600">Your activity log is empty. Activities will appear here once you start using the system.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background: linear-gradient(90deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1));">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date/Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Activity Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($activities as $activity)
                                    @php
                                        $activityConfig = match($activity->activity_type) {
                                            'login' => [
                                                'gradient' => 'linear-gradient(135deg, #10b981, #14b8a6)',
                                                'badge' => 'background: linear-gradient(90deg, #d1fae5, #ccfbf1); color: #047857;',
                                                'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'
                                            ],
                                            'logout' => [
                                                'gradient' => 'linear-gradient(135deg, #f59e0b, #f97316)',
                                                'badge' => 'background: linear-gradient(90deg, #fef3c7, #ffedd5); color: #b45309;',
                                                'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'
                                            ],
                                            'contribution' => [
                                                'gradient' => 'linear-gradient(135deg, #3b82f6, #6366f1)',
                                                'badge' => 'background: linear-gradient(90deg, #dbeafe, #e0e7ff); color: #4338ca;',
                                                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                            ],
                                            'loan_application' => [
                                                'gradient' => 'linear-gradient(135deg, #a855f7, #7c3aed)',
                                                'badge' => 'background: linear-gradient(90deg, #f3e8ff, #ede9fe); color: #6d28d9;',
                                                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                            ],
                                            'dashboard_access' => [
                                                'gradient' => 'linear-gradient(135deg, #6366f1, #4f46e5)',
                                                'badge' => 'background: linear-gradient(90deg, #e0e7ff, #c7d2fe); color: #4338ca;',
                                                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                                            ],
                                            default => [
                                                'gradient' => 'linear-gradient(135deg, #64748b, #94a3b8)',
                                                'badge' => 'background: linear-gradient(90deg, #f1f5f9, #e2e8f0); color: #475569;',
                                                'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                            ]
                                        };
                                    @endphp
                                    <tr class="hover:bg-purple-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: {{ $activityConfig['gradient'] }};">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activityConfig['icon'] }}"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $activity->created_at->format('M d, Y') }}</p>
                                                    <p class="text-xs text-gray-500">{{ $activity->created_at->format('g:i:s A') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize" style="{{ $activityConfig['badge'] }}">
                                                {{ str_replace('_', ' ', $activity->activity_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination if needed -->
                    @if(method_exists($activities, 'links'))
                        <div class="px-6 py-4 border-t border-gray-100" style="background: linear-gradient(90deg, rgba(241, 245, 249, 0.5), transparent);">
                            {{ $activities->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Info Card -->
            <div class="mt-8 p-5 rounded-2xl shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1)); border: 1px solid rgba(139, 92, 246, 0.2);">
                <div class="flex gap-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">About Activity Log</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            This log shows all your account activities including logins, logouts, and other important actions. Activity logs are kept for security and auditing purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
