@extends('AdminSide.layouts.admin')

@section('title', 'Schedule Report')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-gray-600 rounded-lg p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-gray-100 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">Schedule Reports</h1>
                </div>
                <p class="text-gray-100">Cash on Hand, Loans Receivable & CBU/Savings Schedules</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('admin.reports.schedule') }}" class="flex items-center gap-2">
                    <label class="text-sm text-white">Year:</label>
                    <select name="year" onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.reports.schedule.export', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-sm font-medium rounded-lg transition">
                    <i class="fas fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-sm font-medium rounded-lg transition">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>

    <div class="print:space-y-8">
        <!-- SCHEDULE OF CASH ON HAND / LOANS RECEIVABLE -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden print:shadow-none print:border">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-900 text-center">SCHEDULE OF CASH ON HAND / LOANS RECEIVABLE</h2>
                <p class="text-sm text-slate-600 text-center mt-1">As of Dec. 31, {{ $year }}</p>
            </div>

            <div class="p-6 space-y-8">
                <!-- CASH ON HAND Table -->
                <div>
                    <h3 class="text-base font-bold text-slate-800 mb-3">CASH ON HAND</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-300 text-sm">
                            <thead>
                                <tr class="bg-slate-100">
                                    <th class="border border-slate-300 px-3 py-2 text-left font-semibold">Date</th>
                                    <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Cash Collection</th>
                                    <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Cash Disbursement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashOnHand as $row)
                                <tr>
                                    <td class="border border-slate-300 px-3 py-2">{{ $row['date'] }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($row['collection'], 2) }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($row['disbursement'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-slate-50 font-semibold">
                                    <td class="border border-slate-300 px-3 py-2">Total</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($totalCashCollection, 2) }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($totalCashDisbursement, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 max-w-md ml-auto space-y-1 text-sm">
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
                    <h3 class="text-base font-bold text-slate-800 mb-3">LOANS RECEIVABLE</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-300 text-sm">
                            <thead>
                                <tr class="bg-slate-100">
                                    <th class="border border-slate-300 px-3 py-2 text-left font-semibold">Date</th>
                                    <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Loan Releases</th>
                                    <th class="border border-slate-300 px-3 py-2 text-right font-semibold">Loan Repayment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loansReceivable as $row)
                                <tr>
                                    <td class="border border-slate-300 px-3 py-2">{{ $row['date'] }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($row['releases'], 2) }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($row['repayment'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-slate-50 font-semibold">
                                    <td class="border border-slate-300 px-3 py-2">Total</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($totalLoanReleases, 2) }}</td>
                                    <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($totalLoanRepayment, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 max-w-md ml-auto space-y-1 text-sm">
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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden print:shadow-none print:border">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-900 text-center">SCHEDULE OF CBU/SAVINGS/SSFD</h2>
                <p class="text-sm text-slate-600 text-center mt-1">As of Dec. 31, {{ $year }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($cbuSavingsData as $key => $data)
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">{{ $data['label'] }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-slate-300 text-xs">
                                <thead>
                                    <tr class="bg-slate-100">
                                        <th class="border border-slate-300 px-2 py-1.5 text-left font-semibold">Date</th>
                                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">Withdraw</th>
                                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">Contribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['monthly'] as $row)
                                    <tr>
                                        <td class="border border-slate-300 px-2 py-1.5">{{ $row['date'] }}</td>
                                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($row['withdraw'], 2) }}</td>
                                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($row['contribution'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-50 font-semibold">
                                        <td class="border border-slate-300 px-2 py-1.5">Total</td>
                                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($data['total_withdraw'], 2) }}</td>
                                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($data['total_contribution'], 2) }}</td>
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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-semibold text-slate-900">Other Schedule Reports</h3>
            <p class="text-xs text-slate-500 mt-0.5">Member-level schedules with multi-column layout</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.reports.schedule.cbu', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-building-columns text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Schedule of Capital Build Up</p>
                        <p class="text-xs text-slate-500">CBU amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.loans-receivable', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-amber-300 hover:bg-amber-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Schedule of Loan Receivables</p>
                        <p class="text-xs text-slate-500">Outstanding balances by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.savings', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-piggy-bank text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Schedule of Savings Deposit</p>
                        <p class="text-xs text-slate-500">Members savings by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.ssfd', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Special Savings Fund Deposit (SSFD)</p>
                        <p class="text-xs text-slate-500">SSFD amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.mortuary-aid', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Schedule of Mortuary Aid</p>
                        <p class="text-xs text-slate-500">Mortuary aid contributions by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-mortuary-aid', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Monthly Schedule of Mortuary Aid</p>
                        <p class="text-xs text-slate-500">Jan–Dec mortuary aid by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-cbu', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Monthly Schedule of CBU</p>
                        <p class="text-xs text-slate-500">Jan–Dec CBU by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.interest-contribution', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center">
                        <i class="fas fa-percent text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Schedule of Individual Interest Contribution</p>
                        <p class="text-xs text-slate-500">Dividend amounts by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.contributions', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <i class="fas fa-list-alt text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Contributions</p>
                        <p class="text-xs text-slate-500">CONTRIBUTIONS {{ $year }} – Jan–Dec by member</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.schedule.monthly-contribution', ['year' => $year]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Monthly Interest Contribution</p>
                        <p class="text-xs text-slate-500">Jan–Dec breakdown by member</p>
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
