@extends('AdminSide.layouts.admin')

@section('title', 'Schedule of Capital Build Up')

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-blue-100 hover:text-white transition text-lg"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-3xl font-extrabold text-white drop-shadow">Schedule of Capital Build Up</h1>
                </div>
                <p class="text-blue-100/90 text-base font-medium">For the Month of Jan. to Dec. 31, {{ $year }}</p>
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
                <a href="{{ route('admin.reports.schedule.cbu.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white text-base font-semibold rounded-xl shadow transition">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 overflow-hidden print:shadow-none">
        <div class="p-8 print:p-4">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-extrabold text-blue-900 text-xl mb-2">SCHEDULE OF CAPITAL BUILD UP</h3>
            <p class="text-center text-base text-blue-700/80 mb-4">For the Month of Jan. to Dec. 31, {{ $year }}</p>
            @if($blocks->isEmpty())
            <div class="text-center py-12 text-slate-500">No Capital Build Up data for {{ $year }}.</div>
            @else
            <div class="flex justify-end mb-4">
                <span class="font-extrabold text-blue-900 text-lg">GRAND TOTAL: {{ number_format($grandTotal, 2) }}</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($blocks as $block)
                <div class="border border-blue-200 rounded-xl shadow group hover:shadow-xl transition overflow-hidden">
                    <table class="w-full border-collapse text-base rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="border border-blue-200 px-4 py-2 text-left font-bold w-10">No.</th>
                                <th class="border border-blue-200 px-4 py-2 text-left font-bold">NAME</th>
                                <th class="border border-blue-200 px-4 py-2 text-right font-bold">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($block as $row)
                            <tr class="hover:bg-blue-100/60 transition-colors">
                                <td class="border border-blue-200 px-4 py-2">{{ $row['no'] }}</td>
                                <td class="border border-blue-200 px-4 py-2">{{ $row['name'] }}</td>
                                <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-blue-50 font-bold">
                                <td colspan="2" class="border border-blue-200 px-4 py-2">TOTAL</td>
                                <td class="border border-blue-200 px-4 py-2 text-right">{{ number_format($block->sum('amount'), 2) }}</td>
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
