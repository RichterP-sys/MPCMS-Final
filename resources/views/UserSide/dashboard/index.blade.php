@extends('UserSide.layouts.app')

@section('title', 'Member Dashboard')

@section('content')
@php
    $totalContributions = $member->contributions->sum('amount');
    $memberSinceDays = $member->join_date ? $member->join_date->diffInDays(now()) : 0;
    $pendingLoans = $activeLoans->where('status', 'pending')->count();
    $approvedLoans = $activeLoans->where('status', 'approved')->count();
@endphp

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
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
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
    .card-gradient-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
    }
    .card-gradient-orange {
        background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);
    }
    .card-gradient-purple {
        background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
    }
    .card-gradient-green {
        background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
    }
    .hero-gradient {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #ec4899 100%);
    }
    .btn-gradient-orange {
        background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 50%, #f0fdfa 100%); backdrop-filter: blur(2px);">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(236, 72, 153, 0.1));"></div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full animate-float" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.15));"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 rounded-full animate-float-delayed" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.15));"></div>
        <div class="absolute -bottom-20 right-10 w-64 h-64 rounded-full animate-pulse-slow" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.1), rgba(236, 72, 153, 0.1));"></div>
        </div>
        
    <div class="relative z-10">
    <!-- Navigation Bar -->
    <nav class="bg-white/60 shadow-xl backdrop-blur-2xl border-b border-blue-300/40 sticky top-0 z-30 rounded-b-3xl" style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);">
        <div class="absolute bottom-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #7c3aed, #a855f7, #ec4899, #f97316);"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold" style="background: linear-gradient(90deg, #7c3aed, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">MPCMS</p>
                        <p class="text-xs text-slate-500">Member Portal</p>
                    </div>
                </a>
                
                <div class="flex items-center gap-2">
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($activityLogs->count() > 0)
                                <span class="absolute top-1.5 right-1.5 h-2 w-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                        
                        <!-- Notifications Panel -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-xl bg-white border border-slate-200 shadow-lg z-50"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-800">Notifications</h3>
                                <span class="text-xs text-blue-900">{{ $activityLogs->count() }} recent · <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600">Refresh</a></span>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($activityLogs->take(5) as $activity)
                                    <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0">
                                        <div class="flex items-start gap-3">
                                            <div class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0
                                                @if($activity->activity_type === 'login') bg-emerald-50
                                                @elseif($activity->activity_type === 'logout') bg-red-50
                                                @elseif($activity->activity_type === 'contribution') bg-blue-50
                                                @else bg-slate-100 @endif">
                                                @if($activity->activity_type === 'login')
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                                @elseif($activity->activity_type === 'logout')
                                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @endif
                                </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-blue-900 capitalize">{{ str_replace('_', ' ', $activity->activity_type) }}</p>
                                                <p class="text-xs text-blue-900 truncate">{{ $activity->description }}</p>
                                                <p class="text-xs text-blue-800 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        <p class="text-sm text-blue-900">No notifications</p>
                                </div>
                                @endforelse
                            </div>
                            @if($activityLogs->count() > 0)
                                <div class="px-4 py-3 border-t border-slate-100">
                                    <a href="{{ route('user.activity-log') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center justify-center gap-1">
                                        View all activity
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-600 hover:bg-blue-100 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <div class="h-9 w-9 rounded-full flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #26667F, #124170);">
                                <span class="text-white font-bold text-base">{{ substr($member->first_name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block text-base font-semibold text-blue-900">{{ $member->first_name }}</span>
                            <svg class="w-4 h-4 text-blue-700 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <!-- Profile Slideout -->
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="translate-x-8 opacity-0 scale-95" x-transition:enter-end="translate-x-0 opacity-100 scale-100" x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100 scale-100" x-transition:leave-end="translate-x-8 opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 rounded-2xl bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 shadow-2xl ring-1 ring-blue-200/40 z-50 flex flex-col border border-blue-200/60 backdrop-blur-xl"
                                style="min-width: 320px; max-height: 420px; overflow: hidden;">
                            <div class="flex items-center gap-4 px-6 py-6 border-b border-blue-200/40 bg-blue-100/90 rounded-t-2xl">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#26667F] to-[#124170] flex items-center justify-center text-white font-bold text-xl shadow-lg border-2 border-white/30">
                                    {{ substr($member->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-base font-bold text-blue-900 drop-shadow">{{ $member->first_name }} {{ $member->last_name }}</p>
                                    <p class="text-xs text-blue-700/90">{{ $member->email }}</p>
                                </div>
                                <button @click="open = false" class="ml-auto text-blue-700 hover:text-blue-900 transition text-xl focus:outline-none"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="py-4 px-6 space-y-2">
                                    <a href="{{ route('user.loans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        My Loans
                                    </a>
                                    <a href="{{ route('user.report') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Reports
                                    </a>
                                </div>
                                <div class="py-4 px-6 border-t border-blue-200/40 bg-blue-50/80 rounded-b-2xl">
                                    <form method="POST" action="{{ route('user.logout') }}">
                                        @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-base font-medium text-white bg-red-600 hover:bg-red-700 transition text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden animate-gradient-x" style="background: linear-gradient(135deg, #6366f1 0%, #7c3aed 30%, #a855f7 60%, #ec4899 90%); background-size: 200% 200%; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10); border-radius: 0 0 2rem 2rem;">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.08&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl animate-float" style="background: rgba(255,255,255,0.1);"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full blur-3xl animate-float-delayed" style="background: rgba(255,255,255,0.1);"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                            <svg class="w-3.5 h-3.5" style="color: #6ee7b7;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified Member
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                            Member since {{ $member->join_date ? $member->join_date->format('M Y') : 'N/A' }}
                        </span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white drop-shadow mb-2 tracking-tight">
                        Welcome back, {{ $member->first_name }}!
                    </h1>
                    <p class="text-base sm:text-lg" style="color: rgba(255,255,255,0.92); text-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                        Here's your account overview for today.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('user.loans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all" style="background: linear-gradient(135deg, #fbbf24, #f97316);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Apply for Loan
                    </a>
                    <a href="{{ route('user.report') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold text-sm border border-white/30 hover:bg-white/20 transition-all" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ lastUpdated: '{{ now()->toIso8601String() }}' }">
        <!-- Last Updated & Refresh -->
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs text-slate-500">Last updated: <span x-text="new Date(lastUpdated).toLocaleString()"></span></p>
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh
            </a>
        </div>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
            <!-- Net Account Balance -->
            <div class="relative overflow-hidden rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all {{ ($netBalance ?? 0) >= 0 ? 'card-gradient-green' : '' }}" style="{{ ($netBalance ?? 0) < 0 ? 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);' : 'background: rgba(255,255,255,0.25); backdrop-filter: blur(8px);' }} border: 1.5px solid #e0e7ff;">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 rounded-full translate-y-6 -translate-x-6" style="background: rgba(255,255,255,0.1);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-medium text-white px-2.5 py-1 rounded-full" style="background: rgba(255,255,255,0.2);">Net</span>
                    </div>
                    <p class="text-sm mb-1" style="color: rgba(255,255,255,0.9);">Net Account Balance</p>
                    <p class="text-2xl font-bold text-white">₱{{ number_format($netBalance ?? 0, 2) }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.75);">Contributions − Outstanding Loans</p>
                </div>
            </div>
            <!-- Total Contributions -->
            <div class="relative overflow-hidden rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); border: 1.5px solid #dbeafe;">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 rounded-full translate-y-6 -translate-x-6" style="background: rgba(255,255,255,0.1);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-xs font-medium text-white px-2.5 py-1 rounded-full" style="background: rgba(255,255,255,0.2);">Active</span>
                    </div>
                    <p class="text-sm mb-1" style="color: rgba(219, 234, 254, 1);">Total Contributions</p>
                    <p class="text-2xl font-bold text-white">₱{{ number_format($totalContributions, 2) }}</p>
                </div>
            </div>

            <!-- Active Loans -->
            <div class="relative overflow-hidden rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%); border: 1.5px solid #fbcfe8;">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 rounded-full translate-y-6 -translate-x-6" style="background: rgba(255,255,255,0.1);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        @if($pendingLoans > 0)
                            <span class="text-xs font-medium text-white px-2.5 py-1 rounded-full" style="background: rgba(255,255,255,0.2);">{{ $pendingLoans }} Pending</span>
                        @endif
                    </div>
                    <p class="text-sm mb-1" style="color: rgba(255, 237, 213, 1);">Active Loans</p>
                    <p class="text-2xl font-bold text-white">{{ $activeLoans->count() }}</p>
                        </div>
                    </div>

            <!-- Days as Member -->
            <div class="relative overflow-hidden rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); border: 1.5px solid #ede9fe;">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 rounded-full translate-y-6 -translate-x-6" style="background: rgba(255,255,255,0.1);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                            </div>
                    <p class="text-sm mb-1" style="color: rgba(243, 232, 255, 1);">Days as Member</p>
                    <p class="text-2xl font-bold text-white">{{ $memberSinceDays }}</p>
                                </div>
                            </div>

            <!-- Account Status -->
            <div class="relative overflow-hidden rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); border: 1.5px solid #d1fae5;">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-8 translate-x-8" style="background: rgba(255,255,255,0.15);"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 rounded-full translate-y-6 -translate-x-6" style="background: rgba(255,255,255,0.1);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm mb-1" style="color: rgba(209, 250, 229, 1);">Account Status</p>
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-white animate-pulse"></span>
                        <p class="text-lg font-bold text-white">Verified</p>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Start Guide Card -->
            <div class="lg:col-span-3 bg-white/60 backdrop-blur-xl border-2 border-blue-200 rounded-2xl p-8 shadow-xl" style="box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.08);">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM5 9a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Getting Started</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <a href="{{ route('user.loans.index') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-blue-100 transition">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-200 text-blue-700 flex items-center justify-center text-sm font-bold">1</div>
                                <div>
                                    <p class="font-medium text-blue-900 text-sm">View Your Loans</p>
                                    <p class="text-xs text-blue-700">Check status and details</p>
                                </div>
                            </a>
                            <a href="{{ route('user.receipts.index') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-blue-100 transition">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-200 text-blue-700 flex items-center justify-center text-sm font-bold">2</div>
                                <div>
                                    <p class="font-medium text-blue-900 text-sm">Download Receipts</p>
                                    <p class="text-xs text-blue-700">All payment receipts</p>
                                </div>
                            </a>
                            <a href="{{ route('user.report') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-blue-100 transition">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-200 text-blue-700 flex items-center justify-center text-sm font-bold">3</div>
                                <div>
                                    <p class="font-medium text-blue-900 text-sm">View Contributions</p>
                                    <p class="text-xs text-blue-700">Financial history & reports</p>
                                </div>
                            </a>
                            <a href="{{ route('user.report') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-blue-100 transition">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-200 text-blue-700 flex items-center justify-center text-sm font-bold">4</div>
                                <div>
                                    <p class="font-medium text-blue-900 text-sm">View Reports</p>
                                    <p class="text-xs text-blue-700">Financial overview</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Contributions -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background: linear-gradient(90deg, rgba(219, 234, 254, 0.5), rgba(224, 231, 255, 0.5));">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">Recent Contributions</h3>
                            <p class="text-sm text-slate-500">Your latest contribution history</p>
                        </div>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background: #e0e7ff; color: #4f46e5;">{{ $recentContributions->count() }} records</span>
                    </div>
                    @if($recentContributions->count() > 0)
                        <div class="divide-y divide-slate-100">
                                @foreach($recentContributions as $contribution)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-blue-50/50 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Contribution Payment</p>
                                            <p class="text-xs text-slate-500">{{ $contribution->created_at->format('M d, Y • g:i A') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold" style="color: #10b981;">+₱{{ number_format($contribution->amount, 2) }}</span>
                                </div>
                                @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="h-14 w-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <p class="text-slate-500 text-sm">No contributions yet</p>
                        </div>
                    @endif
                </div>

                <!-- Active Loans -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(255, 237, 213, 0.5), rgba(252, 231, 243, 0.5));">
                        <h3 class="text-base font-semibold text-slate-800">Active Loans</h3>
                        <p class="text-sm text-slate-500">Track your loan applications</p>
                    </div>
                    @if($activeLoans->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($activeLoans->take(5) as $loan)
                                @php
                                    $loanIconBg = match($loan->status) {
                                        'approved' => 'linear-gradient(135deg, #34d399, #14b8a6)',
                                        'pending' => 'linear-gradient(135deg, #fbbf24, #f97316)',
                                        default => 'linear-gradient(135deg, #f87171, #fb7185)'
                                    };
                                    $loanBadgeStyle = match($loan->status) {
                                        'approved' => 'background: linear-gradient(90deg, #d1fae5, #ccfbf1); color: #047857;',
                                        'pending' => 'background: linear-gradient(90deg, #fef3c7, #ffedd5); color: #b45309;',
                                        default => 'background: linear-gradient(90deg, #fee2e2, #fce7f3); color: #dc2626;'
                                    };
                                @endphp
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-orange-50/50 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm" style="background: {{ $loanIconBg }};">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                            <div>
                                            <p class="text-sm font-medium text-slate-800">Loan #{{ $loan->id }}</p>
                                            <p class="text-xs text-slate-500">{{ $loan->created_at->format('M d, Y') }}</p>
                                        </div>
                                                </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-slate-800">₱{{ number_format($loan->amount, 2) }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize" style="{{ $loanBadgeStyle }}">
                                            {{ $loan->status }}
                                        </span>
                                                </div>
                                            </div>
                            @endforeach
                        </div>
                        @if($activeLoans->count() > 5)
                            <div class="px-6 py-3 border-t border-slate-100 text-center" style="background: linear-gradient(90deg, #f8fafc, transparent);">
                                <a href="{{ route('user.loans.index') }}" class="text-sm font-semibold" style="color: #6366f1;">View all loans</a>
                            </div>
                        @endif
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="h-14 w-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-slate-500 text-sm mb-3">No active loans</p>
                            <a href="{{ route('user.loans.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold" style="color: #6366f1;">
                                <svg class="w-4 h-4" style="color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Apply for your first loan
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Repayments & Receipts -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.1));">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-slate-800">Recent Repayments</h3>
                            @if($recentRepayments->count() > 0)
                                <a href="{{ route('user.receipts.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    View all
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    @if($recentRepayments->count() > 0)
                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                            @foreach($recentRepayments as $repayment)
                                @php
                                    $loan = $repayment->loan;
                                @endphp
                                <div class="px-5 py-4 hover:bg-emerald-50/50 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-lg flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Loan #{{ $loan->id }} Repayment</p>
                                                <p class="text-xs text-slate-500">{{ $repayment->payment_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-emerald-600">₱{{ number_format($repayment->amount, 2) }}</p>
                                            @if($repayment->receipt_number)
                                                <span class="text-xs text-emerald-600 font-semibold">{{ $repayment->receipt_number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background: linear-gradient(135deg, #d1fae5, #ccfbf1); color: #047857;">
                                            {{ ucfirst($repayment->payment_method) }}
                                        </span>
                                        @if($repayment->receipt_number)
                                            <a href="{{ route('user.receipts.show', $repayment->id) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                                View Receipt
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-5 py-3 border-t border-slate-100" style="background: linear-gradient(90deg, rgba(241, 245, 249, 1), transparent);">
                            <a href="{{ route('user.receipts.index') }}" class="text-sm font-semibold flex items-center justify-center gap-1 text-emerald-600 hover:text-emerald-700">
                                View all repayments
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    @else
                        <div class="px-5 py-10 text-center">
                            <div class="h-12 w-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-slate-500 text-sm">No repayments yet</p>
                            <p class="text-xs text-slate-400 mt-1">Your repayments and receipts will appear here</p>
                        </div>
                    @endif
                </div>
                </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-lg p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('user.loans.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all group">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-blue-700">View All Loans</p>
                                <p class="text-xs text-slate-500">Manage your loan history</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('user.report') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-cyan-50 border border-transparent hover:border-cyan-100 transition-all group">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all" style="background: linear-gradient(135deg, #06b6d4, #14b8a6);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-cyan-700">Financial Reports</p>
                                <p class="text-xs text-slate-500">View detailed analytics</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-cyan-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('user.activity-log') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 border border-transparent hover:border-purple-100 transition-all group">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-purple-700">Activity Log</p>
                                <p class="text-xs text-slate-500">View account activity</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-violet-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('user.activity-log', ['filter' => 'access']) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all group">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all" style="background: linear-gradient(135deg, #10b981, #14b8a6);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-emerald-700">Access Logs</p>
                                <p class="text-xs text-slate-500">Login & logout history</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('user.mortuary.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all group">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all" style="background: linear-gradient(135deg, #f97316, #ec4899);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5A4.5 4.5 0 0112 7h0a4.5 4.5 0 015 4.5c0 2.485-2 4-5 6.5-3-2.5-5-4.015-5-6.5z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-rose-700">Pay Mortuary Aid</p>
                                <p class="text-xs text-slate-500">Submit your mortuary contribution</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                                            </div>

                <!-- Recent Activity -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100" style="background: linear-gradient(90deg, rgba(167, 139, 250, 0.1), rgba(192, 132, 252, 0.1));">
                        <h3 class="text-base font-semibold text-slate-800">Recent Activity</h3>
                                            </div>
                    @if($activityLogs->count() > 0)
                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                            @foreach($activityLogs->take(5) as $activity)
                                @php
                                    $activityBg = match($activity->activity_type) {
                                        'login' => 'linear-gradient(135deg, #34d399, #14b8a6)',
                                        'logout' => 'linear-gradient(135deg, #f87171, #fb7185)',
                                        'contribution' => 'linear-gradient(135deg, #60a5fa, #6366f1)',
                                        default => 'linear-gradient(135deg, #94a3b8, #64748b)'
                                    };
                                @endphp
                                <div class="px-5 py-3 flex items-start gap-3 hover:bg-purple-50/50 transition">
                                    <div class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background: {{ $activityBg }};">
                                        @if($activity->activity_type === 'login')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                        @elseif($activity->activity_type === 'logout')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                        </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800 capitalize">{{ str_replace('_', ' ', $activity->activity_type) }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $activity->description }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-5 py-3 border-t border-slate-100" style="background: linear-gradient(90deg, rgba(241, 245, 249, 1), transparent);">
                            <a href="{{ route('user.activity-log') }}" class="text-sm font-semibold flex items-center justify-center gap-1" style="color: #7c3aed;">
                                View all activity
                                <svg class="w-4 h-4" style="color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    @else
                        <div class="px-5 py-10 text-center">
                            <p class="text-slate-500 text-sm">No recent activity</p>
                        </div>
                    @endif
                </div>

                <!-- Member Card -->
                <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-2xl" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); box-shadow: 0 8px 32px 0 rgba(124, 58, 237, 0.18);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -translate-y-12 translate-x-12" style="background: rgba(255,255,255,0.1);"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 rounded-full translate-y-8 -translate-x-8" style="background: rgba(255,255,255,0.1);"></div>
                    <div class="absolute top-1/2 right-4 w-16 h-16 rounded-full blur-xl" style="background: rgba(244, 114, 182, 0.3);"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-14 w-14 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #fbbf24, #f97316); box-shadow: 0 0 0 4px rgba(255,255,255,0.2);">
                                <span class="text-white font-bold text-lg">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                <p class="font-bold text-lg">{{ $member->first_name }} {{ $member->last_name }}</p>
                                <p class="text-sm" style="color: rgba(233, 213, 255, 1);">{{ $member->email }}</p>
                                            </div>
                                        </div>
                        <div class="space-y-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.2);">
                            <div class="flex justify-between text-sm">
                                <span style="color: rgba(233, 213, 255, 1);">Member ID</span>
                                <span class="font-semibold px-2 py-0.5 rounded" style="background: rgba(255,255,255,0.2);">{{ $member->member_id }}</span>
                                        </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: rgba(233, 213, 255, 1);">Phone</span>
                                <span class="font-medium">{{ $member->phone ?? 'Not set' }}</span>
                                    </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: rgba(233, 213, 255, 1);">Status</span>
                                <span class="inline-flex items-center gap-1.5 font-semibold px-2 py-0.5 rounded-full" style="background: rgba(52, 211, 153, 0.2); color: #6ee7b7;">
                                    <span class="h-2 w-2 rounded-full animate-pulse" style="background: #34d399;"></span>
                                    Active
                                </span>
                                </div>
                        </div>
                        </div>
                </div>
            </div>
             </div>
        </div>
    </div>
</div>
@endsection
