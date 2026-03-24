@extends('UserSide.layouts.guest')

@section('content')
@include('UserSide.partials.theme-config')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 glass-panel rounded-2xl shadow-2xl p-8 text-center fade-in-up">
        @if (session('success'))
        <div>
            <div class="mx-auto h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Profile Completed!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ session('success') }}
            </p>
        </div>
        @else
        <div>
            <svg class="mx-auto h-12 w-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Account Pending Confirmation
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Hi {{ auth('member')->user()?->first_name ?? 'there' }}, thanks for signing up with {{ config('app.name', 'MPCMS') }}.
            </p>
        </div>
        @endif

        <div class="mt-8 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg text-left">
            <p class="text-sm text-amber-700">
                <span class="font-semibold">Your account is pending admin confirmation.</span> Please wait for approval from the administrator. You will be notified once your account has been activated.
            </p>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-semibold rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-transform duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <p class="mt-6 text-xs text-gray-500">
            You can close this page and come back later. Your account status will be updated automatically once approved.
        </p>
    </div>
</div>
@endsection
