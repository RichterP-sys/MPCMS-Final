@extends('AdminSide.layouts.admin')

@section('title', 'Contribution Details')

@section('content')
<div class="container mx-auto px-4 py-8">
  <div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <a href="{{ route('admin.finance.index', ['tab' => 'contributions']) }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
        <i class="fas fa-arrow-left"></i>
        Back to Contributions
      </a>
      <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 bg-clip-text text-transparent">Contribution Details</h1>
      <div></div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="rounded-xl bg-indigo-50 text-indigo-600 p-3">
          <i class="fas fa-peso-sign"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500">Amount</p>
          <p class="text-xl font-semibold">₱{{ number_format($contribution->amount ?? 0, 2) }}</p>
        </div>
      </div>

      <div class="p-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="rounded-xl bg-purple-50 text-purple-600 p-3">
          <i class="fas fa-tag"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500">Type</p>
          <p class="text-base font-semibold">{{ ucfirst($contribution->contribution_type ?? 'regular') }}</p>
        </div>
      </div>

      <div class="p-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="rounded-xl bg-green-50 text-green-600 p-3">
          <i class="fas fa-info-circle"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500">Status / Date</p>
          @php
            $status = strtolower($contribution->status ?? 'pending');
            $statusClasses = [
              'approved' => 'bg-green-100 text-green-700',
              'pending' => 'bg-yellow-100 text-yellow-800',
              'rejected' => 'bg-red-100 text-red-700',
            ];
            $badgeClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-700';
          @endphp
          <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
              <i class="fas fa-circle mr-1" style="font-size:8px"></i>
              {{ ucfirst($status) }}
            </span>
            <span class="text-sm text-gray-600">{{ optional($contribution->contribution_date)->format('M d, Y') }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Member Card -->
      <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4">
          <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
            {{ strtoupper(substr($contribution->member->first_name,0,1)) }}{{ strtoupper(substr($contribution->member->last_name,0,1)) }}
          </div>
          <div>
            <p class="text-sm text-gray-500">Member</p>
            <p class="text-lg font-semibold">{{ $contribution->member->first_name }} {{ $contribution->member->last_name }}</p>
            <p class="text-sm text-gray-500"><i class="fas fa-envelope mr-1"></i>{{ $contribution->member->email ?? '—' }}</p>
          </div>
        </div>

        <div class="mt-4 space-y-2 text-sm">
          <p class="text-gray-600"><i class="fas fa-id-card mr-2 text-gray-400"></i>Member ID: {{ $contribution->member->member_id ?? '—' }}</p>
          <p class="text-gray-600"><i class="fas fa-phone mr-2 text-gray-400"></i>{{ $contribution->member->phone ?? '—' }}</p>
        </div>
      </div>

      <!-- Contribution Details Card -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
          <i class="fas fa-donate text-indigo-600"></i>
          Contribution Information
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Amount</p>
            <p class="mt-1 font-medium">₱{{ number_format($contribution->amount ?? 0, 2) }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Type</p>
            <p class="mt-1 font-medium">{{ ucfirst($contribution->contribution_type ?? 'regular') }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Status</p>
            <p class="mt-1">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
                <i class="fas fa-circle mr-1" style="font-size:8px"></i>
                {{ ucfirst($status) }}
              </span>
            </p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Date</p>
            <p class="mt-1 font-medium">{{ optional($contribution->contribution_date)->format('M d, Y') }}</p>
          </div>
          @if(!empty($contribution->description))
          <div class="md:col-span-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Description</p>
            <p class="mt-1 font-medium">{{ $contribution->description }}</p>
          </div>
          @endif
          @if(!empty($contribution->notes))
          <div class="md:col-span-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Notes</p>
            <p class="mt-1 font-medium">{{ $contribution->notes }}</p>
          </div>
          @endif
          @if(!empty($contribution->payment_method))
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Payment Method</p>
            <p class="mt-1 font-medium">{{ $contribution->payment_method }}</p>
          </div>
          @endif
          @if(!empty($contribution->reference_number))
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Reference #</p>
            <p class="mt-1 font-medium">{{ $contribution->reference_number }}</p>
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Footer Actions -->
    <div class="flex justify-end">
      <a href="{{ route('admin.finance.index', ['tab' => 'contributions']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-medium">
        <i class="fas fa-list"></i>
        Back to List
      </a>
    </div>
  </div>
</div>
@endsection


