<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-section {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .logo-circle {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo-circle i {
            font-size: 70px;
            color: #1e3a8a;
        }

        .system-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .system-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .right-panel {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sign-in-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 30px;
            text-align: center;
        }

        .user-type-dropdown {
            position: relative;
            margin-bottom: 25px;
        }

        .dropdown-button {
            width: 100%;
            padding: 14px 16px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            color: #374151;
        }

        .dropdown-button:hover {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .dropdown-button.active {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            margin-top: 5px;
            display: none;
            z-index: 10;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.selected {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            color: #1f2937;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #2563eb;
        }

        .remember-me label {
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 14px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #1e40af;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-button:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .login-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .info-box {
            margin-top: 25px;
            padding: 16px;
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            font-size: 14px;
            color: #1e40af;
        }

        .info-box i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .powered-by {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
            }

            .left-panel {
                padding: 40px 30px;
            }

            .right-panel {
                padding: 40px 30px;
            }

            .logo-circle {
                width: 120px;
                height: 120px;
            }

            .logo-circle i {
                font-size: 50px;
            }

            .system-title {
                font-size: 22px;
            }

            .sign-in-title {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="logo-section">
                <div class="logo-circle">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="system-title">Cooperative Management</div>
                <div class="system-subtitle">Secure Access Portal</div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <h2 class="sign-in-title">Sign In</h2>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Login Failed</strong>
                    <ul style="margin-top: 5px; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            <form id="loginForm" method="POST">
                @csrf

                <!-- User Type Dropdown -->
                <div class="user-type-dropdown">
                    <div class="dropdown-button" id="dropdownButton">
                        <span id="selectedType">
                            <i class="fas fa-user"></i> Member
                        </span>
                        <i class="fas fa-chevron-down" id="dropdownIcon"></i>
                    </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-item" data-type="member" data-icon="fa-user">
                            <i class="fas fa-user"></i>
                            <span>Member</span>
                        </div>
                        <div class="dropdown-item" data-type="admin" data-icon="fa-user-shield">
                            <i class="fas fa-user-shield"></i>
                            <span>Administrator</span>
                        </div>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="Enter your email" required value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-input" 
                               placeholder="Enter your password" required>
                        <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-footer">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                    <a href="#" class="forgot-link" id="forgotLink">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-button" id="loginButton">
                    <span id="buttonText">Login</span>
                    <i class="fas fa-arrow-right" id="buttonIcon"></i>
                </button>
            </form>

            <!-- Info Box -->
            <div class="info-box" id="infoBox">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Member Access:</strong> Sign in to view your dashboard, loans, and financial records.
                </div>
            </div>

            <!-- Powered By -->
            <div class="powered-by">
                Powered by Cooperative Management System
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('dropdownButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownIcon = document.getElementById('dropdownIcon');
            const selectedType = document.getElementById('selectedType');
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            const loginForm = document.getElementById('loginForm');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const buttonIcon = document.getElementById('buttonIcon');
            const infoBox = document.getElementById('infoBox');
            const forgotLink = document.getElementById('forgotLink');

            let currentType = 'member';
            let isPasswordVisible = false;

            // Toggle dropdown
            dropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
                dropdownButton.classList.toggle('active');
                dropdownIcon.style.transform = dropdownMenu.classList.contains('show') 
                    ? 'rotate(180deg)' : 'rotate(0deg)';
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.remove('show');
                dropdownButton.classList.remove('active');
                dropdownIcon.style.transform = 'rotate(0deg)';
            });

            // Handle dropdown item selection
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Remove selected class from all items
                    dropdownItems.forEach(i => i.classList.remove('selected'));
                    
                    // Add selected class to clicked item
                    this.classList.add('selected');
                    
                    // Update selected type
                    currentType = this.dataset.type;
                    const icon = this.dataset.icon;
                    const text = this.textContent.trim();
                    
                    selectedType.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
                    
                    // Update form action
                    if (currentType === 'admin') {
                        loginForm.action = '{{ route("admin.login") }}';
                        forgotLink.href = '#';
                        forgotLink.style.display = 'inline';
                        infoBox.innerHTML = `
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Administrator Access:</strong> Manage members, loans, and system settings.
                            </div>
                        `;
                    } else {
                        loginForm.action = '{{ route("user.login") }}';
                        forgotLink.href = '{{ route("user.password.generate") }}';
                        forgotLink.style.display = 'inline';
                        infoBox.innerHTML = `
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Member Access:</strong> Sign in to view your dashboard, loans, and financial records.
                            </div>
                        `;
                    }
                    
                    // Close dropdown
                    dropdownMenu.classList.remove('show');
                    dropdownButton.classList.remove('active');
                    dropdownIcon.style.transform = 'rotate(0deg)';
                });
            });

            // Set initial form action
            loginForm.action = '{{ route("user.login") }}';

            // Mark default selection
            dropdownItems[0].classList.add('selected');

            // Password toggle
            passwordToggle.addEventListener('click', function() {
                isPasswordVisible = !isPasswordVisible;
                passwordInput.type = isPasswordVisible ? 'text' : 'password';
                this.className = isPasswordVisible ? 'fas fa-eye-slash password-toggle' : 'fas fa-eye password-toggle';
            });

            // Form submission
            loginForm.addEventListener('submit', function() {
                loginButton.disabled = true;
                buttonText.textContent = 'Signing in...';
                buttonIcon.className = 'fas fa-spinner fa-spin';
            });

            // Email validation
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.style.borderColor = '#ef4444';
                } else if (this.value) {
                    this.style.borderColor = '#10b981';
                } else {
                    this.style.borderColor = '#e5e7eb';
                }
            });
        });
    </script>
</body>
</html>
