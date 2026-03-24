<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Cooperative Management System</title>
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

        .info-icon {
            color: #34d399;
        }

        .form-container {
            max-height: 60vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 10px;
        }

        .form-container::-webkit-scrollbar {
            width: 8px;
        }

        .form-container::-webkit-scrollbar-track {
            background: #1f2937;
            border-radius: 4px;
        }

        .form-container::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }

        .form-container::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }

        /* Ensure body can scroll */
        html, body {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="bg-gray-900" style="background-color: #111827; overflow-y: auto;">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes" style="pointer-events: none;">
        <div class="shape"><i class="fas fa-user-edit text-4xl"></i></div>
        <div class="shape"><i class="fas fa-id-card text-3xl"></i></div>
        <div class="shape"><i class="fas fa-briefcase text-5xl"></i></div>
        <div class="shape"><i class="fas fa-file-alt text-2xl"></i></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10" style="min-height: auto; padding-top: 3rem; padding-bottom: 3rem;">
        <div class="max-w-2xl w-full space-y-8 animate-fade-in">
            <!-- Logo/Header Section -->
            <div class="text-center animate-bounce-in">
                <div class="animate-pulse-slow">
                    <i class="fas fa-user-circle text-6xl info-icon"></i>
                </div>
                <h2 class="mt-6 text-center heading-title animate-slide-up">
                    Complete Your Profile
                </h2>
                <p class="mt-2 text-center heading-subtitle animate-slide-up" style="animation-delay: 0.2s;">
                    @php
                        $user = auth('member')->user() ?? auth()->user();
                        $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                        $displayName = $displayName !== '' ? $displayName : ($user->name ?? 'there');
                    @endphp
                    Hi {{ $displayName }}, confirm your details to finish setup.
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
                            Please fill in all required information to complete your profile. This information will be reviewed by the administrator.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success/Warning Messages -->
            @if (session('success'))
            <div class="rounded-md p-4 border animate-slide-up" style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
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

            @if (session('warning'))
            <div class="rounded-md p-4 border animate-slide-up" style="background-color: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle" style="color: #fbbf24;"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" style="color: #fde68a;">
                            {{ session('warning') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Profile Form -->
            <div class="form-container animate-slide-up" style="animation-delay: 0.4s;">
                <form action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Profile Photo -->
                    <div>
                        <label class="label-text block mb-2">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*"
                            class="input-focus block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db; padding: 8px; border-radius: 6px;">
                        @error('profile_photo')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="label-text block mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="First Name"
                            value="{{ old('first_name', $user->first_name ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('first_name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="label-text block mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="Last Name"
                            value="{{ old('last_name', $user->last_name ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('last_name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone_number" class="label-text block mb-2">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="Phone Number"
                            value="{{ old('phone_number', $user->phone ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('phone_number')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="label-text block mb-2">Address</label>
                        <input type="text" id="address" name="address" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="Address"
                            value="{{ old('address', $user->address ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('address')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nature of Work -->
                    <div>
                        <label for="nature_of_work" class="label-text block mb-2">Nature of Work</label>
                        <input type="text" id="nature_of_work" name="nature_of_work" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="Self-employed, Farmer, etc."
                            value="{{ old('nature_of_work', $user->nature_of_work ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('nature_of_work')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Employer/Business Name -->
                    <div>
                        <label for="employer_business_name" class="label-text block mb-2">Employer or Business Name</label>
                        <input type="text" id="employer_business_name" name="employer_business_name" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="Employer or Business Name"
                            value="{{ old('employer_business_name', $user->employer_business_name ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('employer_business_name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Employment -->
                    <div>
                        <label for="date_of_employment" class="label-text block mb-2">Date of Employment</label>
                        <input type="date" id="date_of_employment" name="date_of_employment" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            value="{{ old('date_of_employment', optional($user->date_of_employment)->format('Y-m-d') ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('date_of_employment')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- TIN Number -->
                    <div>
                        <label for="tin_number" class="label-text block mb-2">TIN Number</label>
                        <input type="text" id="tin_number" name="tin_number" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="123-456-789-000"
                            value="{{ old('tin_number', $user->tin_number ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('tin_number')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- SSS/GSIS Number -->
                    <div>
                        <label for="sss_gsis_no" class="label-text block mb-2">SSS/GSIS Number</label>
                        <input type="text" id="sss_gsis_no" name="sss_gsis_no" required
                            class="input-focus block w-full px-4 py-3 rounded-md"
                            placeholder="01-2345678-9"
                            value="{{ old('sss_gsis_no', $user->sss_gsis_no ?? '') }}"
                            style="background-color: #1f2937; border: 1px solid #374151; color: #d1d5db;">
                        @error('sss_gsis_no')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <p class="text-xs mb-3" style="color: #9ca3af;">
                            By continuing, you agree to keep your information accurate.
                        </p>
                        <button type="submit" class="btn-hover" style="width: 100%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Complete Profile</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back Button -->
            <div class="text-center animate-fade-in" style="animation-delay: 0.5s;">
                <a href="{{ route('user.profile.pending') }}" class="text-sm" style="color: #10b981; text-decoration: none;">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Pending Confirmation
                </a>
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
</body>
</html>
