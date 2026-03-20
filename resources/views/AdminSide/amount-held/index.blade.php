@extends('AdminSide.layouts.admin')

@section('title', 'Amount Held')

@section('content')
<style>
    .fund-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .fund-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(236, 72, 153, 0.25);
    }
    .fund-card::after {
        content: '';
        position: absolute;
        top: -50%; right: -50%;
        width: 100%; height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transition: all 0.3s ease;
    }
    .fund-card:hover::after {
        transform: scale(1.5);
    }
    .bank-card {
        transition: all 0.2s ease;
    }
    .bank-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.2);
    }
</style>

<div class="space-y-6" x-data="{ showCashModal: false, showBankModal: false, editBank: null }">
    <!-- Header with Total Funds -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #831843 0%, #be185d 25%, #db2777 50%, #ec4899 75%, #f472b6 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background: rgba(244, 114, 182, 0.4);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-3xl" style="background: rgba(219, 39, 119, 0.4);"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">Cooperative Funds</h1>
                <p class="text-pink-200 mt-1">Total money held by the cooperative</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-pink-200 uppercase tracking-wide">Total Amount Held</p>
                    <p class="text-3xl lg:text-4xl font-bold text-white">₱{{ number_format($totalFunds, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lending Status -->
    <div class="rounded-2xl p-5 border-2 {{ $isLendingFrozen ? 'border-red-300 bg-gradient-to-r from-red-50 to-red-50' : 'border-green-300 bg-gradient-to-r from-green-50 to-green-50' }}">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $isLendingFrozen ? 'bg-gradient-to-br from-red-500 to-red-600 shadow-red-500/25' : 'bg-gradient-to-br from-green-500 to-green-600 shadow-green-500/25' }} flex items-center justify-center shadow-lg">
                    <i class="fas {{ $isLendingFrozen ? 'fa-ban' : 'fa-check-circle' }} text-white text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold {{ $isLendingFrozen ? 'text-red-800' : 'text-green-800' }}">
                            Lending Status: {{ $isLendingFrozen ? 'FROZEN' : 'ACTIVE' }}
                        </h3>
                        @if(!$isLendingFrozen)
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        @endif
                    </div>
                    <p class="text-xs {{ $isLendingFrozen ? 'text-red-600' : 'text-green-600' }} mt-0.5">
                        @if($isLendingFrozen)
                            Funds are at ₱{{ number_format($totalFunds, 2) }} — below the ₱{{ number_format($freezeThreshold, 2) }} threshold. Loan approvals are disabled.
                        @else
                            Funds are at ₱{{ number_format($totalFunds, 2) }} — above the ₱{{ number_format($freezeThreshold, 2) }} minimum. Max loanable: ₱{{ number_format(max(0, $totalFunds - $freezeThreshold), 2) }}.
                        @endif
                    </p>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-xs font-medium {{ $isLendingFrozen ? 'text-red-500' : 'text-green-500' }} uppercase tracking-wide">Threshold</p>
                <p class="text-lg font-bold {{ $isLendingFrozen ? 'text-red-700' : 'text-green-700' }}">₱{{ number_format($freezeThreshold, 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="fund-card rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #059669, #10b981);">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <p class="text-xs font-medium text-emerald-200 uppercase tracking-wide">Cash on Hand</p>
                </div>
                <p class="text-2xl font-bold">₱{{ number_format($totalCash, 2) }}</p>
            </div>
        </div>
        <div class="fund-card rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-building-columns"></i>
                    </div>
                    <p class="text-xs font-medium text-indigo-200 uppercase tracking-wide">Bank Deposits</p>
                </div>
                <p class="text-2xl font-bold">₱{{ number_format($totalBankBalance, 2) }}</p>
            </div>
        </div>
        <div class="fund-card rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #0284c7, #0ea5e9);">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-coins"></i>
                    </div>
                    <p class="text-xs font-medium text-sky-200 uppercase tracking-wide">Total Contributions</p>
                </div>
                <p class="text-2xl font-bold">₱{{ number_format($totalContributions, 2) }}</p>
            </div>
        </div>
        <div class="fund-card rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <p class="text-xs font-medium text-amber-200 uppercase tracking-wide">Outstanding Loans</p>
                </div>
                <p class="text-2xl font-bold">₱{{ number_format($outstandingLoans, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Cash on Hand & Banks Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Cash on Hand Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-green-50 to-green-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900">Cash on Hand</h3>
                    </div>
                    <button @click="showCashModal = true" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center py-4">
                    <p class="text-4xl font-bold bg-gradient-to-r from-green-600 to-green-600 bg-clip-text text-transparent">
                        ₱{{ number_format($totalCash, 2) }}
                    </p>
                    <p class="text-sm text-slate-500 mt-2">{{ $cashOnHand ? $cashOnHand->description ?? 'Available cash' : 'No cash recorded' }}</p>
                    @if($cashOnHand && $cashOnHand->updated_at)
                        <p class="text-xs text-slate-400 mt-1">Last updated: {{ $cashOnHand->updated_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bank Accounts Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-indigo-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-building-columns text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Bank Accounts</h3>
                            <p class="text-xs text-slate-500">{{ $bankAccounts->count() }} account(s)</p>
                        </div>
                    </div>
                    <button @click="showBankModal = true; editBank = null" class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-indigo-600 to-indigo-600 hover:from-indigo-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-indigo-500/25 transition">
                        <i class="fas fa-plus text-xs"></i>
                        Add Bank
                    </button>
                </div>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($bankAccounts as $bank)
                @php
                    $bankColors = [
                        'BDO' => ['from-blue-600', 'to-blue-800', 'bg-blue-500', 'shadow-blue-500/20', 'text-blue-600'],
                        'Landbank' => ['from-green-600', 'to-green-800', 'bg-green-500', 'shadow-green-500/20', 'text-green-600'],
                        'RSB' => ['from-red-600', 'to-red-800', 'bg-red-500', 'shadow-red-500/20', 'text-red-600'],
                    ];
                    $colors = $bankColors[$bank->bank_name] ?? ['from-slate-600', 'to-slate-800', 'bg-slate-500', 'shadow-slate-500/20', 'text-slate-600'];
                @endphp
                <div class="bank-card p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $colors[0] }} {{ $colors[1] }} flex items-center justify-center shadow-lg {{ $colors[3] }}">
                            @if($bank->bank_name === 'BDO')
                                <span class="text-white font-bold text-sm">BDO</span>
                            @elseif($bank->bank_name === 'Landbank')
                                <i class="fas fa-landmark text-white"></i>
                            @elseif($bank->bank_name === 'RSB')
                                <span class="text-white font-bold text-sm">RSB</span>
                            @else
                                <i class="fas fa-university text-white"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $bank->bank_name }}</p>
                            @if($bank->account_number)
                                <p class="text-xs text-slate-500">{{ $bank->account_number }}</p>
                            @endif
                            @if($bank->account_name)
                                <p class="text-xs text-slate-400">{{ $bank->account_name }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-lg font-bold {{ $colors[4] }}">₱{{ number_format($bank->amount, 2) }}</p>
                            <p class="text-xs text-slate-400">Updated {{ $bank->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="showBankModal = true; editBank = {{ $bank->toJson() }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <form action="{{ route('admin.amount-held.bank.destroy', $bank) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition js-confirm-delete" data-confirm-message="Remove this bank account?">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building-columns text-2xl text-indigo-400"></i>
                    </div>
                    <h3 class="text-sm font-medium text-slate-900 mb-1">No bank accounts</h3>
                    <p class="text-sm text-slate-500 mb-4">Add your cooperative's bank accounts to track deposits</p>
                    <button @click="showBankModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-lg shadow-lg shadow-indigo-500/25 transition">
                        <i class="fas fa-plus"></i>Add Bank Account
                    </button>
                </div>
                @endforelse
            </div>

            @if($bankAccounts->count() > 0)
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-t border-slate-200/60">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-600">Total Bank Deposits</p>
                    <p class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">₱{{ number_format($totalBankBalance, 2) }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-pink-50 to-pink-50">
            <h3 class="font-semibold text-slate-900">Fund Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <span class="font-medium text-slate-700">Cash on Hand</span>
                        </div>
                        <span class="font-bold text-green-600">₱{{ number_format($totalCash, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center">
                                <i class="fas fa-building-columns text-white text-sm"></i>
                            </div>
                            <span class="font-medium text-slate-700">Bank Deposits</span>
                        </div>
                        <span class="font-bold text-indigo-600">₱{{ number_format($totalBankBalance, 2) }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-center p-6 bg-gradient-to-br from-pink-50 to-pink-50 rounded-xl">
                    <div class="text-center">
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wide mb-2">Total Cooperative Funds</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">₱{{ number_format($totalFunds, 2) }}</p>
                        <div class="mt-4 flex items-center justify-center gap-4 text-sm">
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="text-slate-600">Cash: {{ $totalFunds > 0 ? number_format(($totalCash / $totalFunds) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                                <span class="text-slate-600">Bank: {{ $totalFunds > 0 ? number_format(($totalBankBalance / $totalFunds) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash on Hand Modal -->
    <div x-show="showCashModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCashModal = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <form action="{{ route('admin.amount-held.cash.update') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50">
                        <h3 class="text-lg font-semibold text-slate-900">Update Cash on Hand</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">₱</span>
                                <input type="number" name="amount" step="0.01" min="0" value="{{ $cashOnHand ? $cashOnHand->amount : 0 }}" 
                                       class="w-full pl-8 pr-4 py-3 text-lg font-semibold border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-300" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description (Optional)</label>
                            <input type="text" name="description" value="{{ $cashOnHand ? $cashOnHand->description : '' }}" placeholder="e.g., Petty cash, Office funds"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-300">
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 bg-slate-50 border-t border-slate-200">
                        <button type="button" @click="showCashModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl hover:from-emerald-600 hover:to-teal-700 transition">Update Cash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bank Account Modal -->
    <div x-show="showBankModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showBankModal = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <form x-bind:action="editBank ? '{{ route('admin.amount-held.bank.update', ['fund' => '__ID__']) }}'.replace('__ID__', editBank.id) : '{{ route('admin.amount-held.bank.store') }}'" method="POST">
                    @csrf
                    <template x-if="editBank">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h3 class="text-lg font-semibold text-slate-900" x-text="editBank ? 'Update Bank Account' : 'Add Bank Account'"></h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Bank Name</label>
                            <select name="bank_name" x-model="editBank ? editBank.bank_name : ''" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300" required x-bind:disabled="editBank">
                                <option value="">Select Bank</option>
                                <option value="BDO">BDO</option>
                                <option value="Landbank">Landbank</option>
                                <option value="RSB">RSB</option>
                            </select>
                            <template x-if="editBank">
                                <input type="hidden" name="bank_name" x-bind:value="editBank.bank_name">
                            </template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Account Number (Optional)</label>
                            <input type="text" name="account_number" x-bind:value="editBank ? editBank.account_number : ''" placeholder="e.g., 1234-5678-9012"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Account Name (Optional)</label>
                            <input type="text" name="account_name" x-bind:value="editBank ? editBank.account_name : ''" placeholder="e.g., Cooperative Savings Account"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Balance Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">₱</span>
                                <input type="number" name="amount" step="0.01" min="0" x-bind:value="editBank ? editBank.amount : ''" 
                                       class="w-full pl-8 pr-4 py-3 text-lg font-semibold border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 bg-slate-50 border-t border-slate-200">
                        <button type="button" @click="showBankModal = false; editBank = null" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition" x-text="editBank ? 'Update Account' : 'Add Account'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
