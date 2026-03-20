@extends('AdminSide.layouts.admin')

@section('title', 'Member Registration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-50 mb-4">
            <i class="fas fa-user-plus text-blue-600 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Create Member Account</h2>
        <p class="text-slate-500 mt-1">Set up login credentials for new members</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-blue-500 rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Members</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $totalMembers }}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-500 rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fas fa-check text-green-900"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Active Accounts</p>
                    <p class="text-2xl font-bold text-green-900">{{ $registeredMembers->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-yellow-500 rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Review</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $pendingRegistrations->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-blue-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-200/60 bg-blue-50/50">
                <h3 class="font-semibold text-blue-900">Create New Account</h3>
                <p class="text-sm text-blue-500">Enter member details and set login credentials</p>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center gap-2 text-red-700 mb-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="font-medium">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.member-registration.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('username') border-red-500 @enderror" placeholder="Choose a username" required>
                        <p class="text-xs text-slate-500 mt-1.5">4-20 characters, letters and numbers only</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 @enderror" placeholder="member@example.com" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-500 @enderror" placeholder="Enter a secure password" required>
                        <p class="text-xs text-slate-500 mt-1.5">Minimum 8 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Re-enter password" required>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Next Steps</p>
                                <p class="text-xs text-blue-700 mt-1">Member will complete their profile on first login. You'll need to review and confirm their registration.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Pending List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-200/60 bg-red-50">
                <h3 class="font-semibold text-slate-900">Pending Confirmations</h3>
                <p class="text-sm text-red-700">{{ $pendingRegistrations->count() }} awaiting approval</p>
            </div>

            <div class="p-4 max-h-96 overflow-y-auto">
                @if($pendingRegistrations->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingRegistrations as $user)
                            <div class="p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 font-semibold text-sm">
                                            {{ $user->member ? substr($user->member->first_name, 0, 1) : '?' }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-900 truncate">
                                                @if($user->member)
                                                    {{ $user->member->first_name }} {{ $user->member->last_name }}
                                                @else
                                                    <span class="text-red-500">No record</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition review-btn" data-user='@json($user)'>
                                        Review
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-inbox text-slate-400"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-600">No pending confirmations</p>
                        <p class="text-xs text-slate-500 mt-1">All registrations confirmed</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Workflow Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-list-ol text-blue-600"></i>
                </div>
                <h4 class="font-semibold text-slate-900">Registration Workflow</h4>
            </div>
            <ol class="space-y-3 text-sm">
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                    <span class="text-slate-600">Admin creates login credentials</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                    <span class="text-slate-600">Member receives login details</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
                    <span class="text-slate-600">Member completes profile on first login</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">4</span>
                    <span class="text-slate-600">Admin reviews and confirms registration</span>
                </li>
            </ol>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-user-check text-purple-600"></i>
                </div>
                <h4 class="font-semibold text-slate-900">Profile Completion</h4>
            </div>
            <p class="text-sm text-slate-600 mb-3">Members fill in during first login:</p>
            <ul class="space-y-2 text-sm text-slate-600">
                <li class="flex items-center gap-2"><i class="fas fa-check text-purple-500 text-xs"></i> Personal Information</li>
                <li class="flex items-center gap-2"><i class="fas fa-check text-purple-500 text-xs"></i> Contact Information</li>
                <li class="flex items-center gap-2"><i class="fas fa-check text-purple-500 text-xs"></i> Identity Documents</li>
            </ul>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm cursor-pointer" id="reviewModalBackdrop"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Review Registration</h3>
            </div>
            <div id="modalContent" class="p-6"></div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3">
                <button id="closeModal" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Cancel</button>
                <form id="confirmForm" method="POST" action="{{ route('admin.member-registration.confirm') }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="user_id" id="modalUserId">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reviewModal');
    const closeModal = document.getElementById('closeModal');
    const modalContent = document.getElementById('modalContent');
    const modalUserId = document.getElementById('modalUserId');
    
    document.querySelectorAll('.review-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const user = JSON.parse(this.getAttribute('data-user'));
            modalContent.innerHTML = `
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500">Name</span>
                        <span class="font-medium text-slate-900">${user.member?.first_name || '—'} ${user.member?.last_name || ''}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500">Email</span>
                        <span class="font-medium text-slate-900">${user.email}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500">Username</span>
                        <span class="font-medium text-slate-900">${user.name}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500">Phone</span>
                        <span class="font-medium text-slate-900">${user.member?.phone || '—'}</span>
                    </div>
                </div>
            `;
            modalUserId.value = user.id;
            modal.classList.remove('hidden');
        });
    });
    
    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    document.getElementById('reviewModalBackdrop')?.addEventListener('click', () => modal.classList.add('hidden'));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) modal.classList.add('hidden'); });
});
</script>
@endsection
