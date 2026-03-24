@extends('AdminSide.layouts.admin')

@section('title', 'Reports & Analytics')

@php
    $chartLabelsJson = json_encode($chartLabels);
    $monthlyContributionsJson = json_encode($monthlyContributions);
    $monthlyLoansJson = json_encode($monthlyLoans);
    $memberGrowthLabelsJson = json_encode($memberGrowthLabels);
    $memberGrowthDataJson = json_encode($memberGrowthData);
@endphp

@section('content')
<style>
    .report-card {
        transition: all 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .rec-card {
        transition: all 0.2s ease;
    }
    .rec-card:hover {
        transform: translateX(4px);
    }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-blue-900 tracking-tight drop-shadow">Reports & Analytics</h2>
            <p class="text-blue-700/80 mt-1">Comprehensive insights, charts, and recommendations</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <a href="{{ route('admin.members.index') }}" class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-blue-100 hover:shadow-xl hover:border-blue-400 block group transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-blue-700"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Total Members</p>
                    <p class="text-2xl font-extrabold text-blue-900">{{ $totalMembers }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-blue-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-blue-500 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        <a href="{{ route('admin.members.index', ['status' => 'active']) }}" class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-green-100 hover:shadow-xl hover:border-green-400 block group transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-user-check text-green-700"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Active</p>
                    <p class="text-2xl font-extrabold text-green-900">{{ $activeMembers }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-green-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-green-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-green-500 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        <a href="{{ route('admin.reports.contributions') }}" class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-yellow-100 hover:shadow-xl hover:border-yellow-400 block group transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-yellow-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-coins text-yellow-700"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide">Contributions</p>
                    <p class="text-xl font-extrabold text-yellow-900">₱{{ number_format($totalContributions, 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-yellow-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-yellow-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-yellow-500 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        <a href="{{ route('admin.reports.loans') }}" class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-red-100 hover:shadow-xl hover:border-red-400 block group transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-red-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-hand-holding-dollar text-red-700"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-red-700 uppercase tracking-wide">Loans</p>
                    <p class="text-xl font-extrabold text-red-900">₱{{ number_format($totalLoans, 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-red-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-red-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-red-500 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        <a href="{{ route('admin.reports.dividends') }}" class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-pink-100 hover:shadow-xl hover:border-pink-400 block group transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-pink-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-percent text-pink-700"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-pink-700 uppercase tracking-wide">Dividends</p>
                    <p class="text-xl font-extrabold text-pink-900">₱{{ number_format($totalDividends, 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-pink-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-pink-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-pink-500 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white/90 backdrop-blur rounded-2xl p-8 shadow-lg border border-blue-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-chart-line text-blue-700 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900">Monthly Trends</h3>
                    <p class="text-xs text-blue-700/80">Contributions vs Loans (last 12 months)</p>
                </div>
            </div>
            <div style="height: 280px;">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        <div class="bg-white/90 backdrop-blur rounded-2xl p-8 shadow-lg border border-green-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-chart-bar text-green-700 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-green-900">Member Growth</h3>
                    <p class="text-xs text-green-700/80">New registrations per month</p>
                </div>
            </div>
            <div style="height: 280px;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Prescriptive Analytics -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-yellow-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-yellow-100 bg-yellow-50/80">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-yellow-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-lightbulb text-yellow-700 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-yellow-900">AI Recommendations</h3>
                    <p class="text-xs text-yellow-700/80">System-generated insights to guide decision-making</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-yellow-100">
            @foreach($recommendations as $rec)
            @php
                $recColors = [
                    'danger' => ['border' => 'border-l-red-500', 'bg' => 'bg-red-50', 'icon_bg' => 'bg-red-600', 'btn' => 'bg-red-600'],
                    'warning' => ['border' => 'border-l-yellow-500', 'bg' => 'bg-yellow-50', 'icon_bg' => 'bg-yellow-600', 'btn' => 'bg-yellow-600'],
                    'info' => ['border' => 'border-l-blue-500', 'bg' => 'bg-blue-50', 'icon_bg' => 'bg-blue-600', 'btn' => 'bg-blue-600'],
                    'success' => ['border' => 'border-l-green-500', 'bg' => 'bg-green-50', 'icon_bg' => 'bg-green-600', 'btn' => 'bg-green-600'],
                ];
                $c = $recColors[$rec['type']] ?? $recColors['info'];
            @endphp
            <div class="rec-card p-5 border-l-4 {{ $c['border'] }} {{ $c['bg'] }} rounded-xl mb-2 shadow-sm">
                <div class="flex items-start gap-5">
                    <div class="w-12 h-12 rounded-xl {{ $c['icon_bg'] }} flex items-center justify-center flex-shrink-0 shadow-md">
                        <i class="fas {{ $rec['icon'] }} text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-bold text-blue-900">{{ $rec['title'] }}</p>
                        <p class="text-sm text-blue-700/80 mt-1">{{ $rec['message'] }}</p>
                    </div>
                    @if($rec['action'])
                    <a href="{{ $rec['action'] }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white {{ $c['btn'] }} rounded-xl whitespace-nowrap transition hover:opacity-90 shadow">
                        {{ $rec['action_text'] }} <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <a href="{{ route('admin.reports.contributions') }}" class="report-card bg-white/90 backdrop-blur rounded-2xl p-7 shadow-lg border border-blue-100 block hover:shadow-xl hover:border-blue-400 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-blue-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-coins text-blue-700 text-2xl"></i>
                </div>
            </div>
            <h3 class="font-bold text-blue-900 mb-2 text-lg">Contributions Report</h3>
            <p class="text-sm text-blue-700/80 mb-4 leading-relaxed">Member contributions, payment patterns, and growth trends</p>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-xl">
                <i class="fas fa-chart-bar"></i>View Details <i class="fas fa-arrow-right text-xs ml-1"></i>
            </span>
        </a>
        <a href="{{ route('admin.reports.loans') }}" class="report-card bg-white/90 backdrop-blur rounded-2xl p-7 shadow-lg border border-yellow-100 block hover:shadow-xl hover:border-yellow-400 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-yellow-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-file-invoice-dollar text-yellow-700 text-2xl"></i>
                </div>
            </div>
            <h3 class="font-bold text-yellow-900 mb-2 text-lg">Loans Report</h3>
            <p class="text-sm text-yellow-700/80 mb-4 leading-relaxed">Loan portfolio analysis, approval rates, and repayment tracking</p>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white text-sm font-semibold rounded-xl">
                <i class="fas fa-chart-line"></i>View Details <i class="fas fa-arrow-right text-xs ml-1"></i>
            </span>
        </a>
        <a href="{{ route('admin.reports.dividends') }}" class="report-card bg-white/90 backdrop-blur rounded-2xl p-7 shadow-lg border border-pink-100 block hover:shadow-xl hover:border-pink-400 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-pink-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-percent text-pink-700 text-2xl"></i>
                </div>
            </div>
            <h3 class="font-bold text-pink-900 mb-2 text-lg">Dividends Report</h3>
            <p class="text-sm text-pink-700/80 mb-4 leading-relaxed">Dividend calculations, distribution history, and member earnings</p>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white text-sm font-semibold rounded-xl">
                <i class="fas fa-chart-pie"></i>View Details <i class="fas fa-arrow-right text-xs ml-1"></i>
            </span>
        </a>
        <a href="{{ route('admin.reports.schedule') }}" class="report-card bg-white/90 backdrop-blur rounded-2xl p-7 shadow-lg border border-slate-200 block hover:shadow-xl hover:border-slate-400 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gray-200 flex items-center justify-center shadow-md">
                    <i class="fas fa-file-invoice text-gray-700 text-2xl"></i>
                </div>
            </div>
            <h3 class="font-bold text-gray-900 mb-2 text-lg">Schedule Report</h3>
            <p class="text-sm text-gray-700/80 mb-4 leading-relaxed">Cash on Hand, Loans Receivable, CBU/Savings/SSFD schedules</p>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white text-sm font-semibold rounded-xl">
                <i class="fas fa-table"></i>View Schedule <i class="fas fa-arrow-right text-xs ml-1"></i>
            </span>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chartLabels = {!! $chartLabelsJson !!};
    var monthlyContributions = {!! $monthlyContributionsJson !!};
    var monthlyLoans = {!! $monthlyLoansJson !!};
    var memberGrowthLabels = {!! $memberGrowthLabelsJson !!};
    var memberGrowthData = {!! $memberGrowthDataJson !!};

    // Monthly Trends Chart
    var trendsCtx = document.getElementById('trendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Contributions',
                        data: monthlyContributions,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#6366f1',
                    },
                    {
                        label: 'Loans',
                        data: monthlyLoans,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#ef4444',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '₱' + value.toLocaleString(); },
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { ticks: { font: { size: 10 }, maxRotation: 45 }, grid: { display: false } }
                }
            }
        });
    }

    // Member Growth Chart
    var growthCtx = document.getElementById('growthChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'bar',
            data: {
                labels: memberGrowthLabels,
                datasets: [{
                    label: 'New Members',
                    data: memberGrowthData,
                    backgroundColor: '#10b981',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) { return context.parsed.y + ' new member(s)'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection
