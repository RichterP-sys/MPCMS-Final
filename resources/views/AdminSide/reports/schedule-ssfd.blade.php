@extends('AdminSide.layouts.admin')

@section('title', 'Special Savings Fund Deposit (SSFD)')

@section('content')
<div class="space-y-6">
    <div class="relative overflow-hidden rounded-lg p-6 lg:p-8" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-gray-100 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl font-bold text-white">Special Savings Fund Deposit (SSFD)</h1>
                </div>
                <p class="text-gray-100">For the Month of Jan. to Dec. 31, {{ $year }}</p>
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
                <a href="{{ route('admin.reports.schedule.ssfd.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden print:shadow-none">
        <div class="p-6 print:p-4">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-bold text-slate-900 mb-2">SPECIAL SAVINGS FUND DEPOSIT (SSFD)</h3>
            <p class="text-center text-sm text-slate-600 mb-4">For the Month of Jan. to Dec. 31, {{ $year }}</p>
            @if(!$blocks->isEmpty())
            <div class="flex justify-end mb-2">
                <span class="font-bold text-slate-900">GRAND TOTAL: {{ number_format($grandTotal, 2) }}</span>
            </div>
            @endif

            @if($blocks->isEmpty())
            <div class="text-center py-12 text-slate-500">No SSFD data for {{ $year }}.</div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($blocks as $block)
                <div class="border border-slate-300">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-indigo-100">
                                <th class="border border-slate-300 px-2 py-1.5 text-left font-semibold w-10">No.</th>
                                <th class="border border-slate-300 px-2 py-1.5 text-left font-semibold">Names</th>
                                <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($block as $row)
                            <tr>
                                <td class="border border-slate-300 px-2 py-1">{{ $row['no'] }}</td>
                                <td class="border border-slate-300 px-2 py-1">{{ $row['name'] }}</td>
                                <td class="border border-slate-300 px-2 py-1 text-right">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-indigo-100 font-bold">
                                <td colspan="2" class="border border-slate-300 px-2 py-1.5">TOTAL</td>
                                <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($block->sum('amount'), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
