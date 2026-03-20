@extends('AdminSide.layouts.admin')

@section('title', 'Record Contribution')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Record Contribution</h1>
            <a href="{{ route('admin.finance.index', ['tab' => 'contributions']) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Contributions
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

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

            <form action="{{ route('admin.contributions.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="member_id" class="block text-gray-700 font-bold mb-2">Member</label>
                    <select name="member_id" id="member_id" class="form-select w-full border-gray-300 rounded" required>
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }} ({{ $member->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Contribution Type --}}
                <div class="mb-4">
                    <label for="contribution_type" class="block text-gray-700 font-bold mb-2">Contribution Type</label>
                    <select name="contribution_type" id="contribution_type" class="form-select w-full border-gray-300 rounded" required>
                        <option value="">Select Type</option>
                        <option value="regular" {{ old('contribution_type') == 'regular' ? 'selected' : '' }}>Regular (CBU)</option>
                        <option value="special" {{ old('contribution_type') == 'special' ? 'selected' : '' }}>Special (SSFD)</option>
                        <option value="emergency" {{ old('contribution_type') == 'emergency' ? 'selected' : '' }}>Emergency (Savings)</option>
                        <option value="mortuary" {{ old('contribution_type') == 'mortuary' ? 'selected' : '' }}>Mortuary Aid</option>
                    </select>
                    @error('contribution_type')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-gray-700 font-bold mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 pointer-events-none">₱</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="form-input w-full border-gray-300 rounded pl-8" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    @error('amount')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contribution_date" class="block text-gray-700 font-bold mb-2">Contribution Date</label>
                    <input type="date" name="contribution_date" id="contribution_date" class="form-input w-full border-gray-300 rounded" value="{{ old('contribution_date', date('Y-m-d')) }}" required>
                    @error('contribution_date')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
                    <select name="status" id="status" class="form-select w-full border-gray-300 rounded" required>
                        <option value="">Select Status</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        Record Contribution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection