@extends('AdminSide.layouts.admin')

@section('title', 'Loans Report')

@php
    $loanChartLabelsJson = json_encode($loanChartLabels);
    $loanChartDataJson = json_encode($loanChartData);
    $repayChartDataJson = json_encode($repayChartData);
    $statusDistJson = json_encode(array_values($statusDistribution));
    $statusLabelsJson = json_encode(array_map('ucfirst', array_keys($statusDistribution)));
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #065f46 0%, #059669 25%, #10b981 50%, #34d399 75%, #6ee7b7 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(52, 211, 153, 0.4);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-emerald-200 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">Loans Report</h1>
                </div>
                <p class="text-emerald-200">Comprehensive loan portfolio analysis and repayment tracking</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.loans.export', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl backdrop-blur transition">
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

    <!-- Stats Row 1 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                    <i class="fas fa-hand-holding-dollar text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Total Disbursed</p>
                    <p class="text-xl font-bold text-green-600">₱{{ number_format($totalLoanAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-money-bill-transfer text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Total Repayments</p>
                    <p class="text-xl font-bold text-blue-600">₱{{ number_format($totalRepayments, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
                    <i class="fas fa-exclamation-circle text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Outstanding</p>
                    <p class="text-xl font-bold text-red-600">₱{{ number_format($outstandingBalance, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-chart-pie text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Repayment Rate</p>
                    <p class="text-xl font-bold text-indigo-600">{{ $repaymentRate }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row 2 -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200/60 text-center">
            <p class="text-2xl font-bold text-slate-900">{{ $totalCount }}</p>
            <p class="text-xs font-medium text-slate-500 uppercase">Total Loans</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200/60 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $approvedCount }}</p>
            <p class="text-xs font-medium text-slate-500 uppercase">Approved</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200/60 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
            <p class="text-xs font-medium text-slate-500 uppercase">Pending</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200/60 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $rejectedCount }}</p>
            <p class="text-xs font-medium text-slate-500 uppercase">Rejected</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Loans vs Repayments</h3>
                    <p class="text-xs text-slate-500">Monthly comparison (last 6 months)</p>
                </div>
            </div>
            <div style="height: 240px;">
                <canvas id="loanBarChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Status Distribution</h3>
                    <p class="text-xs text-slate-500">Loan application outcomes</p>
                </div>
            </div>
            <div style="height: 240px;">
                <canvas id="statusDoughnut"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-medium text-slate-600 mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Member name, ID, or Loan #..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                </div>
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="">All</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-green-500/25 transition hover:from-green-600 hover:to-green-700">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.reports.loans') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Clear</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Loan #</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Amount</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Repaid</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Balance</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Term</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                    @php
                        $repaid = $loan->repayments->sum('amount');
                        $balance = $loan->amount - $repaid;
                        $statusColors = [
                            'approved' => 'from-green-500 to-green-500',
                            'pending' => 'from-yellow-400 to-yellow-400',
                            'rejected' => 'from-red-500 to-red-500',
                            'completed' => 'from-blue-500 to-indigo-500',
                            'active' => 'from-green-500 to-green-500',
                            'defaulted' => 'from-red-600 to-red-700',
                        ];
                        $sc = $statusColors[$loan->status] ?? 'from-slate-400 to-slate-500';
                    @endphp
                    <tr class="hover:bg-green-50/30 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $loan->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($loan->member->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($loan->member->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $loan->member->first_name ?? '' }} {{ $loan->member->last_name ?? '' }}</p>
                                    <p class="text-xs text-slate-500">{{ $loan->member->member_id ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-bold text-gray-900">₱{{ number_format($loan->amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-medium text-green-600">₱{{ number_format($repaid, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-medium {{ $balance > 0 ? 'text-red-600' : 'text-slate-400' }}">₱{{ number_format(max($balance, 0), 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $loan->loan_term ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700">{{ $loan->application_date ? \Carbon\Carbon::parse($loan->application_date)->format('M d, Y') : $loan->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r {{ $sc }} rounded-full shadow-sm">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                <i class="fas fa-eye"></i> Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hand-holding-dollar text-2xl text-green-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">No loans found</h3>
                            <p class="text-sm text-slate-500">Try adjusting your filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var loanLabels = {!! $loanChartLabelsJson !!};
    var loanData = {!! $loanChartDataJson !!};
    var repayData = {!! $repayChartDataJson !!};
    var statusDist = {!! $statusDistJson !!};
    var statusLabels = {!! $statusLabelsJson !!};

    // Bar Chart - Loans vs Repayments
    var barCtx = document.getElementById('loanBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: loanLabels,
                datasets: [
                    {
                        label: 'Loans Disbursed',
                        data: loanData,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Repayments',
                        data: repayData,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.dataset.label + ': ₱' + ctx.parsed.y.toLocaleString(); }
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

    // Doughnut - Status Distribution
    var doughCtx = document.getElementById('statusDoughnut');
    if (doughCtx) {
        new Chart(doughCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusDist,
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>
@endsection
