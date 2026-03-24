<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cooperative Member Portal')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Alpine.js for lightweight interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Navigation animations */
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::before {
            width: 100%;
        }
        
        .nav-item.active::before {
            width: 100%;
        }
        
        /* Sidebar animations */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        /* Profile dropdown animations */
        .dropdown-menu {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        
        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        
        /* Mobile menu animations */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .mobile-menu.open {
            max-height: 500px;
        }
        
        /* Logo animation */
        .logo {
            transition: all 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        /* Button hover effects */
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }
        
        /* Notification badge pulse */
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(12px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { animation: fadeInUp 600ms ease-out both; }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08); }
        
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
    </style>
    
    @stack('styles')
</head>
@php
    $theme = session('theme', request('theme', 'indigo'));
    $themes = [
        'indigo' => ['from' => 'from-indigo-500', 'via' => 'via-blue-500', 'to' => 'to-indigo-600', 'accent' => 'text-indigo-600', 'ring' => 'focus:ring-indigo-500', 'border' => 'focus:border-indigo-500', 'button' => 'bg-indigo-600 hover:bg-indigo-700'],
        'green' => ['from' => 'from-green-500', 'via' => 'via-green-500', 'to' => 'to-green-600', 'accent' => 'text-green-600', 'ring' => 'focus:ring-green-500', 'border' => 'focus:border-green-500', 'button' => 'bg-green-600 hover:bg-green-700'],
        'red' => ['from' => 'from-red-500', 'via' => 'via-red-500', 'to' => 'to-red-600', 'accent' => 'text-red-600', 'ring' => 'focus:ring-red-500', 'border' => 'focus:border-red-500', 'button' => 'bg-red-600 hover:bg-red-700'],
    ];
    $themeConfig = $themes[$theme] ?? $themes['indigo'];
@endphp
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Page Content -->
        <main class="flex-grow">
            @if(session('status'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>