@extends('UserSide.layouts.guest')

@section('content')
@include('UserSide.partials.theme-config')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md glass-panel rounded-2xl shadow-2xl p-8 fade-in-up">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 mb-4">
                <svg class="w-6 h-6 text-indigo-600" data-feather="key" stroke-width="2.5"></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">
                Generate New Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email and we'll generate a new password for you.
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

        @if (session('generated_password'))
            <div class="mt-6 space-y-4">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" data-feather="check-circle" stroke-width="2.5"></svg>
                        <div>
                            <p class="font-semibold">Your new password has been generated!</p>
                            <p class="text-sm mt-2">Use this password to sign in. We recommend changing it after your first login.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Your new password</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="generated-password" value="{{ session('generated_password') }}" readonly
                            class="flex-1 px-3 py-2.5 bg-white border border-slate-200 rounded-lg font-mono text-sm">
                        <button type="button" onclick="copyPassword()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 inline-block mr-1" data-feather="copy" stroke-width="2.5"></svg> Copy
                        </button>
                    </div>
                </div>
                <a href="{{ route('user.login') }}" class="block w-full text-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white {{ $themeConfig['button'] }}">
                    Go to Login
                </a>
            </div>
        @else
            <form class="mt-6 space-y-5" action="{{ route('user.password.generate') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 shadow-sm transition focus:outline-none {{ $themeConfig['ring'] }} {{ $themeConfig['border'] }}"
                        placeholder="you@email.com"
                        value="{{ old('email') }}">
                </div>

                <div class="space-y-3">
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 py-2.5 px-4 rounded-lg text-sm font-semibold text-white shadow-lg transition-transform duration-200 hover:-translate-y-0.5 {{ $themeConfig['button'] }}">
                        <svg class="w-4 h-4" data-feather="wand" stroke-width="2.5"></svg>
                        Generate Password
                    </button>
                    <div class="text-center space-y-1">
                        <a href="{{ route('user.login') }}" class="block text-sm font-medium {{ $themeConfig['accent'] }} hover:opacity-80">
                            Back to Login
                        </a>
                        <a href="{{ route('user.password.request') }}" class="block text-xs text-gray-500 hover:text-gray-700">
                            Prefer to choose your own password?
                        </a>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

@if (session('generated_password'))
<script>
function copyPassword(btn) {
    const input = document.getElementById('generated-password');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 inline-block mr-1" data-feather="check" stroke-width="2.5"></svg> Copied!';
        feather.replace();
        setTimeout(function() { btn.innerHTML = orig; feather.replace(); }, 2000);
    });
}
</script>
@endif
@endsection
