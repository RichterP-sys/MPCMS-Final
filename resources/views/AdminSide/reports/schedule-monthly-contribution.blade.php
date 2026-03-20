@extends('AdminSide.layouts.admin')

@section('title', 'Monthly Interest Contribution')

@section('content')
<div class="space-y-6">
    <div class="relative overflow-hidden rounded-lg p-6 lg:p-8" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-gray-100 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl font-bold text-white">Monthly Interest Contribution</h1>
                </div>
                <p class="text-gray-100">{{ $year }}</p>
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
                <a href="{{ route('admin.reports.schedule.monthly-contribution.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-sm font-medium rounded-xl">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden print:shadow-none">
        <div class="p-6 print:p-4 overflow-x-auto">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-bold text-slate-900 mb-4">Monthly Interest Contribution - {{ $year }}</h3>

            <table class="w-full border-collapse border border-slate-300 text-sm min-w-[800px]">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border border-slate-300 px-2 py-1.5 text-left font-semibold w-10">NO</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-left font-semibold">NAMES</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">JAN</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">FEB</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">MAR</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">APR</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">MAY</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">JUNE</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">JULY</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">AUG</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">SEPT</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">OCT</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">NOV</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">DEC</th>
                        <th class="border border-slate-300 px-2 py-1.5 text-right font-semibold">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $row)
                    <tr>
                        <td class="border border-slate-300 px-2 py-1">{{ $row['no'] }}</td>
                        <td class="border border-slate-300 px-2 py-1">{{ $row['name'] }}</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-slate-300 px-2 py-1 text-right">{{ number_format($row['monthly'][$mo], 2) }}</td>
                        @endfor
                        <td class="border border-slate-300 px-2 py-1 text-right font-semibold">{{ number_format($row['total'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-slate-100 font-bold">
                        <td colspan="2" class="border border-slate-300 px-2 py-1.5">GRAND TOTAL</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($monthTotals[$mo], 2) }}</td>
                        @endfor
                        <td class="border border-slate-300 px-2 py-1.5 text-right">{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
