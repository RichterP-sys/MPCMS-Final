@extends('AdminSide.layouts.admin')

@section('title', 'Member Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="text-sm mb-2">
                <ol class="flex items-center gap-2 text-slate-500">
                    <li><a href="{{ route('admin.members.index') }}" class="hover:text-blue-600 transition">Members</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-slate-900 font-medium">Details</li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-slate-900">Member Details</h2>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.members.edit', $member) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm transition">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    @php
        $status = strtolower($member->status ?? 'inactive');
        $statusClasses = match($status) {
            'active' => 'bg-emerald-50 text-emerald-700',
            'pending' => 'bg-amber-50 text-amber-700',
            'inactive' => 'bg-slate-50 text-slate-700',
            default => 'bg-red-50 text-red-700'
        };
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-id-badge text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</p>
                    <span class="inline-flex items-center mt-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClasses }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Join Date</p>
                    <p class="text-sm font-semibold text-slate-900">{{ optional($member->join_date)->format('M d, Y') }}</p>
                    <p class="text-xs text-slate-500">{{ optional($member->join_date)->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center">
                    <i class="fas fa-envelope text-cyan-600"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $member->email ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-4">Profile</h3>
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($member->first_name,0,1)) }}{{ strtoupper(substr($member->last_name,0,1)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                    <p class="text-sm text-slate-500"><i class="fas fa-phone mr-1"></i>{{ $member->phone ?? '—' }}</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Member ID</span>
                    <span class="font-medium text-slate-900">{{ $member->member_id ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-slate-500">Address</span>
                    <span class="font-medium text-slate-900 text-right max-w-[180px] truncate">{{ $member->address ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-4">Personal Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Full Name</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $member->email ?? '—' }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Phone</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $member->phone ?? '—' }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</p>
                    <span class="inline-flex items-center mt-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClasses }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ optional($member->created_at)->format('M d, Y') }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Last Updated</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ optional($member->updated_at)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
