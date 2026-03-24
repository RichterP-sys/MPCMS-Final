@php
    $theme = Auth::guard('member')->check()
        ? (Auth::guard('member')->user()->theme ?: 'indigo')
        : session('theme', request('theme', 'indigo'));
    $themes = [
        'indigo' => ['from' => 'from-indigo-500', 'via' => 'via-blue-500', 'to' => 'to-indigo-600', 'accent' => 'text-indigo-600', 'ring' => 'focus:ring-indigo-500', 'border' => 'focus:border-indigo-500', 'button' => 'bg-indigo-600 hover:bg-indigo-700'],
        'green' => ['from' => 'from-green-500', 'via' => 'via-green-500', 'to' => 'to-green-600', 'accent' => 'text-green-600', 'ring' => 'focus:ring-green-500', 'border' => 'focus:border-green-500', 'button' => 'bg-green-600 hover:bg-green-700'],
        'red' => ['from' => 'from-red-500', 'via' => 'via-red-500', 'to' => 'to-red-600', 'accent' => 'text-red-600', 'ring' => 'focus:ring-red-500', 'border' => 'focus:border-red-500', 'button' => 'bg-red-600 hover:bg-red-700'],
    ];
    $themeConfig = $themes[$theme] ?? $themes['indigo'];
@endphp
