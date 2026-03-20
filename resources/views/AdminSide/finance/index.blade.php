@extends('AdminSide.layouts.admin')

@section('title', 'Finance Management')

@section('content')
<style>
    .stat-mini {
        position: relative;
        overflow: hidden;
    }
    .stat-mini::after {
        content: '';
        position: absolute;
        top: -50%; right: -50%;
        width: 100%; height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transition: all 0.3s ease;
    }
    .stat-mini:hover::after {
        transform: scale(1.5);
    }
    .table-row-hover {
        transition: all 0.2s ease;
    }
    .table-row-hover:hover {
        background: linear-gradient(90deg, rgba(99, 102, 241, 0.03), rgba(139, 92, 246, 0.03), transparent);
    }
</style>

<div class="space-y-6">
    <!-- Header with Gradient Banner -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4338ca 50%, #059669 75%, #10b981 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(16, 185, 129, 0.3);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-3xl" style="background: rgba(99, 102, 241, 0.3);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Finance Management</h1>
                <p class="text-indigo-200 mt-2">Manage loans, contributions, and track the cooperative's financial health</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-lg px-3 py-2 text-sm">
                        <span class="text-indigo-200">💡 Tip:</span>
                        <span class="text-white ml-2">Start with Repayment Confirmation to process this month's payments</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.finance.repayment-confirmation') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-xl backdrop-blur border border-white/20 transition">
                    <i class="fas fa-receipt"></i>
                    <span>Repayment Confirmation</span>
                </a>
                <a href="{{ route('admin.receipts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-500/30 hover:bg-green-500/40 text-white text-sm font-medium rounded-xl backdrop-blur border border-green-300/30 transition">
                    <i class="fas fa-file-invoice"></i>
                    <span>View Receipts</span>
                </a>
                @if($isLendingFrozen)
                <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/30 text-white/70 text-sm font-medium rounded-xl border border-red-400/30 cursor-not-allowed">
                    <i class="fas fa-ban"></i>
                    <span>Lending Frozen</span>
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Lending Freeze Alert -->
    @if($isLendingFrozen)
    <div class="relative overflow-hidden rounded-2xl p-5 border-2 border-red-300 bg-gradient-to-r from-red-50 to-rose-50">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/25 flex-shrink-0">
                <i class="fas fa-ban text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-red-800">Lending Temporarily Suspended</h3>
                <p class="text-xs text-red-600 mt-0.5">
                    Cooperative funds are at <strong>₱{{ number_format($totalFunds, 2) }}</strong> (threshold: ₱{{ number_format($freezeThreshold, 2) }}). Loan approvals are disabled until funds are replenished.
                </p>
            </div>
            <a href="{{ route('admin.amount-held.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition flex-shrink-0">
                <i class="fas fa-wallet"></i> View Funds
            </a>
        </div>
    </div>
    @endif

    <!-- Overview Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-mini rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-indigo-200 uppercase tracking-wide">Total Loans</p>
                    <p class="text-xl font-bold">₱{{ number_format($totalLoans, 0) }}</p>
                </div>
            </div>
        </div>
        <div class="stat-mini rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-emerald-200 uppercase tracking-wide">Total Contributions</p>
                    <p class="text-xl font-bold">₱{{ number_format($totalContributions, 0) }}</p>
                </div>
            </div>
        </div>
        <div class="stat-mini rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-amber-200 uppercase tracking-wide">Pending Items</p>
                    <p class="text-xl font-bold">{{ $pendingLoans + $pendingContributions }}</p>
                </div>
            </div>
        </div>
        <div class="stat-mini rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-purple-200 uppercase tracking-wide">Net Balance</p>
                    <p class="text-xl font-bold">₱{{ number_format($totalContributions - $totalLoans, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Merged Table: Loans + Contributions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-slate-50 to-indigo-50/30 flex flex-wrap items-center justify-between gap-4">
            <h3 class="font-semibold text-slate-900">
                @if($tab === 'loans')
                    Loan Applications
                @elseif($tab === 'contributions')
                    Contributions
                @else
                    All Records (Loans & Contributions)
                @endif
            </h3>
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('admin.finance.index') }}" class="flex gap-2">
                    @if($tab)
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    @endif
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Search by name, member ID, amount..."
                            class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
                    </div>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                        Search
                    </button>
                    @if(!empty($search))
                    <a href="{{ route('admin.finance.index', $tab ? ['tab' => $tab] : []) }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 border border-slate-200 rounded-xl transition">
                        Clear
                    </a>
                    @endif
                </form>
                <div class="flex gap-2">
                    <a href="{{ route('admin.finance.index', request()->only('search')) }}" class="text-sm font-medium {{ ($tab ?? 'all') === 'all' ? 'text-indigo-600' : 'text-slate-500 hover:text-indigo-600' }}">All</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('admin.finance.index', array_merge(request()->only('search'), ['tab' => 'loans'])) }}" class="text-sm font-medium {{ ($tab ?? 'all') === 'loans' ? 'text-indigo-600' : 'text-slate-500 hover:text-indigo-600' }}">Loan Applications</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('admin.finance.index', array_merge(request()->only('search'), ['tab' => 'contributions'])) }}" class="text-sm font-medium {{ ($tab ?? 'all') === 'contributions' ? 'text-indigo-600' : 'text-slate-500 hover:text-indigo-600' }}">Contributions</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('admin.finance.index', array_merge(request()->only('search'), ['tab' => 'mortuary'])) }}" class="text-sm font-medium {{ ($tab ?? 'all') === 'mortuary' ? 'text-indigo-600' : 'text-slate-500 hover:text-indigo-600' }}">Mortuary Aid</a>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        @if($tab !== 'loans')
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Type</th>
                        @endif
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Amount</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $record)
                    <tr class="table-row-hover">
                        @if($tab !== 'loans')
                        <td class="px-6 py-4">
                            @if($record->record_type === 'loan')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-full shadow-sm">
                                <i class="fas fa-hand-holding-dollar"></i> Loan
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-full shadow-sm">
                                <i class="fas fa-coins"></i>
                                @if(($record->contribution_type ?? null) === 'mortuary')
                                    Mortuary Aid
                                @else
                                    {{ ucfirst($record->contribution_type ?? 'Contribution') }}
                                @endif
                            </span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-semibold shadow-lg {{ $record->record_type === 'loan' ? 'bg-gradient-to-br from-indigo-500 to-indigo-600' : 'bg-gradient-to-br from-green-500 to-green-600' }}">
                                    {{ strtoupper(substr($record->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($record->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $record->first_name }} {{ $record->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $record->member_code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold {{ $record->record_type === 'loan' ? 'bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent' : 'bg-gradient-to-r from-green-600 to-green-600 bg-clip-text text-transparent' }}">₱{{ number_format($record->amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = match(strtolower($record->status ?? '')) {
                                    'approved' => 'bg-green-500',
                                    'completed' => 'bg-blue-500',
                                    'pending' => 'bg-yellow-500',
                                    'rejected' => 'bg-red-500',
                                    default => 'bg-black-500'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white rounded-full {{ $statusStyles }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $recordDate = $record->record_type === 'contribution' ? ($record->contribution_date ?? $record->application_date) : $record->application_date;
                            @endphp
                            <p class="text-sm text-slate-900">{{ $recordDate ? \Carbon\Carbon::parse($recordDate)->format('M d, Y') : '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @if($record->record_type === 'loan')
                                    <a href="{{ route('admin.loans.show', $record->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.loans.edit', $record->id) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if($record->status === 'pending')
                                        @if($isLendingFrozen)
                                            <span class="p-2 text-slate-300 cursor-not-allowed" title="Lending suspended">
                                                <i class="fas fa-check text-sm"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('admin.loans.approve', $record->id) }}" method="POST" class="inline">
                                                @csrf
                                                <a href="#" class="inline-flex p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition cursor-pointer js-confirm" data-confirm-message="Approve this loan application?" title="Approve">
                                                    <i class="fas fa-check text-sm"></i>
                                                </a>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.loans.reject', $record->id) }}" method="POST" class="inline">
                                            @csrf
                                            <a href="#" class="inline-flex p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer js-confirm" data-confirm-message="Reject this loan application?" title="Reject">
                                                <i class="fas fa-times text-sm"></i>
                                            </a>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('admin.contributions.show', $record->id) }}" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.contributions.edit', $record->id) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if($record->status === 'pending')
                                        <form action="{{ route('admin.contributions.approve', $record->id) }}" method="POST" class="inline">
                                            @csrf
                                            <a href="#" class="inline-flex p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition cursor-pointer js-confirm" data-confirm-message="Approve this contribution?" title="Approve">
                                                <i class="fas fa-check text-sm"></i>
                                            </a>
                                        </form>
                                        <form action="{{ route('admin.contributions.reject', $record->id) }}" method="POST" class="inline">
                                            @csrf
                                            <a href="#" class="inline-flex p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer js-confirm" data-confirm-message="Reject this contribution?" title="Reject">
                                                <i class="fas fa-times text-sm"></i>
                                            </a>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.contributions.destroy', $record->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <a href="#" class="inline-flex p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer js-confirm-delete" data-confirm-message="Delete this contribution?" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </a>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        @php
                            $colspan = 6;
                            if ($tab === 'loans') $colspan = 5;
                            elseif ($tab === 'all') $colspan = 6;
                        @endphp
                        <td colspan="{{ $colspan }}" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-{{ $tab === 'loans' ? 'file-invoice-dollar' : 'wallet' }} text-2xl text-indigo-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 mb-1">
                                @if($tab === 'loans')
                                    No loan applications yet
                                @elseif($tab === 'contributions')
                                    No contributions yet
                                @else
                                    No records yet
                                @endif
                            </h3>
                            <p class="text-sm text-slate-500 mb-4">
                                @if($tab === 'loans')
                                    Members can apply for loans from their dashboard.
                                @else
                                    Loan and contribution records will appear here.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
