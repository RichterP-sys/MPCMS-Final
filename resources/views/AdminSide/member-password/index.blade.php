@extends('AdminSide.layouts.admin')

@section('title', 'Member Password')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl shadow-lg p-8 mb-2" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 50%, #2563eb 100%);">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white drop-shadow">Member Password</h1>
                <p class="text-white/90 mt-1 font-medium">Generate a new password for a member by email or username</p>
            </div>
            <a href="{{ route('admin.member-registration.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl backdrop-blur border border-white/30 shadow transition">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Registration</span>
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-white/80 backdrop-blur shadow-lg border border-indigo-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-indigo-100 bg-indigo-50/60">
                <h3 class="font-semibold text-indigo-900">Set Member Password</h3>
                <p class="text-sm text-indigo-700">Enter member's email or username, then generate or enter a custom password</p>
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

                @if (session('generated_password'))
                    <div class="mb-6 space-y-4">
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-emerald-600 text-xl mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-emerald-800">Password {{ session('password_mode') === 'custom' ? 'updated' : 'generated' }} successfully</p>
                                    <p class="text-sm text-emerald-700 mt-1">Member: {{ session('member_name', '—') }}</p>
                                    <p class="text-sm text-emerald-700">Email: {{ session('member_email', '—') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <label class="block text-xs font-semibold text-slate-600 mb-2">{{ session('password_mode') === 'custom' ? 'Password Set' : 'Generated Password' }}</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="generated-password" value="{{ session('generated_password') }}" readonly
                                    class="flex-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-mono text-sm">
                                <button type="button" onclick="copyPassword(this)" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Give this password to the member. They can use it to sign in.</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.member-password.generate') }}" method="POST" class="space-y-5" id="password-form">
                    @csrf
                    <div>
                        <label for="email_or_username" class="block text-sm font-medium text-slate-700 mb-2">Email or Username <span class="text-red-500">*</span></label>
                        <input type="text" name="email_or_username" id="email_or_username" value="{{ old('email_or_username') }}" required
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email_or_username') border-red-500 @enderror"
                            placeholder="member@email.com or username">
                        <p class="text-xs text-slate-500 mt-1.5">Enter the member's email address or login username</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password Option</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="password_mode" value="generate" {{ old('password_mode', 'generate') === 'generate' ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm">Generate random password</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="password_mode" value="custom" {{ old('password_mode') === 'custom' ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm">Enter custom password</span>
                            </label>
                        </div>
                    </div>

                    <div id="custom-password-fields" class="space-y-4 hidden">
                        <div>
                            <label for="custom_password" class="block text-sm font-medium text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="custom_password" id="custom_password"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('custom_password') border-red-500 @enderror"
                                placeholder="Enter password (min 8 characters)">
                        </div>
                        <div>
                            <label for="custom_password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="custom_password_confirmation" id="custom_password_confirmation"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="Re-enter password">
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-key"></i>
                        <span id="submit-text">Generate Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyPassword(btn) {
    const input = document.getElementById('generated-password');
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(function() { btn.innerHTML = orig; }, 2000);
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const modeGenerate = document.querySelector('input[name="password_mode"][value="generate"]');
    const modeCustom = document.querySelector('input[name="password_mode"][value="custom"]');
    const customFields = document.getElementById('custom-password-fields');
    const customPassword = document.getElementById('custom_password');
    const customConfirm = document.getElementById('custom_password_confirmation');
    const submitText = document.getElementById('submit-text');
    const form = document.getElementById('password-form');
    function toggleCustom() {
        const isCustom = modeCustom && modeCustom.checked;
        if (customFields) customFields.classList.toggle('hidden', !isCustom);
        if (submitText) submitText.textContent = isCustom ? 'Set Password' : 'Generate Password';
        if (customPassword) customPassword.required = isCustom;
        if (customConfirm) customConfirm.required = isCustom;
    }
    if (modeGenerate) modeGenerate.addEventListener('change', toggleCustom);
    if (modeCustom) modeCustom.addEventListener('change', toggleCustom);
    toggleCustom();
});
</script>
@endsection
