@extends('AdminSide.layouts.admin')

@section('title', 'Notifications')

@section('content')
<style>
    .notification-card {
        transition: all 0.2s ease;
    }
    .notification-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 30px -10px rgba(244, 63, 94, 0.2);
    }
    .balance-row:hover {
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.05), transparent);
    }
    .alert-urgent { border-left: 4px solid #ef4444; }
    .alert-warning { border-left: 4px solid #f59e0b; }
    .alert-info { border-left: 4px solid #3b82f6; }
    .pulse-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
</style>

<div class="space-y-6" x-data="{ showAnnouncementModal: false, announcementType: 'meeting' }">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #881337 0%, #be123c 25%, #e11d48 50%, #f43f5e 75%, #fb7185 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(251, 113, 133, 0.4);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Notifications Center</h1>
                <p class="text-rose-200 mt-1">Member balances, meetings, and alerts</p>
            </div>
            <button type="button" @click="showAnnouncementModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-rose-50 text-rose-600 text-sm font-semibold rounded-xl shadow-lg transition flex-shrink-0 order-first lg:order-last">
                <i class="fas fa-plus"></i>
                <span>New Announcement</span>
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60 hover-lift">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Members</p>
                    <p class="text-xl font-bold text-green-600">{{ $totalMembers }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60 hover-lift">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-coins text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Contributions</p>
                    <p class="text-xl font-bold text-indigo-600">₱{{ number_format($totalContributions, 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60 hover-lift">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                    <i class="fas fa-calendar text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Upcoming Meetings</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $upcomingMeetingsCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60 hover-lift">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Overdue Loans</p>
                    <p class="text-xl font-bold text-red-600">{{ $overdueLoansCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- 1. Member Account Balances -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-green-50 to-green-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <i class="fas fa-wallet text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Member Account Balances</h3>
                            <p class="text-xs text-slate-500">Real-time member balances</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Live</span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($memberBalances as $data)
                <a href="{{ route('admin.members.show', $data['member']) }}" class="balance-row p-4 flex items-center justify-between transition group block">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-xs font-semibold shadow">
                            {{ strtoupper(substr($data['member']->first_name, 0, 1)) }}{{ strtoupper(substr($data['member']->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $data['member']->first_name }} {{ $data['member']->last_name }}</p>
                            <p class="text-xs text-slate-500">{{ $data['member']->member_id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $data['net_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $data['net_balance'] >= 0 ? '₱' : '-₱' }}{{ number_format(abs($data['net_balance']), 2) }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Contrib: ₱{{ number_format($data['contributions'], 0) }}
                            </p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-green-500 group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                </a>
                @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-green-400"></i>
                    </div>
                    <p class="text-sm text-slate-500">No member data available</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200">
                <a href="{{ route('admin.amount-held.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 flex items-center justify-center gap-1">
                    View All Balances <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <!-- 2. Meeting Schedules & Election Notices -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Meetings & Elections</h3>
                            <p class="text-xs text-slate-500">Upcoming schedules</p>
                        </div>
                    </div>
                    <button @click="showAnnouncementModal = true; announcementType = 'meeting'" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-lg transition">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($meetings as $meeting)
                <div class="notification-card p-4 {{ $meeting->priority === 'urgent' ? 'alert-urgent' : ($meeting->priority === 'high' ? 'alert-warning' : 'alert-info') }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $meeting->type === 'election' ? 'bg-gradient-to-br from-purple-500 to-pink-600' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }}">
                                <i class="fas {{ $meeting->type === 'election' ? 'fa-vote-yea' : 'fa-users' }} text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-slate-900">{{ $meeting->title }}</p>
                                    @if($meeting->priority === 'urgent')
                                        <span class="px-1.5 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded pulse-badge">URGENT</span>
                                    @elseif($meeting->priority === 'high')
                                        <span class="px-1.5 py-0.5 text-xs font-bold text-yellow-700 bg-yellow-100 rounded">HIGH</span>
                                    @endif
                                </div>
                                @if($meeting->description)
                                    <p class="text-xs text-slate-600 mt-1">{{ Str::limit($meeting->description, 80) }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                                    @if($meeting->scheduled_date)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-calendar text-indigo-400"></i>
                                            {{ $meeting->scheduled_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                    @if($meeting->scheduled_time)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock text-indigo-400"></i>
                                            {{ $meeting->scheduled_time }}
                                        </span>
                                    @endif
                                    @if($meeting->location)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-indigo-400"></i>
                                            {{ $meeting->location }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.notifications.announcement.destroy', $meeting) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded transition" onclick="return confirm('Delete this announcement?')">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-check text-indigo-400"></i>
                    </div>
                    <p class="text-sm text-slate-500 mb-3">No upcoming meetings</p>
                    <button @click="showAnnouncementModal = true; announcementType = 'meeting'" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        Schedule a meeting <i class="fas fa-plus ml-1"></i>
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- 3. Loan Due Date Alerts -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-yellow-50 to-yellow-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                        <i class="fas fa-bell text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Loan Due Date Alerts</h3>
                        <p class="text-xs text-slate-500">Overdue + due within 7 days</p>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($upcomingDueLoans as $item)
                <div class="notification-card p-4 {{ $item['is_overdue'] ? 'alert-urgent bg-red-50/50' : ($item['days_until_due'] <= 7 ? 'alert-warning bg-yellow-50/30' : '') }}">
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('admin.loans.show', $item['loan']) }}" class="flex-1 min-w-0 flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $item['is_overdue'] ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-yellow-500 to-yellow-600' }}">
                                <i class="fas {{ $item['is_overdue'] ? 'fa-exclamation' : 'fa-clock' }} text-white text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-900">{{ $item['loan']->member->first_name }} {{ $item['loan']->member->last_name }}</p>
                                <p class="text-xs text-slate-500">Loan: ₱{{ number_format($item['loan']->amount ?? $item['loan']->remaining_balance, 2) }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                @if($item['is_overdue'])
                                    <span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full pulse-badge">
                                        {{ abs($item['days_until_due']) }} days overdue
                                    </span>
                                @elseif($item['days_until_due'] <= 7)
                                    <span class="px-2 py-1 text-xs font-bold text-amber-700 bg-amber-100 rounded-full">
                                        {{ $item['days_until_due'] }} days left
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-slate-600 bg-slate-100 rounded-full">
                                        {{ $item['days_until_due'] }} days left
                                    </span>
                                @endif
                                <p class="text-xs text-slate-500 mt-1">Due: {{ $item['due_date']->format('M d, Y') }}</p>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-amber-500 group-hover:translate-x-0.5 transition-all flex-shrink-0"></i>
                        </a>
                        @if($item['is_overdue'])
                        <form action="{{ route('admin.notifications.loan.send-demand', $item['loan']) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Send formal payment demand with legal notice to this borrower?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 rounded-lg shadow-sm transition">
                                <i class="fas fa-gavel"></i> Send Demand
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                    </div>
                    <p class="text-sm text-slate-500">No upcoming loan due dates</p>
                    <p class="text-xs text-slate-400 mt-1">All loans are in good standing</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 4. New Cooperative Offerings -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-pink-50 to-pink-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center shadow-lg shadow-pink-500/20">
                            <i class="fas fa-gift text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">New Offerings</h3>
                            <p class="text-xs text-slate-500">Latest cooperative products & services</p>
                        </div>
                    </div>
                    <button @click="showAnnouncementModal = true; announcementType = 'offering'" class="p-2 text-pink-600 hover:bg-pink-100 rounded-lg transition">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($offerings as $offering)
                <div class="notification-card p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center">
                                <i class="fas fa-star text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $offering->title }}</p>
                                @if($offering->description)
                                    <p class="text-xs text-slate-600 mt-1">{{ Str::limit($offering->description, 100) }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $offering->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('admin.notifications.announcement.destroy', $offering) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded transition" onclick="return confirm('Delete this offering?')">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bullhorn text-pink-400"></i>
                    </div>
                    <p class="text-sm text-slate-500 mb-3">No current offerings</p>
                    <button @click="showAnnouncementModal = true; announcementType = 'offering'" class="text-sm font-medium text-pink-600 hover:text-pink-700">
                        Add new offering <i class="fas fa-plus ml-1"></i>
                    </button>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 5. General Announcements -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-slate-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <i class="fas fa-bullhorn text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">General Announcements</h3>
                            <p class="text-xs text-slate-500">Broadcast messages to all members</p>
                        </div>
                    </div>
                    <button @click="showAnnouncementModal = true; announcementType = 'general'" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($generalAnnouncements as $announcement)
                <div class="notification-card p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <i class="fas fa-bullhorn text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $announcement->title }}</p>
                                @if($announcement->description)
                                    <p class="text-xs text-slate-600 mt-1">{{ Str::limit($announcement->description, 100) }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $announcement->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('admin.notifications.announcement.destroy', $announcement) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded transition" onclick="return confirm('Delete this announcement?')">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bullhorn text-blue-400"></i>
                    </div>
                    <p class="text-sm text-slate-500 mb-3">No general announcements</p>
                    <button @click="showAnnouncementModal = true; announcementType = 'general'" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Create announcement <i class="fas fa-plus ml-1"></i>
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div x-show="showAnnouncementModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] overflow-y-auto"
         aria-modal="true">
        <div class="flex min-h-screen items-start justify-center p-4 py-8">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAnnouncementModal = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[calc(100vh-4rem)] my-8 overflow-hidden"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <form action="{{ route('admin.notifications.announcement.store') }}" method="POST" class="flex flex-col min-h-0 flex-1">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-rose-50 to-pink-50 flex-shrink-0">
                        <h3 class="text-lg font-semibold text-slate-900">Create Announcement</h3>
                    </div>
                    <div class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                            <select name="type" x-model="announcementType" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300">
                                <option value="meeting">Meeting</option>
                                <option value="election">Election Notice</option>
                                <option value="offering">New Offering</option>
                                <option value="general">General Announcement</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                            <input type="text" name="title" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300" placeholder="Announcement title">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300" placeholder="Details about the announcement"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4" x-show="announcementType === 'meeting' || announcementType === 'election'">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                                <input type="date" name="scheduled_date" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Time</label>
                                <input type="time" name="scheduled_time" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300">
                            </div>
                        </div>
                        <div x-show="announcementType === 'meeting' || announcementType === 'election'">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Location</label>
                            <input type="text" name="location" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-300" placeholder="e.g., Conference Room, Online via Zoom">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Priority</label>
                            <div class="flex gap-2">
                                <label class="flex-1">
                                    <input type="radio" name="priority" value="low" class="sr-only peer">
                                    <div class="px-3 py-2 text-center text-sm font-medium border border-slate-200 rounded-lg cursor-pointer peer-checked:bg-slate-100 peer-checked:border-slate-400 transition">Low</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="priority" value="normal" checked class="sr-only peer">
                                    <div class="px-3 py-2 text-center text-sm font-medium border border-slate-200 rounded-lg cursor-pointer peer-checked:bg-blue-100 peer-checked:border-blue-400 peer-checked:text-blue-700 transition">Normal</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="priority" value="high" class="sr-only peer">
                                    <div class="px-3 py-2 text-center text-sm font-medium border border-slate-200 rounded-lg cursor-pointer peer-checked:bg-amber-100 peer-checked:border-amber-400 peer-checked:text-amber-700 transition">High</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="priority" value="urgent" class="sr-only peer">
                                    <div class="px-3 py-2 text-center text-sm font-medium border border-slate-200 rounded-lg cursor-pointer peer-checked:bg-red-100 peer-checked:border-red-400 peer-checked:text-red-700 transition">Urgent</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 bg-slate-50 border-t border-slate-200 flex-shrink-0">
                        <button type="button" @click="showAnnouncementModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-black bg-gradient-to-r from-rose-500 to-pink-600 rounded-xl hover:from-rose-600 hover:to-pink-700 transition shadow-md">Create Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
