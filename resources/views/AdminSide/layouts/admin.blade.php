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
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f3f4f6; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        
        /* Dark Professional Theme */
        body {
            background-color: #f9fafb;
            color: #1f2937;
        }

        .admin-sidebar {
            background-color: #1f2937;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
            border-right: 1px solid #374151;
        }
        
        /* Nav item styles - Simple and Professional */
        .nav-item {
            position: relative;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #d1d5db;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #f3f4f6;
        }
        
        .nav-item.active {
            background-color: #374151;
            color: #fff;
            border-left: 3px solid #3b82f6;
        }
        
        .nav-item.active i {
            color: #60a5fa;
        }
        
        .nav-item i {
            transition: color 0.2s ease;
        }
        
        /* Navbar */
        .admin-navbar {
            background: linear-gradient(90deg, #312e81 0%, #6366f1 100%);
            border-bottom: 1px solid #4338ca;
            box-shadow: 0 2px 8px 0 rgba(99, 102, 241, 0.07);
        }
        .admin-navbar .text-gray-900,
        .admin-navbar .text-gray-700 {
            color: #fff !important;
        }
        .admin-navbar .text-gray-500 {
            color: #c7d2fe !important;
        }
        .admin-navbar .text-indigo-600 {
            color: #a5b4fc !important;
        }
        .admin-navbar .hover\:bg-gray-100:hover {
            background-color: rgba(255,255,255,0.08) !important;
        }
        .admin-navbar .rounded-lg {
            border-radius: 10px !important;
        }
        .admin-navbar .fa-bell,
        .admin-navbar .fa-chevron-down {
            color: #c7d2fe !important;
        }
        .admin-navbar .fa-bell {
            transition: color 0.2s;
        }
        .admin-navbar .fa-bell:hover {
            color: #fff !important;
        }
        .admin-navbar .bg-gradient-to-br {
            background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%) !important;
        }
        .admin-navbar .bg-white {
            background: rgba(255,255,255,0.10) !important;
        }
        .admin-navbar .border-gray-200 {
            border-color: #6366f1 !important;
        }
        
        /* Main content area */
        .main-bg {
            background-color: #f9fafb;
        }
        
        /* Card styles - Clean and Simple */
        .card-gradient {
            background-color: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .card-gradient:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }
        
        /* Hover lift effect */
        .hover-lift {
            transition: all 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Section headers */
        .section-header {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px 8px;
        }
        
        /* Status badges */
        .badge-success {
            background-color: #ecfdf5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #654321;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #7f1d1d;
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: #084594;
        }
        
        /* Icon background circles */
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 18px;
        }
        
        .icon-circle-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .icon-circle-green {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .icon-circle-purple {
            background-color: #e9d5ff;
            color: #581c87;
        }
        
        .icon-circle-amber {
            background-color: #fed7aa;
            color: #92400e;
        }
        
        /* Alert styles */
        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #7f1d1d;
        }
        
        /* Sidebar logo */
        .sidebar-logo {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        <!-- Animated background like member portal -->
        <div class="admin-animated-bg">
            <div class="admin-bg-circle admin-bg-circle-1"></div>
            <div class="admin-bg-circle admin-bg-circle-2"></div>
            <div class="admin-bg-circle admin-bg-circle-3"></div>
            <div class="admin-bg-plus-pattern"></div>
        </div>
        <style>
        .admin-animated-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }
        .admin-bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.18;
            animation: adminCircleFloat 24s ease-in-out infinite;
        }
        .admin-bg-circle-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle at 30% 30%, #818cf8 0%, #a5b4fc 100%);
            top: -180px; left: -180px;
            animation-delay: 0s;
        }
        .admin-bg-circle-2 {
            width: 420px; height: 420px;
            background: radial-gradient(circle at 70% 70%, #f472b6 0%, #fcd34d 100%);
            bottom: -120px; right: 10%;
            animation-delay: 8s;
        }
        .admin-bg-circle-3 {
            width: 320px; height: 320px;
            background: radial-gradient(circle at 60% 40%, #6ee7b7 0%, #3b82f6 100%);
            top: 40%; right: -100px;
            animation-delay: 16s;
        }
        @keyframes adminCircleFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            25% { transform: translateY(-40px) scale(1.05); }
            50% { transform: translateY(30px) scale(0.97); }
            75% { transform: translateY(-20px) scale(1.03); }
        }
        .admin-bg-plus-pattern {
            position: absolute;
            inset: 0;
            background-image: url('data:image/svg+xml;utf8,<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="18" width="4" height="40" rx="2" fill="%23e0e7ef" fill-opacity="0.18"/><rect y="18" width="40" height="4" rx="2" fill="%23e0e7ef" fill-opacity="0.18"/></svg>');
            background-size: 40px 40px;
            opacity: 0.18;
            pointer-events: none;
        }
        </style>
    </style>
</head>
<body class="antialiased" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile overlay -->
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Sidebar -->
        <aside class="admin-sidebar fixed lg:static inset-y-0 left-0 z-50 flex flex-col overflow-hidden transition-all duration-300"
               :class="[sidebarOpen ? 'w-72' : 'w-20', mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">
            
            <!-- Logo -->
            <div class="relative z-10 flex items-center h-16 px-6 border-b border-gray-700">
                <div class="flex items-center gap-3" :class="{ 'justify-center w-full': !sidebarOpen }">
                    <div class="sidebar-logo w-10 h-10">
                        <i class="fas fa-building-columns text-white"></i>
                    </div>
                    <div x-show="sidebarOpen" x-transition.opacity.duration.200ms>
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ config('app.name', 'MPCMS') }}</h1>
                        <p class="text-xs text-gray-400 -mt-0.5">{{ config('cooperative.subtitle', 'Davao del Sur State College') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="relative z-10 flex-1 overflow-y-auto py-6 px-4">
                <div class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-th-large w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Dashboard</span>
                    </a>
                    
                    <div x-show="sidebarOpen" class="pt-4 pb-2">
                        <p class="section-header">Management</p>
                    </div>
                    
                    <a href="{{ route('admin.members.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-users w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Members</span>
                    </a>
                    
                    <a href="{{ route('admin.member-registration.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.member-registration.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-user-plus w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Registration</span>
                    </a>
                    <a href="{{ route('admin.member-password.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.member-password.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-key w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Password</span>
                    </a>
                    <a href="{{ route('admin.member-sessions.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.member-sessions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-user-clock w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Sessions</span>
                    </a>
                    
                    <div x-show="sidebarOpen" class="pt-4 pb-2">
                        <p class="section-header">Finance</p>
                    </div>
                    
                    <a href="{{ route('admin.finance.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.finance.index') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.contributions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-wallet w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Finance</span>
                    </a>
                    <a href="{{ route('admin.finance.repayment-confirmation') }}" 
                       class="nav-item {{ request()->routeIs('admin.finance.repayment-confirmation') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-receipt w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Repayment</span>
                    </a>
                    
                    <a href="{{ route('admin.amount-held.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.amount-held.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-piggy-bank w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Savings</span>
                    </a>
                    
                    <div x-show="sidebarOpen" class="pt-4 pb-2">
                        <p class="section-header">Analytics</p>
                    </div>
                    
                    <a href="{{ route('admin.reports.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.reports.index') || request()->routeIs('admin.reports.contributions') || request()->routeIs('admin.reports.loans') || request()->routeIs('admin.reports.dividends*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-chart-line w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Reports</span>
                    </a>
                    
                    <a href="{{ route('admin.reports.activity-logs') }}" 
                       class="nav-item {{ request()->routeIs('admin.reports.activity-logs') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-history w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Activity Logs</span>
                    </a>
                    
                    <a href="{{ route('admin.notifications.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-bell w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Notifications</span>
                    </a>
                    <a href="{{ route('admin.messages.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-sm font-semibold"
                       :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-envelope w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen">Messages</span>
                    </a>
                </div>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="relative z-10 p-4 border-t border-gray-700">
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="hidden lg:flex items-center gap-3 w-full px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-white rounded-lg hover:bg-gray-700 transition-all"
                        :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-chevron-left text-blue-400 transition-transform duration-300" :class="{ 'rotate-180': !sidebarOpen }"></i>
                    <span x-show="sidebarOpen">Collapse</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-0 overflow-hidden main-bg">
            
            <!-- Navbar -->
            <header class="admin-navbar sticky top-0 z-30">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-xs text-gray-500 hidden sm:block">Cooperative Management System</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 sm:gap-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="relative p-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-1.5 right-1.5 h-2.5 w-2.5 bg-red-500 rounded-full pulse-dot"></span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition:enter="dropdown-enter"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-gray-200 overflow-hidden z-50">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    <p class="text-xs text-gray-500">You have 3 unread messages</p>
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-blue w-9 h-9 flex-shrink-0">
                                                <i class="fas fa-user-plus text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-900">New member registration</p>
                                                <p class="text-xs text-gray-500 mt-0.5">2 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-amber w-9 h-9 flex-shrink-0">
                                                <i class="fas fa-file-invoice-dollar text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-900">Loan application pending</p>
                                                <p class="text-xs text-gray-500 mt-0.5">1 hour ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 cursor-pointer transition">
                                        <div class="flex gap-3">
                                            <div class="icon-circle icon-circle-green w-9 h-9 flex-shrink-0">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-900">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-0.5">3 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 border-t border-gray-100">
                                    <a href="#" class="block text-center text-sm font-medium text-blue-600 hover:text-blue-700">View all notifications</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-3 p-1.5 pr-3 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@mpcms.com' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-600 hidden sm:block transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition:enter="dropdown-enter"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-gray-200 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@mpcms.com' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('admin.account-settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-user-cog w-4 text-blue-500"></i>Account Settings
                                    </a>
                                </div>
                                <div class="py-2 border-t border-gray-200">
                                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                            <i class="fas fa-sign-out-alt w-4"></i>Sign Out
                                        </button>
                                    </form>
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
