@extends('AdminSide.layouts.admin')

@section('title', 'Edit Loan')

@section('content')
<div class="container mx-auto px-4 py-8">
	<div class="max-w-2xl mx-auto">
		<div class="flex justify-between items-center mb-6">
			<h1 class="text-3xl font-bold text-gray-900">Edit Loan</h1>
			<a href="{{ route('admin.finance.index', ['tab' => 'loans']) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
				Back to Loans
			</a>
		</div>

		<div class="bg-white shadow-md rounded-lg p-6">
			@if ($errors->any())
				<div class="mb-4">
					<ul class="list-disc list-inside text-red-600">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form action="{{ route('admin.loans.update', $loan) }}" method="POST">
				@csrf
				@method('PUT')

				<div class="mb-4">
					<label for="member_id" class="block text-gray-700 font-bold mb-2">Member</label>
					<select name="member_id" id="member_id" class="form-select w-full border-gray-300 rounded" required>
						<option value="">Select Member</option>
						@foreach($members as $member)
							<option value="{{ $member->id }}" {{ old('member_id', $loan->member_id) == $member->id ? 'selected' : '' }}>
								{{ $member->first_name }} {{ $member->last_name }} ({{ $member->email }})
							</option>
						@endforeach
					</select>
					@error('member_id')
						<span class="text-red-600 text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="amount" class="block text-gray-700 font-bold mb-2">Amount</label>
					<div class="relative">
						<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 pointer-events-none">₱</span>
						<input type="number" name="amount" id="amount" value="{{ old('amount', $loan->amount) }}" class="form-input w-full border-gray-300 rounded pl-8" required min="0" step="0.01" placeholder="0.00">
					</div>
					@error('amount')
						<span class="text-red-600 text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="application_date" class="block text-gray-700 font-bold mb-2">Application Date</label>
					<input type="date" name="application_date" id="application_date" class="form-input w-full border-gray-300 rounded" value="{{ old('application_date', optional($loan->application_date)->format('Y-m-d')) }}" required>
					@error('application_date')
						<span class="text-red-600 text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="flex justify-end">
					<button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
						Update Loan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


