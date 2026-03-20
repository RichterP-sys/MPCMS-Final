<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
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

        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.5);
        }

        .error-shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-gray-900 relative overflow-hidden">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape text-blue-500"><svg class="w-10 h-10" data-feather="shield" stroke-width="2.5"></svg></div>
        <div class="shape text-green-500"><svg class="w-8 h-8" data-feather="lock" stroke-width="2.5"></svg></div>
        <div class="shape text-purple-500"><svg class="w-12 h-12" data-feather="shield" stroke-width="2.5"></svg></div>
        <div class="shape text-indigo-500"><svg class="w-6 h-6" data-feather="key" stroke-width="2.5"></svg></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-8 animate-fade-in">
            <!-- Logo/Header Section -->
            <div class="text-center animate-bounce-in">
                <div class="animate-pulse-slow">
                    <svg class="w-24 h-24 mx-auto text-blue-500" data-feather="shield" stroke-width="2.5"></svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white animate-slide-up">
                    Administrator Login
                </h2>
                <p class="mt-2 text-center text-sm text-gray-400 animate-slide-up" style="animation-delay: 0.2s;">
                    Cooperative Management System
                </p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 border border-red-400 error-shake">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" data-feather="alert-circle" stroke-width="2.5"></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Authentication failed
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
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
            <div class="rounded-md bg-green-50 p-4 border border-green-400 animate-slide-up">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" data-feather="check-circle" stroke-width="2.5"></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Login Form -->
            <form class="mt-8 space-y-6 animate-slide-up" action="{{ route('admin.login') }}" method="POST" id="loginForm">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400" id="emailIcon"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                class="input-focus appearance-none rounded-t-md relative block w-full px-3 py-3 pl-10
                                border border-gray-700 bg-gray-800 text-gray-300 placeholder-gray-500
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Admin Email"
                                value="{{ old('email') }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <span id="emailValidation" class="text-red-500 text-sm hidden">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <span id="emailSuccess" class="text-green-500 text-sm hidden">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400" id="passwordIcon"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="input-focus appearance-none rounded-b-md relative block w-full px-3 py-3 pl-10 pr-10
                                border border-gray-700 bg-gray-800 text-gray-300 placeholder-gray-500
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 rounded bg-gray-800">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-400 cursor-pointer">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="text-blue-400 hover:text-blue-300 transition duration-150 ease-in-out">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" id="loginBtn"
                        class="btn-hover group relative w-full flex justify-center py-3 px-4 border border-transparent
                        text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                        transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400 transition duration-150 ease-in-out" id="loginIcon"></i>
                        </span>
                        <span id="loginText">Sign in to Dashboard</span>
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="text-center text-sm text-gray-500 mt-8 animate-fade-in" style="animation-delay: 0.5s;">
                <div class="flex items-center justify-center">
                    <i class="fas fa-lock text-gray-400 mr-2"></i>
                    <span>Secure Admin Access Only</span>
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
            togglePassword.addEventListener('click', function() {
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
                        emailIcon.className = 'fas fa-envelope text-green-400';
                        emailInput.classList.remove('border-red-500');
                        emailInput.classList.add('border-green-500');
                    } else {
                        emailValidation.classList.remove('hidden');
                        emailSuccess.classList.add('hidden');
                        emailIcon.className = 'fas fa-envelope text-red-400';
                        emailInput.classList.remove('border-green-500');
                        emailInput.classList.add('border-red-500');
                    }
                } else {
                    emailValidation.classList.add('hidden');
                    emailSuccess.classList.add('hidden');
                    emailIcon.className = 'fas fa-envelope text-gray-400';
                    emailInput.classList.remove('border-red-500', 'border-green-500');
                }
            });

            // Enhanced form submission with loading animation
            form.addEventListener('submit', function(e) {
                const email = emailInput.value;
                const password = passwordInput.value;

                // Basic validation
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return;
                }

                loginBtn.disabled = true;
                loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
                loginText.textContent = 'Signing in...';
                loginIcon.className = 'fas fa-spinner fa-spin text-blue-500 group-hover:text-blue-400 transition duration-150 ease-in-out';

                // Add loading animation to button
                loginBtn.innerHTML = `
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    </span>
                    <span id="loginText">Signing in...</span>
                `;
            });

            // Add focus effects
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                });
            });

            // Add hover effects for remember me label
            document.querySelector('label[for="remember_me"]').addEventListener('mouseenter', function() {
                this.classList.add('text-gray-300');
            });

            document.querySelector('label[for="remember_me"]').addEventListener('mouseleave', function() {
                this.classList.remove('text-gray-300');
            });
        });
    </script>
</body>
</html>
