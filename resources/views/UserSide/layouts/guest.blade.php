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
    
    <!-- Outfit Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }

        [x-cloak] { display: none !important; }

        @keyframes floatSlow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(12px); } 100% { opacity: 1; transform: translateY(0); } }
        .float-slow { animation: floatSlow 6s ease-in-out infinite; }
        .fade-in-up { animation: fadeInUp 600ms ease-out both; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
@php
    $brandName = config('app.name', 'MPCMS');
    $theme = session('theme', request('theme', 'indigo'));
    $themes = [
        'indigo' => ['from' => 'from-indigo-500', 'via' => 'via-blue-500', 'to' => 'to-indigo-600', 'accent' => 'text-indigo-600', 'ring' => 'focus:ring-indigo-500', 'border' => 'focus:border-indigo-500', 'button' => 'bg-indigo-600 hover:bg-indigo-700'],
        'green' => ['from' => 'from-green-500', 'via' => 'via-green-500', 'to' => 'to-green-600', 'accent' => 'text-green-600', 'ring' => 'focus:ring-green-500', 'border' => 'focus:border-green-500', 'button' => 'bg-green-600 hover:bg-green-700'],
        'red' => ['from' => 'from-red-500', 'via' => 'via-red-500', 'to' => 'to-red-600', 'accent' => 'text-red-600', 'ring' => 'focus:ring-red-500', 'border' => 'focus:border-red-500', 'button' => 'bg-red-600 hover:bg-red-700'],
    ];
    $themeConfig = $themes[$theme] ?? $themes['indigo'];
@endphp
<body class="bg-gradient-to-br {{ $themeConfig['from'] }} {{ $themeConfig['via'] }} {{ $themeConfig['to'] }} text-slate-900 min-h-screen">
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Alpine.js for lightweight interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
