@extends('AdminSide.layouts.admin')

@section('title', 'Members Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-blue-900 tracking-tight drop-shadow">Members Management</h2>
            <p class="text-blue-700/80 mt-1">Manage cooperative members and their information</p>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" placeholder="Search members..." class="w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm bg-blue-50/80 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-300 transition shadow-sm backdrop-blur">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-400 text-sm"></i>
            </div>
            <a href="{{ route('admin.member-registration.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition backdrop-blur">
                <i class="fas fa-plus"></i>
                <span class="hidden sm:inline">Add Member</span>
            </a>
        </div>
    </div>

    <!-- Member Growth Chart -->
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-lg border border-blue-100 p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Member Growth</h3>
                    <p class="text-sm text-slate-500">Registration trends over time</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.members.index') }}" class="flex gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
                <select name="group_by" class="px-3 py-2 text-sm bg-indigo-50 border-0 rounded-lg text-indigo-700 focus:ring-2 focus:ring-indigo-500" onchange="this.form.submit()">
                    <option value="day" {{ request('group_by', 'day') == 'day' ? 'selected' : '' }}>Daily</option>
                    <option value="month" {{ request('group_by') == 'month' ? 'selected' : '' }}>Monthly</option>
                    <option value="year" {{ request('group_by') == 'year' ? 'selected' : '' }}>Yearly</option>
                </select>
                <select name="year" class="px-3 py-2 text-sm bg-blue-50 border-0 rounded-lg text-blue-700 focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    @for($y = now()->year - 5; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
        <div class="h-48">
            <canvas id="membersJoinedChart"></canvas>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow-lg border border-blue-100 hover:shadow-xl transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-blue-700"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total</p>
                    <p class="text-xl font-bold text-slate-900">{{ $members->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow-lg border border-blue-100 hover:shadow-xl transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center shadow-md">
                    <i class="fas fa-check text-green-700"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Active</p>
                    <p class="text-xl font-bold text-emerald-600">{{ $members->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow-lg border border-blue-100 hover:shadow-xl transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center shadow-md">
                    <i class="fas fa-clock text-yellow-700"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending</p>
                    <p class="text-xl font-bold text-amber-600">{{ $members->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow-lg border border-blue-100 hover:shadow-xl transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center shadow-md">
                    <i class="fas fa-calendar-plus text-purple-700"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">This Month</p>
                    <p class="text-xl font-bold text-purple-600">{{ $members->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-blue-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-blue-50/80">
            <h3 class="font-semibold text-blue-900">All Members</h3>
            <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-wrap gap-2">
                <select name="status" class="px-3 py-2 text-sm bg-white border border-indigo-100 rounded-lg text-slate-700">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <select name="sort" class="px-3 py-2 text-sm bg-white border border-indigo-100 rounded-lg text-slate-700">
                    <option value="join_date" {{ request('sort') == 'join_date' ? 'selected' : '' }}>Date</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-md transition">
                    Apply
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Contact</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Join Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $member)
                    <tr class="hover:bg-blue-100/80 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(!empty($member->profile_photo))
                                    <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="Profile Photo" class="w-9 h-9 rounded-lg object-cover border border-gray-300 shadow-lg">
                                @else
                                    <div class="w-9 h-9 rounded-lg bg-blue-200 flex items-center justify-center text-blue-900 text-xs font-bold shadow-md">
                                        {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-blue-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                                    <p class="text-xs text-blue-500">{{ $member->member_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-blue-900">{{ $member->email }}</p>
                            <p class="text-xs text-blue-500">{{ $member->phone }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = match($member->status) {
                                    'active' => 'from-green-500 to-green-600',
                                    'pending' => 'from-yellow-400 to-yellow-400',
                                    'inactive' => 'from-red-400 to-red-500',
                                    default => 'from-slate-500 to-slate-500'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full shadow-sm
                                {{
                                    $member->status === 'active' ? 'bg-green-100 text-green-800 border border-green-300' :
                                    ($member->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' :
                                    ($member->status === 'inactive' ? 'bg-red-100 text-red-800 border border-red-300' :
                                    'bg-slate-100 text-slate-800 border border-slate-300'))
                                }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-blue-700">
                            {{ $member->join_date ? $member->join_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.members.show', $member) }}" class="p-2 text-blue-700 hover:text-white hover:bg-blue-500 rounded-lg transition shadow-sm" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}" class="p-2 text-amber-600 hover:text-white hover:bg-amber-400 rounded-lg transition shadow-sm" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-2 text-red-600 hover:text-white hover:bg-red-500 rounded-lg transition shadow-sm js-confirm-delete" data-confirm-message="Delete this member?" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl text-blue-400"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-blue-900 mb-1">No members found</h3>
                            <p class="text-sm text-slate-500 mb-4">Get started by adding a new member</p>
                            <a href="{{ route('admin.member-registration.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-lg shadow-lg shadow-indigo-500/25 transition">
                                <i class="fas fa-plus"></i>Add Member
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-gradient-to-r from-slate-50 to-indigo-50/30">
            {{ $members->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    @php
        $labelsData = $joinLabels ?? [];
        $countsData = $joinCounts ?? [];
        $activeCountsData = $activeJoinCounts ?? [];
    @endphp
    var joinLabels = {!! json_encode($labelsData) !!};
    var joinCounts = {!! json_encode($countsData) !!};
    var activeJoinCounts = {!! json_encode($activeCountsData) !!};
    
    var ctx = document.getElementById('membersJoinedChart').getContext('2d');
    var gradient1 = ctx.createLinearGradient(0, 0, 0, 200);
    gradient1.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradient1.addColorStop(1, 'rgba(99, 102, 241, 0.01)');
    var gradient2 = ctx.createLinearGradient(0, 0, 0, 200);
    gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradient2.addColorStop(1, 'rgba(16, 185, 129, 0.01)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: joinLabels,
            datasets: [{
                label: 'Total Members',
                data: joinCounts,
                borderColor: '#6366f1',
                backgroundColor: gradient1,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }, {
                label: 'Active Members',
                data: activeJoinCounts,
                borderColor: '#10b981',
                backgroundColor: gradient2,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle' } } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(99, 102, 241, 0.1)' } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
            }
        }
    });
</script>
@endsection
