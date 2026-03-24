@extends('UserSide.layouts.guest')

@section('content')
@php
    // Prefer the member guard (this page is shown after member login)
    $user = auth('member')->user() ?? auth()->user();
    $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
    $displayName = $displayName !== '' ? $displayName : ($user->name ?? 'there');
    $brandName = config('app.name', 'MPCMS');
    $theme = request('theme', 'indigo');
    $themes = [
        'indigo' => ['from' => 'from-indigo-500', 'via' => 'via-sky-500', 'to' => 'to-blue-600', 'accent' => 'text-indigo-600', 'ring' => 'focus:ring-indigo-500', 'border' => 'focus:border-indigo-500', 'button' => 'bg-indigo-600 hover:bg-indigo-700'],
        'emerald' => ['from' => 'from-emerald-500', 'via' => 'via-teal-500', 'to' => 'to-cyan-600', 'accent' => 'text-emerald-600', 'ring' => 'focus:ring-emerald-500', 'border' => 'focus:border-emerald-500', 'button' => 'bg-emerald-600 hover:bg-emerald-700'],
        'rose' => ['from' => 'from-rose-500', 'via' => 'via-pink-500', 'to' => 'to-fuchsia-600', 'accent' => 'text-rose-600', 'ring' => 'focus:ring-rose-500', 'border' => 'focus:border-rose-500', 'button' => 'bg-rose-600 hover:bg-rose-700'],
    ];
    $themeConfig = $themes[$theme] ?? $themes['indigo'];
@endphp
<style>
    @keyframes floatSlow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(12px); } 100% { opacity: 1; transform: translateY(0); } }
    .float-slow { animation: floatSlow 6s ease-in-out infinite; }
    .fade-in-up { animation: fadeInUp 600ms ease-out both; }
</style>

<div class="min-h-screen bg-gradient-to-br {{ $themeConfig['from'] }} {{ $themeConfig['via'] }} {{ $themeConfig['to'] }} flex items-center justify-center py-10 px-4 sm:px-6 lg:px-10">
    <div class="w-full max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 items-stretch">
        <div class="hidden lg:flex flex-col justify-between rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-8 text-white shadow-2xl float-slow">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                    MPCMS Account
                </span>
                <h1 class="mt-6 text-3xl font-bold leading-tight">
                    Welcome!
                </h1>
                <p class="mt-3 text-sm text-white/80">
                    Complete your profile to unlock personalized features and faster approvals.
                </p>

                <!-- Profile Status Progress Box -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-4">
                    <div class="flex flex-col space-y-4">
                        <!-- Step 1: Registration -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="h-1 bg-green-500 rounded"></div>
                            </div>
                            <span class="ml-3 text-xs font-bold text-gray-900">Registration</span>
                        </div>

                        <!-- Step 2: Profile Completion -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white flex-shrink-0 animate-pulse">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="h-1 bg-gradient-to-r from-blue-500 to-gray-300 rounded"></div>
                            </div>
                            <span class="ml-3 text-xs font-bold text-blue-600">In Progress</span>
                        </div>

                        <!-- Step 3: Admin Approval -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="h-1 bg-gray-300 rounded"></div>
                            </div>
                            <span class="ml-3 text-xs font-bold text-gray-500">Waiting</span>
                        </div>

                        <!-- Step 4: Account Active -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="h-1 bg-gray-300 rounded"></div>
                            </div>
                            <span class="ml-3 text-xs font-bold text-gray-500">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="inline-block h-2 w-2 rounded-full bg-white/80"></span>
                    <span>Profile status: <strong class="text-white">Pending completion</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block h-2 w-2 rounded-full bg-white/80"></span>
                    <span>Email: {{ $user->email ?? 'Not provided' }}</span>
                </div>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('user.logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-white/30 text-sm font-semibold rounded-lg text-white bg-white/10 hover:bg-white/20 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full rounded-2xl bg-white shadow-2xl p-6 sm:p-8 lg:p-10 fade-in-up flex flex-col max-h-[85vh]">
            @if (session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="text-center lg:text-left mb-4">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Complete your profile
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Hi {{ $displayName }}, confirm your details to finish setup.
                </p>
            </div>

            <div class="overflow-y-auto flex-1 pr-2">
                <form class="space-y-5" action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            
                <!-- Profile Photo -->
                <div>
                    <label for="profile_photo" class="block text-xs font-semibold text-gray-600 mb-2">Profile Photo</label>
                    <input
                        id="profile_photo"
                        name="profile_photo"
                        type="file"
                        accept="image/*"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                    >
                    @error('profile_photo')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                    @if(!empty($user->profile_photo))
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="mt-2 w-20 h-20 rounded-full object-cover border border-gray-300">
                    @endif
                </div>

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-xs font-semibold text-gray-600 mb-2">First Name</label>
                <input
                    id="first_name"
                    name="first_name"
                    type="text"
                    autocomplete="given-name"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="First Name"
                    value="{{ old('first_name', $user->first_name ?? '') }}"
                >
                @error('first_name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-xs font-semibold text-gray-600 mb-2">Last Name</label>
                <input
                    id="last_name"
                    name="last_name"
                    type="text"
                    autocomplete="family-name"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Last Name"
                    value="{{ old('last_name', $user->last_name ?? '') }}"
                >
                @error('last_name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone_number" class="block text-xs font-semibold text-gray-600 mb-2">Phone</label>
                <input
                    id="phone_number"
                    name="phone_number"
                    type="tel"
                    autocomplete="tel"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Phone Number"
                    value="{{ old('phone_number', $user->phone ?? '') }}"
                >
                @error('phone_number')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-semibold text-gray-600 mb-2">Address</label>
                <input
                    id="address"
                    name="address"
                    type="text"
                    autocomplete="street-address"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Address"
                    value="{{ old('address', $user->address ?? '') }}"
                >
                @error('address')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nature of Work -->
            <div>
                <label for="nature_of_work" class="block text-xs font-semibold text-gray-600 mb-2">Nature of Work</label>
                <input
                    id="nature_of_work"
                    name="nature_of_work"
                    type="text"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Self-employed, Farmer, etc."
                    value="{{ old('nature_of_work', $user->nature_of_work ?? '') }}"
                >
                @error('nature_of_work')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Employer/Business Name -->
            <div>
                <label for="employer_business_name" class="block text-xs font-semibold text-gray-600 mb-2">Employer or Business</label>
                <input
                    id="employer_business_name"
                    name="employer_business_name"
                    type="text"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Employer or Business Name"
                    value="{{ old('employer_business_name', $user->employer_business_name ?? '') }}"
                >
                @error('employer_business_name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date of Employment -->
            <div>
                <label for="date_of_employment" class="block text-xs font-semibold text-gray-600 mb-2">Date of Employment</label>
                <input
                    id="date_of_employment"
                    name="date_of_employment"
                    type="date"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    value="{{ old('date_of_employment', optional($user->date_of_employment)->format('Y-m-d') ?? '') }}"
                >
                @error('date_of_employment')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- TIN Number -->
            <div>
                <label for="tin_number" class="block text-xs font-semibold text-gray-600 mb-2">TIN Number</label>
                <input
                    id="tin_number"
                    name="tin_number"
                    type="text"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="123-456-789-000"
                    value="{{ old('tin_number', $user->tin_number ?? '') }}"
                >
                @error('tin_number')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- SSS/GSIS Number -->
            <div>
                <label for="sss_gsis_no" class="block text-xs font-semibold text-gray-600 mb-2">SSS/GSIS Number</label>
                <input
                    id="sss_gsis_no"
                    name="sss_gsis_no"
                    type="text"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="01-2345678-9"
                    value="{{ old('sss_gsis_no', $user->sss_gsis_no ?? '') }}"
                >
                @error('sss_gsis_no')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <p class="text-xs text-gray-500 mb-3">
                    By continuing, you agree to keep your information accurate.
                </p>
                <button
                    type="submit"
                    class="w-full group relative inline-flex items-center justify-center rounded-lg px-6 py-3 text-sm font-semibold text-white shadow-lg transition-transform duration-200 {{ $themeConfig['button'] }} hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $themeConfig['ring'] }}"
                >
                    Complete Profile
                </button>
            </div>
        </form>
            </div>
    </div>
</div>
@endsection
