<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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

        .error-shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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

        .link-forgot {
            color: #10b981;
            text-decoration: none;
            font-size: 14px;
        }
        .link-forgot:hover {
            color: #059669;
        }

        .checkbox-custom {
            width: 16px;
            height: 16px;
            accent-color: #10b981;
        }

        .info-icon {
            color: #34d399;
        }
    </style>
</head>
<body class="bg-gray-900" style="background-color: #111827; overflow-y: auto;">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes" style="pointer-events: none;">
        <div class="shape"><i class="fas fa-handshake text-4xl"></i></div>
        <div class="shape"><i class="fas fa-lock text-3xl"></i></div>
        <div class="shape"><i class="fas fa-credit-card text-5xl"></i></div>
        <div class="shape"><i class="fas fa-user-circle text-2xl"></i></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10" style="min-height: auto; padding-top: 3rem; padding-bottom: 3rem;">
        <div class="max-w-md w-full space-y-8 animate-fade-in">
            <!-- Logo/Header Section -->
            <div class="text-center animate-bounce-in">
                <div class="animate-pulse-slow">
                    <i class="fas fa-user-circle text-6xl info-icon"></i>
                </div>
                <h2 class="mt-6 text-center heading-title animate-slide-up">
                    Member Login
                </h2>
                <p class="mt-2 text-center heading-subtitle animate-slide-up" style="animation-delay: 0.2s;">
                    Cooperative Management System
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
                            Welcome! Sign in with your member account to access your dashboard, loans, and financial records.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="rounded-md p-4 border error-shake" style="background-color: #7f1d1d; border-color: #dc2626;">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle" style="color: #fca5a5;"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium" style="color: #fca5a5;">
                            Login Failed
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

            <!-- Success Messages -->
            @if (session('success'))
            <div class="rounded-md p-4 border animate-slide-up" style="background-color: #f0fdf4; border-color: #22c55e;">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" style="color: #166534;">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Login Form -->
            <form class="mt-8 space-y-6 animate-slide-up" action="{{ route('user.login') }}" method="POST" id="loginForm" style="animation-delay: 0.4s;">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope" id="emailIcon" style="color: #9ca3af;"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="input-focus appearance-none rounded-t-md relative block w-full px-3 py-3 pl-10 sm:text-sm"
                            placeholder="Member Email"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;"
                            value="{{ old('email') }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span id="emailValidation" class="text-red-500 text-sm hidden">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span id="emailSuccess" class="text-blue-500 text-sm hidden">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock" id="passwordIcon" style="color: #9ca3af;"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="input-focus appearance-none rounded-b-md relative block w-full px-3 py-3 pl-10 pr-10 sm:text-sm"
                            placeholder="Password"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="focus:outline-none" style="color: #9ca3af;">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="checkbox-custom">
                        <label for="remember_me" class="ml-2 block text-sm cursor-pointer label-text">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('user.password.generate') }}" class="link-forgot">
                            Request Password Change
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" id="loginBtn" class="btn-hover" style="width: 100%; display: flex; align-items: center; justify-content: center;">
                        <span class="absolute left-0" style="padding-left: 12px;">
                            <i class="fas fa-sign-in-alt" id="loginIcon" style="color: #059669;"></i>
                        </span>
                        <span id="loginText">Sign in to Dashboard</span>
                    </button>
                </div>
            </form>

            <!-- Info Section -->
            <div class="rounded-md border p-4 animate-slide-up" style="animation-delay: 0.5s; background-color: rgba(31, 41, 55, 0.5); border-color: #374151;">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb" style="color: #fbbf24; margin-top: 4px;"></i>
                    <div class="text-sm" style="color: #d1d5db;">
                        <strong style="color: #e5e7eb; display: block; margin-bottom: 4px;">Need help or dont have an account?</strong>
                        <p>Contact your cooperative administrator to create a new member account.</p>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="text-center text-sm animate-fade-in" style="animation-delay: 0.6s; color: #6b7280; margin-top: 32px;">
                <div class="flex items-center justify-center">
                    <i class="fas fa-shield-alt" style="color: #9ca3af; margin-right: 8px;"></i>
                    <span>Secure Member Access Only</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginIcon = document.getElementById('loginIcon');
            const emailValidation = document.getElementById('emailValidation');
            const emailSuccess = document.getElementById('emailSuccess');
            const emailIcon = document.getElementById('emailIcon');

            let isPasswordVisible = false;

            // Password toggle functionality
            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();
                isPasswordVisible = !isPasswordVisible;
                passwordInput.type = isPasswordVisible ? 'text' : 'password';
                passwordToggleIcon.className = isPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye';
            });

            // Real-time email validation
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email.length > 0) {
                    if (emailRegex.test(email)) {
                        emailValidation.classList.add('hidden');
                        emailSuccess.classList.remove('hidden');
                        emailIcon.style.color = '#10b981';
                        emailInput.style.borderColor = '#10b981';
                    } else {
                        emailValidation.classList.remove('hidden');
                        emailSuccess.classList.add('hidden');
                        emailIcon.style.color = '#ef4444';
                        emailInput.style.borderColor = '#ef4444';
                    }
                } else {
                    emailValidation.classList.add('hidden');
                    emailSuccess.classList.add('hidden');
                    emailIcon.style.color = '#9ca3af';
                    emailInput.style.borderColor = '#374151';
                }
            });

            // Enhanced form submission with loading animation
            form.addEventListener('submit', function(e) {
                const email = emailInput.value;
                const password = passwordInput.value;

                // Basic validation
                if (!email || !password) {
                    e.preventDefault();
                    emailInput.style.borderColor = '#ef4444';
                    passwordInput.style.borderColor = '#ef4444';
                    return;
                }

                loginBtn.disabled = true;
                loginBtn.style.opacity = '0.75';
                loginBtn.style.cursor = 'not-allowed';
                loginBtn.style.backgroundColor = '#047857';
                loginText.textContent = 'Signing in...';
                loginIcon.className = 'fas fa-spinner fa-spin';
                loginIcon.style.color = '#059669';
            });

            // Add focus effects
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#10b981';
                    this.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
                });

                input.addEventListener('blur', function() {
                    // Reset to default or error state
                    if (input === emailInput) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (emailInput.value && !emailRegex.test(emailInput.value)) {
                            this.style.borderColor = '#ef4444';
                        } else {
                            this.style.borderColor = '#374151';
                        }
                    } else {
                        this.style.borderColor = '#374151';
                    }
                    this.style.boxShadow = '';
                });
            });

            // Add hover effects for remember me label
            const rememberLabel = document.querySelector('label[for="remember_me"]');
            if (rememberLabel) {
                rememberLabel.addEventListener('mouseenter', function() {
                    this.style.color = '#e5e7eb';
                });

                rememberLabel.addEventListener('mouseleave', function() {
                    this.style.color = '#d1d5db';
                });
            }
        });
    </script>
</body>
</html>
