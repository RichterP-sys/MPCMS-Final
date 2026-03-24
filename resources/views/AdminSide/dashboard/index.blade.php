@extends('AdminSide.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 100px; height: 100px;
        border-radius: 50%;
        transform: translate(30%, -30%);
        opacity: 0.1;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .stat-card:hover::before {
        transform: translate(20%, -20%) scale(1.2);
        opacity: 0.15;
    }
    .stat-card-blue::before { background: #3b82f6; }
    .stat-card-amber::before { background: #f59e0b; }
    .stat-card-emerald::before { background: #10b981; }
    .stat-card-red::before { background: #ef4444; }
    
    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .activity-item {
        transition: all 0.2s ease;
    }
    .activity-item:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
    }
</style>

<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl shadow-lg p-8 lg:p-12 mb-2 backdrop-blur" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 50%, #2563eb 100%);">
        <!-- Animated background shapes for banner -->
        <div class="admin-animated-bg pointer-events-none select-none">
            <div class="admin-bg-circle admin-bg-circle-1"></div>
            <div class="admin-bg-circle admin-bg-circle-2"></div>
            <div class="admin-bg-circle admin-bg-circle-3"></div>
            <div class="admin-bg-plus-pattern"></div>
        </div>
        <style>
        .admin-animated-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .admin-bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.18;
            animation: adminCircleFloat 24s ease-in-out infinite;
        }
        .admin-bg-circle-1 {
            width: 400px; height: 400px;
            background: radial-gradient(circle at 30% 30%, #818cf8 0%, #a5b4fc 100%);
            top: -120px; left: -120px;
            animation-delay: 0s;
        }
        .admin-bg-circle-2 {
            width: 260px; height: 260px;
            background: radial-gradient(circle at 70% 70%, #f472b6 0%, #fcd34d 100%);
            bottom: -60px; right: 10%;
            animation-delay: 8s;
        }
        .admin-bg-circle-3 {
            width: 180px; height: 180px;
            background: radial-gradient(circle at 60% 40%, #6ee7b7 0%, #3b82f6 100%);
            top: 40%; right: -60px;
            animation-delay: 16s;
        }
        @keyframes adminCircleFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            25% { transform: translateY(-20px) scale(1.05); }
            50% { transform: translateY(15px) scale(0.97); }
            75% { transform: translateY(-10px) scale(1.03); }
        }
        .admin-bg-plus-pattern {
            position: absolute;
            inset: 0;
            background-image: url('data:image/svg+xml;utf8,<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="18" width="4" height="40" rx="2" fill="%23e0e7ef" fill-opacity="0.18"/><rect y="18" width="40" height="4" rx="2" fill="%23e0e7ef" fill-opacity="0.18"/></svg>');
            background-size: 40px 40px;
            opacity: 0.18;
            pointer-events: none;
        }
        </style>
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(129, 140, 248, 0.3);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-3xl" style="background: rgba(6, 182, 212, 0.2);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Welcome back, Admin</h1>
                <p class="text-indigo-200 mt-1">Here's what's happening with your cooperative today.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-xl backdrop-blur border border-white/20 transition">
                    <i class="fas fa-chart-bar"></i>
                    <span>View Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <a href="{{ route('admin.members.index') }}" class="stat-card stat-card-blue bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-blue-100 block group transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Members</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalMembers }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        @php $chg = $membersChange ?? 0; @endphp
                        <span class="inline-flex items-center text-xs font-medium {{ $chg >= 0 ? 'text-emerald-600 bg-gradient-to-r from-emerald-50 to-teal-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-{{ $chg >= 0 ? 'up' : 'down' }} mr-1" style="font-size:10px"></i>{{ $chg >= 0 ? '+' : '' }}{{ $chg }}%
                        </span>
                        <span class="text-xs text-slate-500">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-medium text-indigo-600 group-hover:text-indigo-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-indigo-400 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Active Loans -->
        <a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="stat-card stat-card-amber bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-yellow-100 block group transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Active Loans</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $activeLoans }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        @php $chg = $loansChange ?? 0; @endphp
                        <span class="inline-flex items-center text-xs font-medium {{ $chg >= 0 ? 'text-amber-600 bg-gradient-to-r from-amber-50 to-orange-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-{{ $chg >= 0 ? 'up' : 'down' }} mr-1" style="font-size:10px"></i>{{ $chg >= 0 ? '+' : '' }}{{ $chg }}%
                        </span>
                        <span class="text-xs text-slate-500">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/25">
                    <i class="fas fa-hand-holding-dollar text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-medium text-yellow-600 group-hover:text-yellow-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-yellow-400 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Total Contributions -->
        <a href="{{ route('admin.finance.index', ['tab' => 'contributions']) }}" class="stat-card stat-card-emerald bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-green-100 block group transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Contributions</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">₱{{ number_format($totalContributions, 0) }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        @php $chg = $contributionsChange ?? 0; @endphp
                        <span class="inline-flex items-center text-xs font-medium {{ $chg >= 0 ? 'text-emerald-600 bg-gradient-to-r from-emerald-50 to-teal-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-{{ $chg >= 0 ? 'up' : 'down' }} mr-1" style="font-size:10px"></i>{{ $chg >= 0 ? '+' : '' }}{{ $chg }}%
                        </span>
                        <span class="text-xs text-slate-500">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/25">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-medium text-green-600 group-hover:text-green-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-green-400 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Due Loans -->
        <a href="{{ route('admin.finance.repayment-confirmation') }}" class="stat-card stat-card-red bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-pink-100 block group transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Due Loans</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $dueLoans ?? 0 }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center text-xs font-medium text-red-600 bg-gradient-to-r from-red-50 to-pink-50 px-2 py-0.5 rounded-full">
                            <i class="fas fa-exclamation mr-1" style="font-size:10px"></i>Attention
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-lg shadow-red-500/25">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-medium text-red-600 group-hover:text-red-700">View Details</span>
                <i class="fas fa-arrow-right text-xs text-red-400 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Monthly Overview -->
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-blue-100 hover-lift">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-chart-area text-indigo-500"></i>
                        <span>Monthly Overview</span>
                    </h3>
                    <p class="text-sm text-slate-500">Loans vs Contributions</p>
                </div>
                <div class="flex gap-2">
                    <span class="flex items-center gap-1.5 text-xs font-medium text-blue-600">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>Loans
                    </span>
                    <span class="flex items-center gap-1.5 text-xs font-medium text-green-600">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>Contributions
                    </span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="monthlyOverviewChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-violet-100 hover-lift">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-violet-500"></i>
                        <span>Recent Activities</span>
                    </h3>
                    <p class="text-sm text-slate-500">Latest transactions and updates</p>
                </div>
                <a href="{{ route('admin.reports.activity-logs') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">View all <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="activity-item flex items-start gap-3 p-3 rounded-xl cursor-pointer">
                    @php
                        $gradientClass = match($activity->activity_type ?? 'default') {
                            'login' => 'from-emerald-500 to-teal-500',
                            'logout' => 'from-slate-500 to-slate-600',
                            'contribution' => 'from-blue-500 to-indigo-500',
                            'loan' => 'from-amber-500 to-orange-500',
                            default => 'from-purple-500 to-pink-500'
                        };
                    @endphp
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br {{ $gradientClass }} flex items-center justify-center flex-shrink-0 shadow-lg">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900">
                            @if($activity->member)
                                {{ $activity->member->first_name }} {{ $activity->member->last_name }}
                            @else
                                Administrator
                            @endif
                        </p>
                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $activity->description }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox text-slate-400"></i>
                    </div>
                    <p class="text-sm text-slate-500">No recent activities</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-amber-100">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-amber-500"></i>
            <span>Quick Actions</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.member-registration.index') }}" class="group relative overflow-hidden flex items-center gap-4 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border border-blue-200/50 rounded-xl transition">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-110 transition">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Add Member</h4>
                    <p class="text-xs text-slate-500">Register new member</p>
                </div>
            </a>
            
            <a href="{{ route('admin.loans.index') }}" class="group relative overflow-hidden flex items-center gap-4 p-4 bg-gradient-to-br from-yellow-50 to-orange-50 hover:from-yellow-100 hover:to-orange-100 border border-yellow-200/50 rounded-xl transition">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/25 group-hover:scale-110 transition">
                    <i class="fas fa-hand-holding-dollar text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Manage Loans</h4>
                    <p class="text-xs text-slate-500">Review applications</p>
                </div>
            </a>
            
            <a href="{{ route('admin.reports.index') }}" class="group relative overflow-hidden flex items-center gap-4 p-4 bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border border-purple-200/50 rounded-xl transition">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:scale-110 transition">
                    <i class="fas fa-chart-line text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">View Reports</h4>
                    <p class="text-xs text-slate-500">Generate analytics</p>
                </div>
            </a>

            <a href="{{ route('admin.messages.index') }}" class="group relative overflow-hidden flex items-center gap-4 p-4 bg-gradient-to-br from-indigo-50 to-blue-50 hover:from-indigo-100 hover:to-blue-100 border border-indigo-200/50 rounded-xl transition">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:scale-110 transition relative">
                    <i class="fas fa-envelope text-white text-lg"></i>
                    @if(($unreadMessages ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $unreadMessages > 99 ? '99+' : $unreadMessages }}</span>
                    @endif
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Contact Messages</h4>
                    <p class="text-xs text-slate-500">{{ $unreadMessages ?? 0 }} unread</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Member Growth -->
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-blue-100 hover-lift">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Member Growth</h3>
            </div>
            <div class="h-48">
                <canvas id="memberGrowthChart"></canvas>
            </div>
        </div>

        <!-- Loan Status -->
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-yellow-100 hover-lift">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-sm"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Loan Status</h3>
            </div>
            <div class="h-48">
                <canvas id="loanStatusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-green-100 hover-lift">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-peso-sign text-white text-sm"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Monthly Revenue</h3>
            </div>
            <div class="h-48">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = 'system-ui, -apple-system, sans-serif';
    Chart.defaults.color = '#64748b';
    
    @php
        $loansData = $monthlyLoans ?? array_fill(0, 12, 0);
        $contribData = $monthlyContributions ?? array_fill(0, 12, 0);
        $monthlyChartLabels = $monthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $growthData = $memberGrowth ?? array_fill(0, 4, 0);
        $statusData = $loanStatusData ?? ['approved' => 0, 'pending' => 0, 'rejected' => 0, 'completed' => 0];
        $revenueData = $monthlyRevenue ?? array_fill(0, 6, 0);
        $revenueLabels = $revenueLabels ?? array_map(fn($i) => now()->subMonths(5-$i)->format('M'), range(0, 5));
    @endphp
    
    var monthlyLoansData = {!! json_encode($loansData) !!};
    var monthlyContribData = {!! json_encode($contribData) !!};
    var memberGrowthData = {!! json_encode($growthData) !!};
    var loanStatusData = {!! json_encode($statusData) !!};
    var monthlyRevenueData = {!! json_encode($revenueData) !!};
    var revenueLabels = {!! json_encode($revenueLabels) !!};
    
    // Monthly Overview with gradient
    var ctx1 = document.getElementById('monthlyOverviewChart').getContext('2d');
    var gradient1 = ctx1.createLinearGradient(0, 0, 0, 250);
    gradient1.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradient1.addColorStop(1, 'rgba(99, 102, 241, 0.01)');
    var gradient2 = ctx1.createLinearGradient(0, 0, 0, 250);
    gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradient2.addColorStop(1, 'rgba(16, 185, 129, 0.01)');
    
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyChartLabels) !!},
            datasets: [{
                label: 'Loans',
                data: monthlyLoansData,
                borderColor: '#6366f1',
                backgroundColor: gradient1,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Contributions',
                data: monthlyContribData,
                borderColor: '#10b981',
                backgroundColor: gradient2,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(99, 102, 241, 0.1)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Member Growth with colorful bars
    new Chart(document.getElementById('memberGrowthChart'), {
        type: 'bar',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'New Members',
                data: memberGrowthData,
                backgroundColor: [
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: ['#6366f1', '#10b981', '#f59e0b', '#8b5cf6'],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(99, 102, 241, 0.1)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Loan Status Doughnut
    new Chart(document.getElementById('loanStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected', 'Completed'],
            datasets: [{
                data: [loanStatusData.approved || 0, loanStatusData.pending || 0, loanStatusData.rejected || 0, loanStatusData.completed || 0],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)',
                    'rgba(239, 68, 68, 0.85)',
                    'rgba(99, 102, 241, 0.85)'
                ],
                borderColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                borderWidth: 2,
                cutout: '70%',
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } } }
        }
    });

    // Monthly Revenue
    var ctx4 = document.getElementById('monthlyRevenueChart').getContext('2d');
    var revenueGradient = ctx4.createLinearGradient(0, 0, 0, 200);
    revenueGradient.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
    revenueGradient.addColorStop(1, 'rgba(6, 182, 212, 0.8)');
    
    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: monthlyRevenueData,
                backgroundColor: revenueGradient,
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(16, 185, 129, 0.1)' }, ticks: { callback: function(v) { return '₱' + v.toLocaleString(); } } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection
