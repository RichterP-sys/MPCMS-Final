@extends('UserSide.layouts.guest')

@section('content')
@include('UserSide.partials.theme-config')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md glass-panel rounded-2xl shadow-2xl p-8 fade-in-up">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Reset Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email and choose a new password.
            </p>
        </div>

        @if ($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="mt-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-6 space-y-5" action="{{ route('user.password.update') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-600">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="you@email.com"
                    value="{{ old('email') }}">
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-600">New Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="New Password">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-gray-600">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                    placeholder="Confirm Password">
            </div>

            <div class="space-y-3">
                <button type="submit" class="w-full inline-flex justify-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white shadow-lg transition-transform duration-200 hover:-translate-y-0.5 {{ $themeConfig['button'] }}">
                    Reset Password
                </button>
                <div class="text-center">
                    <a href="{{ route('user.login') }}" class="text-sm font-medium {{ $themeConfig['accent'] }} hover:opacity-80">
                        Back to Login
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
