@extends('AdminSide.layouts.admin')

@section('title', 'Contributions')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl shadow-lg p-6 lg:p-8" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #3b82f6 100%);">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-white hover:text-blue-100 transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl font-bold text-white drop-shadow">Contributions</h1>
                </div>
                <p class="text-white/90 font-medium"><span class="font-bold">{{ $year }}</span></p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('admin.reports.schedule') }}" class="flex items-center gap-2">
                    <label class="text-sm text-white font-semibold">Year:</label>
                    <select name="year" onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-white/90 border border-blue-200 text-blue-900 text-sm font-semibold focus:ring-2 focus:ring-blue-500 shadow">
                        @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.reports.schedule.contributions.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-sm font-semibold rounded-xl shadow">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-sm font-semibold rounded-xl shadow">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Card/Table Section -->
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden print:shadow-none">
        <div class="p-6 print:p-4 overflow-x-auto">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-bold text-blue-900 text-xl mb-4 tracking-wide">CONTRIBUTIONS {{ $year }}</h3>

            @if($members->isEmpty())
            <div class="text-center py-12 text-blue-400">No contribution data for {{ $year }}.</div>
            @else
            <table class="w-full border-collapse border border-blue-200 text-sm min-w-[800px] bg-white/90">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="border border-blue-200 px-2 py-1.5 text-left font-semibold w-10 text-blue-900">NO</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-left font-semibold text-blue-900">NAMES</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">JAN</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">FEB</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">MAR</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">APR</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">MAY</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">JUN</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">JUL</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">AUG</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">SEPT</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">OCT</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">NOV</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">DEC</th>
                        <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $row)
                    <tr>
                        <td class="border border-blue-200 px-2 py-1 text-blue-900">{{ $row['no'] }}</td>
                        <td class="border border-blue-200 px-2 py-1 text-blue-900">{{ $row['name'] }}</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-blue-200 px-2 py-1 text-right text-blue-900">{{ number_format($row['monthly'][$mo], 2) }}</td>
                        @endfor
                        <td class="border border-blue-200 px-2 py-1 text-right font-semibold text-blue-900">{{ number_format($row['total'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-blue-100 font-bold">
                        <td colspan="2" class="border border-blue-200 px-2 py-1.5 text-blue-900">TOTAL</td>
                        @for($mo = 1; $mo <= 12; $mo++)
                        <td class="border border-blue-200 px-2 py-1.5 text-right text-blue-900">{{ number_format($monthTotals[$mo], 2) }}</td>
                        @endfor
                        <td class="border border-blue-200 px-2 py-1.5 text-right text-blue-900">{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
