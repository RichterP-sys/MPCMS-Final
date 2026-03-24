@extends('AdminSide.layouts.admin')

@section('title', 'Schedule Report')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-blue-100 hover:text-white transition text-lg"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-3xl font-extrabold text-white drop-shadow">Schedule Reports</h1>
                </div>
                <p class="text-blue-100/90 text-base font-medium">Cash on Hand, Loans Receivable & CBU/Savings Schedules</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('admin.reports.schedule') }}" class="flex items-center gap-2">
                    <label class="text-base text-white font-semibold">Year:</label>
                    <select name="year" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-white border border-blue-200 text-blue-900 text-base font-semibold focus:ring-2 focus:ring-blue-400 shadow-sm">
                        @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.reports.schedule.export', request()->all()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>

    <div class="print:space-y-8">
        <!-- SCHEDULE OF CASH ON HAND / LOANS RECEIVABLE -->
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden print:shadow-none print:border">
            <div class="px-8 py-5 border-b border-blue-100 bg-blue-50/80">
                <h2 class="text-xl font-extrabold text-blue-900 text-center">SCHEDULE OF CASH ON HAND / LOANS RECEIVABLE</h2>
                <p class="text-base text-blue-700/80 text-center mt-1">As of Dec. 31, {{ $year }}</p>
            </div>

            <div class="p-6 space-y-8">
                <!-- CASH ON HAND Table -->
                <div>
                    <h3 class="text-lg font-bold text-blue-900 mb-3">CASH ON HAND</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-blue-200 text-base rounded-xl overflow-hidden">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th class="border border-blue-200 px-4 py-2 text-left font-bold">Date</th>
                                    <th class="border border-blue-200 px-4 py-2 text-right font-bold">Cash Collection</th>
                                    <th class="border border-blue-200 px-4 py-2 text-right font-bold">Cash Disbursement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashOnHand as $row)
                                <tr>
                                    <td class="border border-blue-200 px-4 py-2">{{ $row['date'] }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($row['collection'], 2) }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($row['disbursement'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-blue-50 font-bold">
                                    <td class="border border-blue-200 px-4 py-2">Total</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($totalCashCollection, 2) }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($totalCashDisbursement, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 max-w-md ml-auto space-y-1 text-base">
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Total Cash Collection</span>
                            <span class="font-medium">{{ number_format($totalCashCollection, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Add. Bal. Beg.</span>
                            <span class="font-medium">{{ number_format($cashBalBeg, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span></span>
                            <span class="font-medium">{{ number_format($totalCashCollection + $cashBalBeg, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Less Cash Disbursement</span>
                            <span class="font-medium">{{ number_format($totalCashDisbursement, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1 font-bold">
                            <span>Bal. End</span>
                            <span>{{ number_format($cashBalEnd, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- LOANS RECEIVABLE Table -->
                <div>
                    <h3 class="text-lg font-bold text-blue-900 mb-3">LOANS RECEIVABLE</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-blue-200 text-base rounded-xl overflow-hidden">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th class="border border-blue-200 px-4 py-2 text-left font-bold">Date</th>
                                    <th class="border border-blue-200 px-4 py-2 text-right font-bold">Loan Releases</th>
                                    <th class="border border-blue-200 px-4 py-2 text-right font-bold">Loan Repayment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loansReceivable as $row)
                                <tr>
                                    <td class="border border-blue-200 px-4 py-2">{{ $row['date'] }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($row['releases'], 2) }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($row['repayment'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-blue-50 font-bold">
                                    <td class="border border-blue-200 px-4 py-2">Total</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($totalLoanReleases, 2) }}</td>
                                    <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($totalLoanRepayment, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 max-w-md ml-auto space-y-1 text-base">
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Total Releases</span>
                            <span class="font-medium">{{ number_format($totalLoanReleases, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Add. Bal. Beg.</span>
                            <span class="font-medium">{{ number_format($loansBalBeg, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span></span>
                            <span class="font-medium">{{ number_format($totalLoanReleases + $loansBalBeg, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Shor & Overstated</span>
                            <span class="font-medium">{{ number_format($loansShortOver, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span></span>
                            <span class="font-medium">{{ number_format($totalLoanReleases + $loansBalBeg + $loansShortOver, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 py-1">
                            <span>Less Loan Repayment</span>
                            <span class="font-medium">{{ number_format($totalLoanRepayment, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1 font-bold bg-slate-200">
                            <span>Bal. End</span>
                            <span class="text-slate-900">{{ number_format($loansBalEnd, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCHEDULE OF CBU/SAVINGS/SSFD -->
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden print:shadow-none print:border">
            <div class="px-8 py-5 border-b border-blue-100 bg-blue-50/80">
                <h2 class="text-xl font-extrabold text-blue-900 text-center">SCHEDULE OF CBU/SAVINGS/SSFD</h2>
                <p class="text-base text-blue-700/80 text-center mt-1">As of Dec. 31, {{ $year }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($cbuSavingsData as $key => $data)
                    <div>
                        <h3 class="text-base font-bold text-blue-900 mb-3 uppercase">{{ $data['label'] }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-blue-200 text-sm rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="bg-blue-50">
                                        <th class="border border-blue-200 px-3 py-2 text-left font-bold">Date</th>
                                        <th class="border border-blue-200 px-3 py-2 text-right font-bold">Withdraw</th>
                                        <th class="border border-blue-200 px-3 py-2 text-right font-bold">Contribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['monthly'] as $row)
                                    <tr>
                                        <td class="border border-blue-200 px-3 py-2">{{ $row['date'] }}</td>
                                        <td class="border border-blue-200 px-3 py-2 text-right">{{ number_format($row['withdraw'], 2) }}</td>
                                        <td class="border border-blue-200 px-3 py-2 text-right">{{ number_format($row['contribution'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-blue-50 font-bold">
                                        <td class="border border-blue-200 px-3 py-2">Total</td>
                                        <td class="border border-blue-200 px-3 py-2 text-right">{{ number_format($data['total_withdraw'], 2) }}</td>
                                        <td class="border border-blue-200 px-3 py-2 text-right">{{ number_format($data['total_contribution'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 space-y-0.5 text-xs">
                            <div class="flex justify-between">
                                <span>Total Contribution</span>
                                <span>{{ number_format($data['total_contribution'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Add. Bal. Beg</span>
                                <span>{{ number_format($data['bal_beg'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span></span>
                                <span class="font-medium">{{ number_format($data['total_contribution'] + $data['bal_beg'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Less Withdrawal</span>
                                <span>{{ number_format($data['total_withdraw'], 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold pt-1">
                                <span>Bal. End</span>
                                <span>{{ number_format($data['bal_end'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Other Schedule Reports -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-blue-100 bg-blue-50/80">
            <h3 class="font-extrabold text-blue-900">Other Schedule Reports</h3>
            <p class="text-sm text-blue-700/80 mt-0.5">Member-level schedules with multi-column layout</p>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.reports.schedule.cbu', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-blue-100 hover:border-indigo-400 hover:bg-indigo-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-200 to-purple-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-building-columns text-indigo-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-blue-900">Schedule of Capital Build Up</p>
                        <p class="text-xs text-blue-700/80">CBU amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.loans-receivable', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-yellow-100 hover:border-amber-400 hover:bg-amber-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-200 to-yellow-300 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-file-invoice-dollar text-yellow-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-yellow-900">Schedule of Loan Receivables</p>
                        <p class="text-xs text-yellow-700/80">Outstanding balances by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.savings', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-emerald-100 hover:border-emerald-400 hover:bg-emerald-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-200 to-emerald-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-piggy-bank text-emerald-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Schedule of Savings Deposit</p>
                        <p class="text-xs text-emerald-700/80">Members savings by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.ssfd', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-indigo-100 hover:border-indigo-400 hover:bg-indigo-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-200 to-indigo-300 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-coins text-indigo-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-indigo-900">Special Savings Fund Deposit (SSFD)</p>
                        <p class="text-xs text-indigo-700/80">SSFD amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.mortuary-aid', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-rose-100 hover:border-rose-400 hover:bg-rose-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-200 to-rose-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-hand-holding-heart text-rose-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-rose-900">Schedule of Mortuary Aid</p>
                        <p class="text-xs text-rose-700/80">Mortuary aid contributions by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-mortuary-aid', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-rose-100 hover:border-rose-400 hover:bg-rose-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-200 to-rose-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-calendar-alt text-rose-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-rose-900">Monthly Schedule of Mortuary Aid</p>
                        <p class="text-xs text-rose-700/80">Jan–Dec mortuary aid by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-cbu', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-emerald-100 hover:border-emerald-400 hover:bg-emerald-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-200 to-emerald-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-calendar-alt text-emerald-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Monthly Schedule of CBU</p>
                        <p class="text-xs text-emerald-700/80">Jan–Dec CBU by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.interest-contribution', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-purple-100 hover:border-purple-400 hover:bg-purple-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-200 to-purple-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-percent text-purple-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-purple-900">Schedule of Individual Interest Contribution</p>
                        <p class="text-xs text-purple-700/80">Dividend amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.contributions', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-blue-100 hover:border-blue-400 hover:bg-blue-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-200 to-indigo-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-list-alt text-blue-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-blue-900">Contributions</p>
                        <p class="text-xs text-blue-700/80">CONTRIBUTIONS {{ $year }} – Jan–Dec by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-contribution', ['year' => $year]) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-blue-100 hover:border-blue-400 hover:bg-blue-50/60 transition shadow group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-200 to-indigo-200 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-calendar-alt text-blue-700 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-blue-900">Monthly Interest Contribution</p>
                        <p class="text-xs text-blue-700/80">Jan–Dec breakdown by member</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Optional: Beginning balance inputs (for custom values) -->
    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
        <p class="text-sm text-slate-600">
            <i class="fas fa-info-circle text-indigo-500 mr-1"></i>
            Beginning balances (Add. Bal. Beg.) default to 0. To use custom values, add query parameters: 
            <code class="text-xs bg-slate-200 px-1 rounded">cash_beg</code>, 
            <code class="text-xs bg-slate-200 px-1 rounded">loans_beg</code>, 
            <code class="text-xs bg-slate-200 px-1 rounded">loans_short_over</code>, 
            <code class="text-xs bg-slate-200 px-1 rounded">cbu_beg</code>, 
            <code class="text-xs bg-slate-200 px-1 rounded">ssfd_beg</code>, 
            <code class="text-xs bg-slate-200 px-1 rounded">savings_beg</code>
        </p>
    </div>
</div>

<style>
@media print {
    .print\:space-y-8 > * + * { margin-top: 2rem; }
    .print\:shadow-none { box-shadow: none; }
    .print\:border { border: 1px solid #cbd5e1; }
}
</style>
@endsection
