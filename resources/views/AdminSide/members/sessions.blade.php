@extends('AdminSide.layouts.admin')

@section('title', 'Member Sessions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-blue-600 rounded-lg p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/20">
                        <i class="fas fa-user-clock text-white"></i>
                    </span>
                    <span>Member Sessions</span>
                </h1>
                <p class="text-blue-100 mt-2 text-sm">
                    Online status is based on activity in the last {{ $onlineThresholdMinutes }} minutes.
                </p>
            </div>
            <div class="flex items-center gap-4 text-sm text-white">
                <div class="flex items-center gap-2">
                    <span class="inline-flex w-3 h-3 rounded-full bg-green-400"></span>
                    <span>Online</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex w-3 h-3 rounded-full bg-gray-400"></span>
                    <span>Offline</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-blue-600"></i>
                    <h2 class="text-base font-semibold text-slate-900">All Member Accounts</h2>
                </div>
                <p class="text-sm text-slate-600">
                    Total: <span class="font-semibold text-slate-900">{{ $members->count() }}</span>
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Account</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Last Activity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->is_online)
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-slate-700 border border-slate-200">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        Offline
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-100 text-xs font-mono text-slate-700 border border-slate-200">
                                    <i class="fas fa-id-card text-slate-500"></i>
                                    {{ $member->member_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-medium
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    <i class="fas {{ $member->status === 'active' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->last_login_at)
                                    <div class="flex items-center gap-2 text-sm text-slate-700">
                                        <i class="fas fa-sign-in-alt text-blue-600"></i>
                                        <span>{{ $member->last_login_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-400">No login recorded</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->last_activity_at)
                                    <div class="flex items-center gap-2 text-sm text-slate-700">
                                        <i class="fas fa-clock text-blue-600"></i>
                                        <span>{{ $member->last_activity_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-400">No activity</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-users text-slate-300 text-3xl"></i>
                                    <p class="text-sm text-slate-500">No members found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
