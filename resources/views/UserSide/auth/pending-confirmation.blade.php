<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending - Cooperative Management System</title>
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

        .btn-secondary {
            transition: all 0.3s ease;
            background-color: #1f2937;
            color: #d1d5db;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 6px;
            border: 1px solid #374151;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background-color: #374151;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(55, 65, 81, 0.5);
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

        .info-icon {
            color: #fbbf24;
        }
    </style>
</head>
<body class="bg-gray-900" style="background-color: #111827; overflow-y: auto;">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes" style="pointer-events: none;">
        <div class="shape"><i class="fas fa-handshake text-4xl"></i></div>
        <div class="shape"><i class="fas fa-clock text-3xl"></i></div>
        <div class="shape"><i class="fas fa-user-check text-5xl"></i></div>
        <div class="shape"><i class="fas fa-shield-alt text-2xl"></i></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10" style="min-height: auto; padding-top: 3rem; padding-bottom: 3rem;">
        <div class="max-w-md w-full space-y-8 animate-fade-in">
            <!-- Logo/Header Section -->
            <div class="text-center animate-bounce-in">
                <div class="animate-pulse-slow">
                    <i class="fas fa-exclamation-circle text-6xl info-icon"></i>
                </div>
                <h2 class="mt-6 text-center heading-title animate-slide-up">
                    Account Pending Confirmation
                </h2>
                <p class="mt-2 text-center heading-subtitle animate-slide-up" style="animation-delay: 0.2s;">
                    Hi {{ auth('member')->user()?->first_name ?? 'Troy' }}, thanks for signing up with MPCMS.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
            <div class="rounded-md p-4 border animate-slide-up" style="animation-delay: 0.3s; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" style="color: #a7f3d0;">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Warning Box -->
            <div class="rounded-md p-4 animate-slide-up" style="animation-delay: 0.4s; background-color: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle info-icon"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" style="color: #fde68a;">
                            Your account is pending admin confirmation.
                        </p>
                        <p class="text-sm mt-2" style="color: #fde68a;">
                            Please wait for approval from the administrator. You will be notified once your account has been activated.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4 animate-slide-up" style="animation-delay: 0.5s;">
                <a href="{{ route('user.profile.complete') }}" class="btn-hover" style="width: 100%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    <i class="fas fa-eye mr-2"></i>
                    <span>View Profile</span>
                </a>

                <form method="POST" action="{{ route('user.logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary" style="width: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

            <!-- Info Section -->
            <div class="rounded-md border p-4 animate-slide-up" style="animation-delay: 0.6s; background-color: rgba(31, 41, 55, 0.5); border-color: #374151;">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb" style="color: #fbbf24; margin-top: 4px;"></i>
                    <div class="text-sm" style="color: #d1d5db;">
                        <p>You can close this page and come back later. Your account status will be updated automatically once approved.</p>
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
</body>
</html>
