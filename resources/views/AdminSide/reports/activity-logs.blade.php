@extends('AdminSide.layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl shadow-lg p-6 lg:p-8" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #3b82f6 100%);">
        <h1 class="text-2xl lg:text-3xl font-bold text-white drop-shadow">Activity Logs</h1>
        <p class="text-white/90 mt-1 font-medium">Track all member and system activities</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-blue-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shadow text-lg">
                <i class="fas fa-list text-white"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Total Logs</p>
                <p class="text-2xl font-bold text-blue-900">{{ number_format($totalLogs) }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-blue-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-500 flex items-center justify-center shadow text-lg">
                <i class="fas fa-calendar-day text-white"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide">Today</p>
                <p class="text-2xl font-bold text-blue-900">{{ $todayLogs }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-blue-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center shadow text-lg">
                <i class="fas fa-sign-in-alt text-white"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Login Events</p>
                <p class="text-2xl font-bold text-blue-900">{{ $loginLogs }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-blue-100 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-blue-700 mb-1">Member</label>
                <select name="member_id" class="w-full text-sm border border-blue-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Members</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>{{ $m->first_name }} {{ $m->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-blue-700 mb-1">Activity Type</label>
                <select name="type" class="w-full text-sm border border-blue-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-blue-700 mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border border-blue-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-blue-700 mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border border-blue-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg transition hover:bg-blue-700 shadow">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.reports.activity-logs') }}" class="px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">Clear</a>
            <a href="{{ route('admin.reports.activity-logs.export', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-100 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-50 transition">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-blue-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50 border-b border-blue-100">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-blue-900 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-blue-900 uppercase tracking-wide">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-blue-900 uppercase tracking-wide">Description</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-blue-900 uppercase tracking-wide">IP Address</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-blue-900 uppercase tracking-wide">Date & Time</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-blue-900 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($logs as $log)
                    @php
                        $typeColors = [
                            'login' => 'bg-green-600',
                            'logout' => 'bg-gray-500',
                            'loan' => 'bg-blue-600',
                            'contribution' => 'bg-yellow-600',
                            'dashboard_access' => 'bg-blue-600',
                        ];
                        $color = $typeColors[$log->activity_type] ?? 'bg-gray-500';
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            @if($log->member)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($log->member->first_name, 0, 1)) }}{{ strtoupper(substr($log->member->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $log->member->first_name }} {{ $log->member->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $log->member->member_id }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-sm text-slate-400">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white rounded-full {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 max-w-xs truncate">{{ $log->description }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-500 font-mono">{{ $log->ip_address ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-900">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $log->created_at->format('h:i A') }} · {{ $log->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($log->member)
                            <a href="{{ route('admin.members.show', $log->member) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <i class="fas fa-eye"></i> Details
                            </a>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-history text-2xl text-blue-600"></i>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900">No activity logs found</h3>
                                <p class="text-sm text-slate-500">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
