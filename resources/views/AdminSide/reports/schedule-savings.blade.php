@extends('AdminSide.layouts.admin')

@section('title', 'Schedule of Savings Deposit')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl shadow-lg p-6 lg:p-8" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #3b82f6 100%);">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.reports.schedule') }}" class="text-white hover:text-blue-100 transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-2xl font-bold text-white drop-shadow">Schedule of Savings Deposit</h1>
                </div>
                <p class="text-white/90 font-medium">For the Month of Jan. to Dec. 31, <span class="font-bold">{{ $year }}</span></p>
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
                <a href="{{ route('admin.reports.schedule.savings.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-sm font-semibold rounded-xl shadow">
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
        <div class="p-6 print:p-4">
            @include('AdminSide.reports.partials.schedule-header')
            <h3 class="text-center font-bold text-blue-900 text-xl mb-2 tracking-wide">SCHEDULE OF SAVINGS DEPOSIT</h3>
            <p class="text-center text-sm text-blue-700 mb-4">For the Month of Jan. to Dec. 31, <span class="font-semibold">{{ $year }}</span></p>
            @if(!$blocks->isEmpty())
            <div class="flex justify-end mb-2">
                <span class="font-bold text-blue-900 text-lg">GRAND TOTAL: {{ number_format($grandTotal, 2) }}</span>
            </div>
            @endif

            @if($blocks->isEmpty())
            <div class="text-center py-12 text-blue-400">No savings deposit data for {{ $year }}.</div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($blocks as $block)
                <div class="rounded-xl bg-white/90 border border-blue-200 shadow flex flex-col overflow-hidden">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-100">
                                <th class="border border-blue-200 px-2 py-1.5 text-left font-semibold w-10 text-blue-900">No</th>
                                <th class="border border-blue-200 px-2 py-1.5 text-left font-semibold text-blue-900">NAME</th>
                                <th class="border border-blue-200 px-2 py-1.5 text-right font-semibold text-blue-900">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($block as $row)
                            <tr>
                                <td class="border border-blue-200 px-2 py-1 text-blue-900">{{ $row['no'] }}</td>
                                <td class="border border-blue-200 px-2 py-1 text-blue-900">{{ $row['name'] }}</td>
                                <td class="border border-blue-200 px-2 py-1 text-right text-blue-900">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-blue-100 font-bold">
                                <td colspan="2" class="border border-blue-200 px-2 py-1.5 text-blue-900">TOTAL</td>
                                <td class="border border-blue-200 px-2 py-1.5 text-right text-blue-900">{{ number_format($block->sum('amount'), 2) }}</td>
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
