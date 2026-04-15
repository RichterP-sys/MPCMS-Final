<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar - Modern and sleek */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(180deg, #94a3b8 0%, #64748b 100%); 
            border-radius: 4px; 
            border: 2px solid #f1f5f9;
        }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #64748b 0%, #475569 100%); }
        
        /* Professional Color Palette */
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1e40af;
            --primary-blue-light: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-purple: #8b5cf6;
            --success-green: #10b981;
            --warning-amber: #f59e0b;
            --danger-red: #ef4444;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
        }
        
        /* Base styles */
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--neutral-900);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Navigation item styles */
        .nav-item {
            position: relative;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #e2e8f0;
            font-weight: 500;
        }
        
        .nav-item:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
            color: #ffffff;
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .nav-item.active i {
            color: #ffffff;
        }
        
        .nav-item i {
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        /* Navigation menu specific styles - for slideout menu */
        .nav-item-menu {
            position: relative;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #ffffff !important;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .nav-item-menu:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(99, 102, 241, 0.3) 100%);
            color: #ffffff !important;
            transform: translateX(4px);
        }
        
        .nav-item-menu.active-menu {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
        }
        
        .nav-item-menu i {
            color: #ffffff !important;
            font-size: 1.1rem;
        }
        
        .nav-item-menu span {
            color: #ffffff !important;
        }
        
        /* Section header for menu */
        .section-header-menu {
            color: #94a3b8 !important;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 16px 16px 8px;
        }
        
        /* Enhanced Navbar */
        .admin-navbar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
        }
        
        /* Main content area with subtle pattern */
        .main-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
        }
        
        .main-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Enhanced Card styles */
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 
                0 1px 3px rgba(0, 0, 0, 0.05),
                0 10px 40px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #06b6d4 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .card-gradient:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.08),
                0 20px 60px rgba(0, 0, 0, 0.06);
            border-color: rgba(59, 130, 246, 0.3);
        }
        
        .card-gradient:hover::before {
            opacity: 1;
        }
        
        /* Section headers - More visible */
        .section-header {
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 16px 16px 8px;
        }
        
        /* Enhanced Status badges */
        .badge-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
        
        .badge-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
        
        /* Enhanced Icon circles */
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.25rem;
            width: 48px;
            height: 48px;
            transition: all 0.3s ease;
        }
        
        .icon-circle-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        
        .icon-circle-green {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        
        .icon-circle-purple {
            background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
            color: #6b21a8;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
        }
        
        .icon-circle-amber {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }
        
        .icon-circle-red {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        
        /* Enhanced Alert styles */
        .alert-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            color: #065f46;
            border-radius: 12px;
            font-weight: 500;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            font-weight: 500;
        }
        
        /* Logo button enhancement */
        .logo-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .logo-button:hover {
            transform: scale(1.05);
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        }
        
        /* Profile button enhancement */
        .profile-avatar {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Notification badge pulse */
        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Dropdown menu enhancement */
        .dropdown-menu-enhanced {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.98) 0%, rgba(51, 65, 85, 0.98) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
        
        /* Button enhancements */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        }
        
        /* Text visibility enhancements */
        .text-primary {
            color: var(--neutral-900) !important;
            font-weight: 600;
        }
        
        .text-secondary {
            color: var(--neutral-700) !important;
        }
        
        .text-muted {
            color: var(--neutral-600) !important;
        }
        
        /* Ensure all navbar text is visible */
        .admin-navbar * {
            color: #ffffff !important;
        }
        
        .admin-navbar .text-xs {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        /* Table enhancements */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--neutral-900);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 16px;
        }
        
        table tbody tr {
            transition: all 0.2s ease;
        }
        
        table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
        }
        
        /* Input field enhancements */
        input, select, textarea {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s ease;
            color: var(--neutral-900);
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        /* Navigation slideout menu styles */
        .nav-slideout-menu {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        }
        
        .nav-menu-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-menu-link:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(99, 102, 241, 0.3) 100%) !important;
            transform: translateX(4px);
        }
        
        .nav-menu-link span,
        .nav-menu-link i {
            color: white !important;
        }
        
        /* Profile slideout menu styles */
        .profile-slideout-menu {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        }
        
        .profile-menu-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .profile-menu-link:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%) !important;
            transform: translateY(-2px);
        }
        
        .profile-menu-link span,
        .profile-menu-link i {
            color: white !important;
        }
        
        .profile-logout-btn:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4) !important;
        }
        
        .profile-logout-btn span,
        .profile-logout-btn i {
            color: white !important;
        }
    </style>
</head>
<body class="antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <!-- No Sidebar - Navigation moved to dropdown -->

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-0 overflow-hidden main-bg">
            
            <!-- Navbar -->
            <header class="admin-navbar sticky top-0 z-30">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <div class="flex items-center gap-4">
                        <!-- Logo/Navigation Dropdown -->
                        <div class="relative" x-data="{ navOpen: false }">
                            <button @click="navOpen = !navOpen" 
                                    class="logo-button flex items-center gap-3 p-2 pr-4 rounded-xl hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/30">
                                <div class="logo-icon w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-building-columns text-lg"></i>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-base font-bold text-white drop-shadow-sm tracking-tight">MPCMS</p>
                                    <p class="text-xs text-white/80 -mt-0.5">{{ config('cooperative.subtitle', 'Davao del Sur State College') }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-white/80 hidden sm:block transition-transform duration-300" :class="{ 'rotate-180': navOpen }"></i>
                            </button>
                            
                            <!-- Navigation Slideout -->
                            <div x-show="navOpen" 
                                 x-cloak
                                 @click.away="navOpen = false" 
                                 x-transition:enter="transition transform ease-out duration-300" 
                                 x-transition:enter-start="-translate-x-full opacity-0" 
                                 x-transition:enter-end="translate-x-0 opacity-100" 
                                 x-transition:leave="transition transform ease-in duration-200" 
                                 x-transition:leave-start="translate-x-0 opacity-100" 
                                 x-transition:leave-end="-translate-x-full opacity-0"
                                 class="nav-slideout-menu"
                                 style="position: fixed; top: 0; left: 0; height: 100vh; width: 320px; max-width: 90vw; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); box-shadow: 0 20px 60px rgba(0,0,0,0.5); z-index: 9999; display: flex; flex-direction: column;">
                                
                                <!-- Header -->
                                <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                    <div class="logo-icon" style="width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas fa-building-columns" style="font-size: 1.5rem; color: white !important;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <p style="font-size: 1.125rem; font-weight: 700; color: white !important; margin: 0;">MPCMS</p>
                                        <p style="font-size: 0.75rem; color: #94a3b8 !important; margin: 0;">Navigation Menu</p>
                                    </div>
                                    <button @click="navOpen = false" style="color: #94a3b8; background: none; border: none; font-size: 1.25rem; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                                        <i class="fas fa-times" style="color: #94a3b8 !important;"></i>
                                    </button>
                                </div>
                                
                                <!-- Navigation Links -->
                                <div style="flex: 1; overflow-y: auto; padding: 1rem;">
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.dashboard') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-th-large" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Dashboard</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8 !important; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">MANAGEMENT</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.members.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.members.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-users" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Members</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-registration.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.member-registration.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-user-plus" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Registration</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-password.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.member-password.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-key" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Password</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-sessions.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.member-sessions.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-user-clock" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Sessions</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8 !important; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">FINANCE</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.finance.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.finance.index') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.contributions.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-wallet" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Finance</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.finance.repayment-confirmation') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.finance.repayment-confirmation') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-receipt" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Repayment</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.amount-held.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.amount-held.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-piggy-bank" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Savings</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8 !important; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">ANALYTICS</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.reports.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.reports.index') || request()->routeIs('admin.reports.contributions') || request()->routeIs('admin.reports.loans') || request()->routeIs('admin.reports.dividends*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-chart-line" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Reports</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.reports.activity-logs') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.reports.activity-logs') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-history" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Activity Logs</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.notifications.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.notifications.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-bell" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Notifications</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.messages.index') }}" 
                                           class="nav-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white !important; text-decoration: none; background: {{ request()->routeIs('admin.messages.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-envelope" style="width: 1.25rem; text-align: center; color: white !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Messages</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div x-show="navOpen" @click.away="navOpen = false" 
                                 x-transition:enter="transition transform ease-out duration-300" 
                                 x-transition:enter-start="-translate-x-full opacity-0" 
                                 x-transition:enter-end="translate-x-0 opacity-100" 
                                 x-transition:leave="transition transform ease-in duration-200" 
                                 x-transition:leave-start="translate-x-0 opacity-100" 
                                 x-transition:leave-end="-translate-x-full opacity-0"
                                 style="position: fixed !important; top: 0 !important; left: 0 !important; height: 100% !important; width: 320px !important; max-width: 90vw !important; background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: flex !important; flex-direction: column !important;">
                                
                                <!-- Header -->
                                <div style="display: flex !important; align-items: center !important; gap: 1rem !important; padding: 1.5rem !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;">
                                    <div class="logo-icon" style="width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);">
                                        <i class="fas fa-building-columns" style="font-size: 1.5rem; color: white;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <p style="font-size: 1.125rem; font-weight: 700; color: white; margin: 0;">MPCMS</p>
                                        <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">Navigation Menu</p>
                                    </div>
                                    <button @click="navOpen = false" style="color: #94a3b8; background: none; border: none; font-size: 1.25rem; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem;">
                                        <i class="fas fa-times" style="color: #94a3b8;"></i>
                                    </button>
                                </div>
                                
                                <!-- Navigation Links -->
                                <div style="flex: 1 !important; overflow-y: auto !important; padding: 1rem !important; background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;">
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <a href="{{ route('admin.dashboard') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.dashboard') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-th-large" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Dashboard</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">MANAGEMENT</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.members.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.members.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-users" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Members</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-registration.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.member-registration.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-user-plus" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Registration</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-password.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.member-password.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-key" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Password</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.member-sessions.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.member-sessions.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-user-clock" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Sessions</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">FINANCE</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.finance.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.finance.index') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.contributions.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-wallet" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Finance</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.finance.repayment-confirmation') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.finance.repayment-confirmation') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-receipt" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Repayment</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.amount-held.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.amount-held.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-piggy-bank" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Savings</span>
                                        </a>
                                        
                                        <div style="padding: 1rem 1rem 0.5rem;">
                                            <p style="color: #94a3b8; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">ANALYTICS</p>
                                        </div>
                                        
                                        <a href="{{ route('admin.reports.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.reports.index') || request()->routeIs('admin.reports.contributions') || request()->routeIs('admin.reports.loans') || request()->routeIs('admin.reports.dividends*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-chart-line" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Reports</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.reports.activity-logs') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.reports.activity-logs') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-history" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Activity Logs</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.notifications.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.notifications.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-bell" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Notifications</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.messages.index') }}" 
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; text-decoration: none; background: {{ request()->routeIs('admin.messages.*') ? 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)' : 'rgba(255,255,255,0.05)' }}; transition: all 0.3s;">
                                            <i class="fas fa-envelope" style="width: 1.25rem; text-align: center; color: white; font-size: 1.1rem;"></i>
                                            <span style="color: white;">Messages</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l border-white/20 pl-4 ml-2 hidden md:block">
                            <h1 class="text-lg font-bold text-white drop-shadow-sm">@yield('title', 'Dashboard')</h1>
                            <p class="text-xs text-white/80 -mt-0.5">Cooperative Management System</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="relative p-2.5 rounded-xl text-white hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/30">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-2 right-2 h-2.5 w-2.5 bg-red-500 rounded-full pulse-dot border-2 border-white"></span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden z-50">
                                <div class="px-5 py-4 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-white/10">
                                    <h3 class="text-base font-bold text-white">Notifications</h3>
                                    <p class="text-xs text-white/80 mt-0.5">You have 3 unread messages</p>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-blue flex-shrink-0">
                                                <i class="fas fa-user-plus text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900">New member registration</p>
                                                <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-amber flex-shrink-0">
                                                <i class="fas fa-file-invoice-dollar text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900">Loan application pending</p>
                                                <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-green flex-shrink-0">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-1">3 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 border-t border-gray-100">
                                    <a href="{{ route('admin.notifications.index') }}" class="block text-center text-sm font-semibold text-blue-600 hover:text-blue-700 py-2 rounded-lg hover:bg-blue-50 transition">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/30">
                                <div class="profile-avatar w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-base">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-bold text-white drop-shadow-sm">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-white/80 -mt-0.5">{{ auth()->user()->email ?? 'admin@mpcms.com' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-white/80 hidden sm:block transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                            </button>
                            
                            <!-- Profile Slideout -->
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false" 
                                 x-transition:enter="transition transform ease-out duration-300" 
                                 x-transition:enter-start="translate-x-full opacity-0" 
                                 x-transition:enter-end="translate-x-0 opacity-100" 
                                 x-transition:leave="transition transform ease-in duration-200" 
                                 x-transition:leave-start="translate-x-0 opacity-100" 
                                 x-transition:leave-end="translate-x-full opacity-0"
                                 class="profile-slideout-menu"
                                 style="position: fixed; top: 0; right: 0; height: 100vh; width: 320px; max-width: 90vw; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); box-shadow: 0 20px 60px rgba(0,0,0,0.5); z-index: 9999; display: flex; flex-direction: column;">
                                
                                <!-- Header -->
                                <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                    <div class="profile-avatar" style="width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white !important; font-weight: 700; font-size: 1.5rem;">
                                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-size: 1.125rem; font-weight: 700; color: white !important; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ auth()->user()->name ?? 'Admin' }}</p>
                                        <p style="font-size: 0.75rem; color: #94a3b8 !important; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ auth()->user()->email ?? 'admin@mpcms.com' }}</p>
                                    </div>
                                    <button @click="open = false" style="color: #94a3b8; background: none; border: none; font-size: 1.25rem; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem; flex-shrink: 0; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                                        <i class="fas fa-times" style="color: #94a3b8 !important;"></i>
                                    </button>
                                </div>
                                
                                <!-- Menu Content -->
                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                        <a href="{{ route('admin.account-settings.index') }}" 
                                           class="profile-menu-link"
                                           style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 0.75rem; font-size: 1rem; font-weight: 600; color: white !important; text-decoration: none; background: rgba(255,255,255,0.05); transition: all 0.3s;">
                                            <i class="fas fa-user-cog" style="width: 1.25rem; color: #60a5fa !important; font-size: 1.1rem;"></i>
                                            <span style="color: white !important;">Account Settings</span>
                                        </a>
                                    </div>
                                    <div style="padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                                        <form method="POST" action="{{ route('admin.logout') }}" style="width: 100%;">
                                            @csrf
                                            <button type="submit" 
                                                    class="profile-logout-btn"
                                                    style="display: flex; align-items: center; gap: 0.75rem; width: 100%; padding: 0.875rem 1rem; border-radius: 0.75rem; font-size: 1rem; font-weight: 600; color: white !important; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); transition: all 0.3s;">
                                                <i class="fas fa-sign-out-alt" style="width: 1.25rem; color: white !important; font-size: 1.1rem;"></i>
                                                <span style="color: white !important;">Sign Out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="relative z-10 flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto py-6 px-4 lg:px-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 alert-success text-sm font-medium rounded-lg flex items-center gap-3 hover-lift">
                            <div class="icon-circle icon-circle-green w-8 h-8 flex-shrink-0">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 alert-error text-sm font-medium rounded-lg flex items-center gap-3 hover-lift">
                            <div class="icon-circle" style="background-color: #fee2e2; color: #7f1d1d; width: 32px; height: 32px;">
                                <i class="fas fa-exclamation text-sm"></i>
                            </div>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 hidden" role="dialog" aria-modal="true" style="z-index: 99999; isolation: isolate;">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm z-[1]" id="confirmModalBackdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 z-[2]" id="confirmModalContent">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 id="confirmTitle" class="text-lg font-semibold text-gray-900 text-center mb-2">Confirm Action</h3>
                    <p id="confirmDesc" class="text-sm text-gray-600 text-center">Are you sure you want to proceed?</p>
                </div>
                <div class="flex gap-3 p-4 bg-gray-50 border-t border-gray-200">
                    <button type="button" id="confirmCancelBtn" onclick="window.confirmModalClose()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">Cancel</button>
                    <button type="button" id="confirmOkBtn" onclick="window.confirmModalConfirm()" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition cursor-pointer">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const modal = document.getElementById('confirmModal');
        if (!modal) return;
        const desc = document.getElementById('confirmDesc');
        const okBtn = document.getElementById('confirmOkBtn');
        let pendingAction = null;

        window.confirmModalClose = function() {
            modal.classList.add('hidden');
            pendingAction = null;
        };

        window.confirmModalConfirm = function() {
            const fn = pendingAction;
            modal.classList.add('hidden');
            pendingAction = null;
            if (typeof fn === 'function') fn();
        };

        function openModal(message, onConfirm) {
            desc.textContent = message || 'Are you sure you want to proceed?';
            pendingAction = onConfirm;
            modal.classList.remove('hidden');
            okBtn.focus();
        }

        document.getElementById('confirmModalBackdrop').addEventListener('click', window.confirmModalClose);
        document.getElementById('confirmModalContent').addEventListener('click', function(e) { e.stopPropagation(); });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) window.confirmModalClose();
        });

        document.addEventListener('click', function(e) {
            const target = e.target.closest('.js-confirm, .js-confirm-delete');
            if (!target) return;
            e.preventDefault();
            e.stopPropagation();
            const form = target.closest('form');
            const message = target.getAttribute('data-confirm-message') || 'Are you sure you want to proceed?';
            openModal(message, function() {
                if (form) form.submit();
                else {
                    const href = target.getAttribute('href');
                    if (href && href !== '#') window.location.href = href;
                }
            });
        });
    })();
    </script>

    @yield('scripts')
</body>
</html>
