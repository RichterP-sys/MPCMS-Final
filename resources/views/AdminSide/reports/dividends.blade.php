@extends('AdminSide.layouts.admin')

@section('title', 'Dividends Report')

@php
    $dividendChartLabelsJson = json_encode($dividendChartLabels ?? []);
    $dividendChartDataJson = json_encode($dividendChartData ?? []);
    $contributionChartDataJson = json_encode($contributionChartData ?? []);
@endphp

@section('content')
<div class="space-y-6" x-data="{ showCalcModal: false }">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #581c87 0%, #7c3aed 25%, #8b5cf6 50%, #a78bfa 75%, #c4b5fd 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(167, 139, 250, 0.4);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-purple-200 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">Dividends Report</h1>
                </div>
                <p class="text-purple-200">Calculate and distribute member dividends</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="GET" class="flex items-center gap-2">
                    <select name="year" onchange="this.form.submit()" class="px-3 py-2.5 text-sm bg-white/10 border border-white/20 rounded-xl text-white backdrop-blur focus:ring-2 focus:ring-white/30">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }} class="text-slate-900">{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <button @click="showCalcModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-purple-50 text-purple-600 text-sm font-medium rounded-xl shadow-lg transition">
                    <i class="fas fa-calculator"></i>
                    <span>Calculate Dividends</span>
                </button>
                <a href="{{ route('admin.reports.dividends.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl backdrop-blur transition">
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
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <i class="fas fa-coins text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Total Dividends</p>
                    <p class="text-xl font-bold text-purple-600">₱{{ number_format($totalDividends, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Eligible Members</p>
                    <p class="text-xl font-bold text-blue-600">{{ $membersEligible }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Average</p>
                    <p class="text-xl font-bold text-amber-600">₱{{ number_format($averageDividend, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Released</p>
                    <p class="text-xl font-bold text-emerald-600">{{ $releasedCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Pending</p>
                    <p class="text-xl font-bold text-rose-600">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($pendingCount > 0)
    <div class="flex justify-end">
        <form action="{{ route('admin.reports.dividends.release') }}" method="POST" onsubmit="return confirm('Release all pending dividends for {{ $year }}?')">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/25 transition">
                <i class="fas fa-paper-plane"></i>
                Release All Pending Dividends
            </button>
        </form>
    </div>
    @endif

    <!-- Chart -->
    @if($dividends->count() > 0)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                <i class="fas fa-chart-bar text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900">Top Member Dividends</h3>
                <p class="text-xs text-slate-500">Contributions vs dividend earnings for {{ $year }}</p>
            </div>
        </div>
        <div style="height: 260px;">
            <canvas id="dividendChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Dividends Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-purple-50/50 to-pink-50/50">
            <h3 class="font-semibold text-slate-900">Member Dividends - {{ $year }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Contributions</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Rate</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Dividend Amount</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dividends as $dividend)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xs font-semibold shadow">
                                    {{ strtoupper(substr($dividend->member->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($dividend->member->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $dividend->member->first_name ?? '' }} {{ $dividend->member->last_name ?? '' }}</p>
                                    <p class="text-xs text-slate-500">{{ $dividend->member->member_id ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-semibold text-slate-700">₱{{ number_format($dividend->total_contributions, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-medium text-indigo-600">{{ number_format($dividend->dividend_rate * 100, 2) }}%</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-bold text-purple-600">₱{{ number_format($dividend->dividend_amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dividend->status === 'released')
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full shadow-sm">Released</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r from-amber-400 to-orange-400 rounded-full shadow-sm">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dividend->member)
                            <a href="{{ route('admin.members.show', $dividend->member) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                                <i class="fas fa-eye"></i> Details
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-percent text-2xl text-purple-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">No dividends for {{ $year }}</h3>
                            <p class="text-sm text-slate-500 mb-4">Calculate dividends to generate records</p>
                            <button @click="showCalcModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/25 transition">
                                <i class="fas fa-calculator"></i>Calculate Dividends
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($dividends->count() > 0)
                <tfoot class="bg-gradient-to-r from-purple-50 to-pink-50 border-t-2 border-purple-200">
                    <tr>
                        <td class="px-6 py-4"><p class="text-sm font-bold text-slate-900">TOTALS</p></td>
                        <td class="px-6 py-4 text-right"><p class="text-sm font-bold text-slate-700">₱{{ number_format($dividends->sum('total_contributions'), 2) }}</p></td>
                        <td class="px-6 py-4 text-right"><p class="text-sm font-bold text-indigo-600">—</p></td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-3 py-1.5 text-sm font-bold text-white bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg shadow">
                                ₱{{ number_format($totalDividends, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Calculate Modal -->
    <div x-show="showCalcModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCalcModal = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <form action="{{ route('admin.reports.dividends.calculate') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="text-lg font-semibold text-slate-900">Calculate Dividends</h3>
                        <p class="text-xs text-slate-500 mt-1">This will calculate dividends for all active members based on their contributions</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Year</label>
                            <select name="year" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-300">
                                @for($y = now()->year; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Dividend Rate (%)</label>
                            <div class="relative">
                                <input type="number" name="dividend_rate" step="0.01" min="0.01" max="100" value="5" 
                                       class="w-full px-4 py-3 text-lg font-semibold border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-300" required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Each member's dividend = Their total contributions x rate</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 bg-slate-50 border-t border-slate-200">
                        <button type="button" @click="showCalcModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl hover:from-purple-600 hover:to-pink-700 transition">Calculate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chartLabels = {!! $dividendChartLabelsJson !!};
    var dividendData = {!! $dividendChartDataJson !!};
    var contribData = {!! $contributionChartDataJson !!};

    var ctx = document.getElementById('dividendChart');
    if (ctx && chartLabels.length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Contributions',
                        data: contribData,
                        backgroundColor: 'rgba(139, 92, 246, 0.3)',
                        borderColor: '#8b5cf6',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Dividends',
                        data: dividendData,
                        backgroundColor: 'rgba(236, 72, 153, 0.7)',
                        borderColor: '#ec4899',
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
});
</script>
@endsection
