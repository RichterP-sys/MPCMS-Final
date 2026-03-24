<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Change - Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #111827;
            color: #fff;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out;
        }
        .animate-pulse-slow {
            animation: pulseSlow 2s infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite linear;
            color: #10b981;
        }
        .shape:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { top: 20%; right: 10%; animation-delay: 5s; }
        .shape:nth-child(3) { bottom: 20%; left: 20%; animation-delay: 10s; }
        .shape:nth-child(4) { bottom: 10%; right: 20%; animation-delay: 15s; }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(30px) rotate(240deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        /* Ensure body can scroll */
        html, body {
            overflow-x: hidden;
        }

        .input-focus {
            transition: all 0.3s ease;
            background-color: #1f2937;
            border: 1px solid #374151;
            color: #d1d5db;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            border-color: #10b981;
        }

        .btn-hover {
            transition: all 0.3s ease;
            background-color: #059669;
            color: white;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }
        .btn-hover:hover {
            background-color: #047857;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.5);
        }

        .heading-title {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
        }

        .heading-subtitle {
            color: #9ca3af;
            font-size: 14px;
        }

        .label-text {
            color: #e5e7eb;
            font-size: 14px;
            font-weight: 500;
        }

        .link-back {
            color: #10b981;
            text-decoration: none;
            font-size: 14px;
        }
        .link-back:hover {
            color: #059669;
        }

        .info-icon {
            color: #34d399;
        }
    </style>
</head>
<body class="bg-gray-900" style="background-color: #111827; overflow-y: auto;">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes" style="pointer-events: none;">
        <div class="shape"><i class="fas fa-key text-4xl"></i></div>
        <div class="shape"><i class="fas fa-lock text-3xl"></i></div>
        <div class="shape"><i class="fas fa-shield-alt text-5xl"></i></div>
        <div class="shape"><i class="fas fa-user-lock text-2xl"></i></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10" style="min-height: auto; padding-top: 3rem; padding-bottom: 3rem;">
        <div class="max-w-md w-full space-y-8 animate-fade-in">
            <!-- Logo/Header Section -->
            <div class="text-center animate-bounce-in">
                <div class="animate-pulse-slow">
                    <i class="fas fa-key text-6xl info-icon"></i>
                </div>
                <h2 class="mt-6 text-center heading-title animate-slide-up">
                    Request Password Change
                </h2>
                <p class="mt-2 text-center heading-subtitle animate-slide-up" style="animation-delay: 0.2s;">
                    Send a request to the administrator
                </p>
            </div>

            <!-- Info Box -->
            <div class="rounded-md p-4 animate-slide-up" style="animation-delay: 0.3s; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle info-icon"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" style="color: #a7f3d0;">
                            Enter your email address and the administrator will assist you with changing your password. You will be contacted shortly.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="rounded-md p-4 border" style="background-color: #7f1d1d; border-color: #dc2626;">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle" style="color: #fca5a5;"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium" style="color: #fca5a5;">
                            Error
                        </h3>
                        <div class="mt-2 text-sm" style="color: #fecaca;">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
            <div class="rounded-md p-4 border animate-slide-up" style="animation-delay: 0.4s; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium" style="color: #a7f3d0;">
                            Request Sent Successfully!
                        </h3>
                        <p class="text-sm mt-2" style="color: #a7f3d0;">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('user.login') }}" class="btn-hover" style="width: 100%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <i class="fas fa-sign-in-alt mr-2"></i>
                <span>Go to Login</span>
            </a>
            @else
            <!-- Password Change Request Form -->
            <form class="mt-8 space-y-6 animate-slide-up" action="{{ route('user.password.generate') }}" method="POST" id="requestForm" style="animation-delay: 0.4s;">
                @csrf
                
                <div>
                    <label for="email" class="label-text block mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope" style="color: #9ca3af;"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="input-focus block w-full px-3 py-3 pl-10 rounded-md"
                            placeholder="your.email@example.com"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;"
                            value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="reason" class="label-text block mb-2">Reason (Optional)</label>
                    <div class="relative">
                        <div class="absolute top-3 left-0 pl-3 pointer-events-none">
                            <i class="fas fa-comment-dots" style="color: #9ca3af;"></i>
                        </div>
                        <textarea id="reason" name="reason" rows="3"
                            class="input-focus block w-full px-3 py-3 pl-10 rounded-md resize-none"
                            placeholder="Why do you need to change your password?"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">{{ old('reason') }}</textarea>
                    </div>
                    <p class="text-xs mt-1" style="color: #6b7280;">This helps the administrator understand your request better.</p>
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="btn-hover" style="width: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-paper-plane mr-2" id="submitIcon"></i>
                        <span id="submitText">Send Request to Admin</span>
                    </button>
                </div>
            </form>

            <div class="text-center animate-fade-in" style="animation-delay: 0.5s;">
                <a href="{{ route('user.login') }}" class="link-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Login
                </a>
            </div>
            @endif

            <!-- Info Section -->
            <div class="rounded-md border p-4 animate-slide-up" style="animation-delay: 0.6s; background-color: rgba(31, 41, 55, 0.5); border-color: #374151;">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb" style="color: #fbbf24; margin-top: 4px;"></i>
                    <div class="text-sm" style="color: #d1d5db;">
                        <strong style="color: #e5e7eb; display: block; margin-bottom: 4px;">What happens next?</strong>
                        <p>The administrator will receive your request and contact you via email with instructions to reset your password.</p>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="text-center text-sm animate-fade-in" style="animation-delay: 0.7s; color: #6b7280; margin-top: 32px;">
                <div class="flex items-center justify-center">
                    <i class="fas fa-shield-alt" style="color: #9ca3af; margin-right: 8px;"></i>
                    <span>Secure Member Access Only</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('requestForm');
            if (!form) return;

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitIcon = document.getElementById('submitIcon');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.75';
                submitBtn.style.cursor = 'not-allowed';
                submitText.textContent = 'Sending Request...';
                submitIcon.className = 'fas fa-spinner fa-spin mr-2';
            });
        });
    </script>
</body>
</html>
