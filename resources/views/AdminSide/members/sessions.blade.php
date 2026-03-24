@extends('AdminSide.layouts.admin')

@section('title', 'Member Sessions')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white flex items-center gap-4 drop-shadow-lg">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 shadow-lg">
                        <i class="fas fa-user-clock text-white text-2xl"></i>
                    </span>
                    <span>Member Sessions</span>
                </h1>
                <p class="text-blue-100/90 mt-3 text-base font-medium">
                    Online status is based on activity in the last <span class="font-bold">{{ $onlineThresholdMinutes }}</span> minutes.
                </p>
            </div>
            <div class="flex items-center gap-6 text-base text-white">
                <div class="flex items-center gap-2">
                    <span class="inline-flex w-4 h-4 rounded-full bg-green-400 border-2 border-white"></span>
                    <span class="font-semibold">Online</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex w-4 h-4 rounded-full bg-gray-400 border-2 border-white"></span>
                    <span class="font-semibold">Offline</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-blue-100 bg-blue-50/80">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users text-blue-700"></i>
                    <h2 class="text-lg font-bold text-blue-900 tracking-tight">All Member Accounts</h2>
                </div>
                <p class="text-base text-blue-700">
                    Total: <span class="font-extrabold text-blue-900">{{ $members->count() }}</span>
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-blue-50/80 border-b border-blue-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Account</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Last Activity</th>
                    </tr>
                </thead>
                <tbody class="bg-white/80 divide-y divide-blue-100">
                    @forelse($members as $member)
                        <tr class="hover:bg-blue-100/60 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->is_online)
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-slate-700 border border-slate-300 shadow-sm">
                                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                                        Offline
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-blue-200 flex items-center justify-center text-blue-900 text-lg font-extrabold shadow-md">
                                        {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-blue-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                                        <p class="text-xs text-blue-600">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-xs font-mono text-blue-800 border border-blue-200 shadow-sm">
                                    <i class="fas fa-id-card text-blue-400"></i>
                                    {{ $member->member_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold shadow-sm
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                                    <i class="fas {{ $member->status === 'active' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->last_login_at)
                                    <div class="flex items-center gap-2 text-sm text-blue-800">
                                        <i class="fas fa-sign-in-alt text-blue-500"></i>
                                        <span>{{ $member->last_login_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-blue-300">No login recorded</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->last_activity_at)
                                    <div class="flex items-center gap-2 text-sm text-blue-800">
                                        <i class="fas fa-clock text-blue-500"></i>
                                        <span>{{ $member->last_activity_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-blue-300">No activity</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-users text-3xl text-blue-400"></i>
                                    </div>
                                    <p class="text-base font-semibold text-blue-900">No members found.</p>
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
