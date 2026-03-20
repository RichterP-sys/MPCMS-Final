<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ThemeConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::guard('member')->check()) {
                $theme = Auth::guard('member')->user()->theme ?: 'indigo';
            } else {
                $theme = session('theme', request('theme', 'indigo'));
            }
            $themes = [
                'indigo' => [
                    'from' => 'from-indigo-500',
                    'via' => 'via-sky-500',
                    'to' => 'to-blue-600',
                    'accent' => 'text-indigo-600',
                    'ring' => 'focus:ring-indigo-500',
                    'border' => 'focus:border-indigo-500',
                    'button' => 'bg-indigo-600 hover:bg-indigo-700',
                ],
                'emerald' => [
                    'from' => 'from-emerald-500',
                    'via' => 'via-teal-500',
                    'to' => 'to-cyan-600',
                    'accent' => 'text-emerald-600',
                    'ring' => 'focus:ring-emerald-500',
                    'border' => 'focus:border-emerald-500',
                    'button' => 'bg-emerald-600 hover:bg-emerald-700',
                ],
                'rose' => [
                    'from' => 'from-rose-500',
                    'via' => 'via-pink-500',
                    'to' => 'to-fuchsia-600',
                    'accent' => 'text-rose-600',
                    'ring' => 'focus:ring-rose-500',
                    'border' => 'focus:border-rose-500',
                    'button' => 'bg-rose-600 hover:bg-rose-700',
                ],
            ];
            $themeConfig = $themes[$theme] ?? $themes['indigo'];
            $view->with('themeConfig', $themeConfig);
        });
    }
}
