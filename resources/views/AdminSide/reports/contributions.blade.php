@extends('AdminSide.layouts.admin')

@section('title', 'Contributions Report')

@php
    $contribChartLabelsJson = json_encode($contribChartLabels);
    $contribChartDataJson = json_encode($contribChartData);
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 25%, #6366f1 50%, #818cf8 75%, #a5b4fc 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(129, 140, 248, 0.4);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-blue-200 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">Contributions Report</h1>
                </div>
                <p class="text-blue-200">Detailed analysis of all member contributions</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.contributions.export', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl backdrop-blur transition">
                    <i class="fas fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl backdrop-blur transition">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-coins text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Total Approved</p>
                    <p class="text-xl font-bold text-blue-600">₱{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Approved</p>
                    <p class="text-xl font-bold text-green-600">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">This Month</p>
                    <p class="text-lg font-bold text-indigo-600">₱{{ number_format($thisMonthAmount, 2) }}</p>
                    @if($growthPercent != 0)
                    <p class="text-xs {{ $growthPercent > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        <i class="fas fa-arrow-{{ $growthPercent > 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($growthPercent) }}%
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                <i class="fas fa-chart-area text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900">Monthly Contribution Trend</h3>
                <p class="text-xs text-slate-500">Approved amounts over the last 6 months</p>
            </div>
        </div>
        <div style="height: 220px;">
            <canvas id="contribChart"></canvas>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-medium text-slate-600 mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Member name or ID..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                </div>
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            @if($contributionTypes->count() > 0)
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">Type</label>
                <select name="type" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach($contributionTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/25 transition hover:from-blue-600 hover:to-indigo-700">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.reports.contributions') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Clear</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">#</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Amount</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Type</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contributions as $contribution)
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $contribution->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($contribution->member->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($contribution->member->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $contribution->member->first_name ?? '' }} {{ $contribution->member->last_name ?? '' }}</p>
                                    <p class="text-xs text-slate-500">{{ $contribution->member->member_id ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-bold text-blue-600">₱{{ number_format($contribution->amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700">{{ $contribution->contribution_date ? \Carbon\Carbon::parse($contribution->contribution_date)->format('M d, Y') : $contribution->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ ucfirst($contribution->contribution_type ?? 'Regular') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'approved' => 'from-green-500 to-green-500',
                                    'pending' => 'from-yellow-400 to-yellow-400',
                                    'rejected' => 'from-red-500 to-red-500',
                                ];
                                $sc = $statusColors[$contribution->status] ?? 'from-slate-400 to-slate-500';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r {{ $sc }} rounded-full shadow-sm">
                                {{ ucfirst($contribution->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.contributions.show', $contribution) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                <i class="fas fa-eye"></i> Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-coins text-2xl text-blue-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">No contributions found</h3>
                            <p class="text-sm text-slate-500">Try adjusting your filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contributions->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $contributions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var labels = {!! $contribChartLabelsJson !!};
    var data = {!! $contribChartDataJson !!};

    var ctx = document.getElementById('contribChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Contributions',
                    data: data,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) { return '₱' + context.parsed.y.toLocaleString(); }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(v) { return '₱' + v.toLocaleString(); }, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection
