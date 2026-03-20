@extends('UserSide.layouts.app')

@section('title', 'Pay Mortuary Aid')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #06b6d4 100%);">
                <h1 class="text-xl font-semibold text-white">Pay Mortuary Aid</h1>
                <p class="text-sm text-indigo-100 mt-1">Submit your mortuary aid contribution for your cooperative protection.</p>
            </div>

            <div class="px-6 py-5 space-y-4">
                @if ($errors->any())
                    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.mortuary.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">Amount (₱)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">₱</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    name="amount"
                                    id="amount"
                                    value="{{ old('amount') }}"
                                    class="w-full pl-7 pr-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    placeholder="Enter amount"
                                    required
                                >
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Typical monthly mortuary aid is a small fixed amount set by your cooperative.</p>
                        </div>

                        <div>
                            <label for="contribution_date" class="block text-sm font-medium text-slate-700 mb-1">Contribution Date</label>
                            <input
                                type="date"
                                name="contribution_date"
                                id="contribution_date"
                                value="{{ old('contribution_date', now()->toDateString()) }}"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            >
                            <p class="mt-1 text-xs text-slate-500">You can back-date within the allowed period if permitted by the office.</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-600 space-y-1">
                        <p class="font-semibold text-slate-800">Reminder</p>
                        <p>Mortuary aid contributions are recorded as <span class="font-semibold text-amber-700">pending</span> until an administrator confirms the payment.</p>
                        <p>Please keep your official receipt or proof of payment for verification if needed.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white rounded-xl shadow-sm hover:shadow-md transition" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #06b6d4 100%);">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Submit Mortuary Aid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

