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
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 items-stretch">
        <div class="hidden lg:flex flex-col justify-between rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-8 text-white shadow-2xl float-slow">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                    {{ $brandName }} Account
                </span>
                <h1 class="mt-6 text-3xl font-bold leading-tight">
                    Welcome back, {{ $displayName }}
                </h1>
                <p class="mt-3 text-sm text-white/80">
                    Complete your profile to unlock personalized features and faster approvals.
                </p>
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
            </div>
        </div>

        <div class="w-full space-y-6 rounded-2xl bg-white shadow-2xl p-6 sm:p-8 lg:p-10 fade-in-up">
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
            
            <div class="text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Complete your profile
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Hi {{ $displayName }}, confirm your details to finish setup.
                </p>
            </div>

        <form class="mt-6 space-y-6" action="{{ route('user.profile.store') }}" method="POST">

            @csrf
            <div class="flex flex-col items-center mb-4">
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
            </div

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-semibold text-gray-600">First Name</label>
                    <input
                        id="first_name"
                        name="first_name"
                        type="text"
                        autocomplete="given-name"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="First Name"
                        value="{{ old('first_name', $user->first_name ?? '') }}"
                    >
                    @error('first_name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-semibold text-gray-600">Last Name</label>
                    <input
                        id="last_name"
                        name="last_name"
                        type="text"
                        autocomplete="family-name"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="Last Name"
                        value="{{ old('last_name', $user->last_name ?? '') }}"
                    >
                    @error('last_name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone_number" class="block text-xs font-semibold text-gray-600">Phone</label>
                    <input
                        id="phone_number"
                        name="phone_number"
                        type="tel"
                        autocomplete="tel"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="Phone Number"
                        value="{{ old('phone_number', $user->phone ?? '') }}"
                    >
                    @error('phone_number')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-gray-600">Address</label>
                    <input
                        id="address"
                        name="address"
                        type="text"
                        autocomplete="street-address"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="Address"
                        value="{{ old('address', $user->address ?? '') }}"
                    >
                    @error('address')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nature of Work -->
                <div>
                    <label for="nature_of_work" class="block text-xs font-semibold text-gray-600">Nature of Work</label>
                    <input
                        id="nature_of_work"
                        name="nature_of_work"
                        type="text"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="Self-employed, Farmer, etc."
                        value="{{ old('nature_of_work', $user->nature_of_work ?? '') }}"
                    >
                    @error('nature_of_work')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Employer/Business Name -->
                <div>
                    <label for="employer_business_name" class="block text-xs font-semibold text-gray-600">Employer or Business</label>
                    <input
                        id="employer_business_name"
                        name="employer_business_name"
                        type="text"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="Employer or Business Name"
                        value="{{ old('employer_business_name', $user->employer_business_name ?? '') }}"
                    >
                    @error('employer_business_name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Date of Employment -->
                <div>
                    <label for="date_of_employment" class="block text-xs font-semibold text-gray-600">Date of Employment</label>
                    <input
                        id="date_of_employment"
                        name="date_of_employment"
                        type="date"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        value="{{ old('date_of_employment', optional($user->date_of_employment)->format('Y-m-d') ?? '') }}"
                    >
                    @error('date_of_employment')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TIN Number -->
                <div>
                    <label for="tin_number" class="block text-xs font-semibold text-gray-600">TIN Number</label>
                    <input
                        id="tin_number"
                        name="tin_number"
                        type="text"
                        required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="123-456-789-000"
                        value="{{ old('tin_number', $user->tin_number ?? '') }}"
                    >
                    @error('tin_number')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <!-- SSS/GSIS Number -->
                <label for="sss_gsis_no" class="block text-xs font-semibold text-gray-600">SSS/GSIS Number</label>
                <input
                    id="sss_gsis_no"
                    name="sss_gsis_no"
                    type="text"
                    required
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="01-2345678-9"
                    value="{{ old('sss_gsis_no', $user->sss_gsis_no ?? '') }}"
                >
                @error('sss_gsis_no')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <p class="text-xs text-gray-500">
                    By continuing, you agree to keep your information accurate.
                </p>
                <button
                    type="submit"
                    class="group relative inline-flex items-center justify-center rounded-lg px-6 py-2 text-sm font-semibold text-white shadow-lg transition-transform duration-200 {{ $themeConfig['button'] }} hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $themeConfig['ring'] }}"
                >
                    Complete Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
