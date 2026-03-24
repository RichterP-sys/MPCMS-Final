@extends('AdminSide.layouts.admin')

@section('title', 'Monthly Schedule of CBU')

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-blue-100 hover:text-white transition text-lg"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-3xl font-extrabold text-white drop-shadow m-0">Monthly Schedule of CBU</h1>
                </div>
                <p class="text-blue-100/90 text-base font-medium">{{ $year }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('admin.reports.schedule') }}" class="flex items-center gap-2">
                    <label class="text-base text-white font-semibold">Year:</label>
                    <select name="year" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-white border border-emerald-200 text-emerald-900 text-base font-semibold focus:ring-2 focus:ring-emerald-400 shadow-sm">
                        @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.reports.schedule.monthly-cbu.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 border border-emerald-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 border border-emerald-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-emerald-100 overflow-hidden print:shadow-none">
        <div class="p-8 print:p-4 overflow-x-auto">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-extrabold text-emerald-800 text-xl mb-4" style="color: #047857 !important;">MONTHLY SCHED. OF CBU - {{ $year }}</h3>

            @if($members->isEmpty())
            <div class="text-center py-12 text-emerald-500">No CBU data for {{ $year }}.</div>
            @else
            <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-emerald-200 text-base min-w-[1100px]">
                <thead>
                    <tr class="bg-emerald-100">
                        <th class="border border-emerald-200 px-3 py-2 text-left font-bold w-10 text-emerald-900">NO</th>
                        <th class="border border-emerald-200 px-3 py-2 text-left font-bold text-emerald-900">NAMES</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">JAN</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">FEB</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">MAR</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">APR</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">MAY</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">JUNE</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">JULY</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">AUG</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">SEPT</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">OCT</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">NOV</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">DEC</th>
                        <th class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $row)
                    <tr class="hover:bg-emerald-50/60 transition-colors">
                        <td class="border border-emerald-200 px-3 py-2 text-emerald-900 font-semibold">{{ $row['no'] }}</td>
                        <td class="border border-emerald-200 px-3 py-2 text-emerald-900 font-semibold">{{ $row['name'] }}</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-emerald-200 px-3 py-2 text-right text-emerald-800">{{ number_format($row['monthly'][$mo], 2) }}</td>
                        @endfor
                        <td class="border border-emerald-200 px-3 py-2 text-right font-bold text-emerald-900">{{ number_format($row['total'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-emerald-100 font-extrabold">
                        <td colspan="2" class="border border-emerald-200 px-3 py-2 text-emerald-900">GRAND TOTAL</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-emerald-200 px-3 py-2 text-right text-emerald-900">{{ number_format($monthTotals[$mo], 2) }}</td>
                        @endfor
                        <td class="border border-emerald-200 px-3 py-2 text-right text-emerald-900">{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
