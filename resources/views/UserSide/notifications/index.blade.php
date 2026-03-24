@extends('UserSide.layouts.app')

@section('title', 'My Notifications')

@section('content')
@include('UserSide.partials.theme-config')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
<style>
    .notification-item {
        transition: all 0.2s ease;
    }
    .notification-item:hover {
        transform: translateX(4px);
    }
    .notification-unread {
        border-left: 4px solid;
    }
    .notification-unread.color-emerald { border-color: #10b981; }
    .notification-unread.color-red { border-color: #ef4444; }
    .notification-unread.color-amber { border-color: #f59e0b; }
    .notification-unread.color-blue { border-color: #3b82f6; }
    .notification-unread.color-indigo { border-color: #6366f1; }
    .notification-unread.color-purple { border-color: #8b5cf6; }
    .notification-unread.color-pink { border-color: #ec4899; }
</style>

<div class="py-6 fade-in-up">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                    <p class="text-sm text-gray-500 mt-1">Stay updated with your account activities</p>
                </div>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        {{ $unreadCount }} unread
                    </span>
                @endif
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">All Notifications</h3>
                    @if($notifications->count() > 0 && $unreadCount > 0)
                        <form action="{{ route('user.notifications.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium rounded-lg {{ $themeConfig['button'] }} text-white shadow-sm transition hover:opacity-90">
                                <svg class="w-4 h-4" data-feather="check" stroke-width="2.5"></svg>
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            $color = $data['color'] ?? 'blue';
                            $icon = $data['icon'] ?? 'fa-bell';
                            
                            $colorClasses = [
                                'green' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'light' => 'bg-green-50'],
                                'red' => ['bg' => 'bg-red-500', 'text' => 'text-red-600', 'light' => 'bg-red-50'],
                                'yellow' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'light' => 'bg-yellow-50'],
                                'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'light' => 'bg-blue-50'],
                                'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'light' => 'bg-indigo-50'],
                            ];
                            $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
                        @endphp
                        <div class="notification-item p-4 {{ $isUnread ? 'notification-unread color-' . $color . ' ' . $colorClass['light'] : 'bg-white' }}">
                            <div class="flex items-start gap-4">
                                <!-- Icon -->
                                <div class="w-10 h-10 rounded-xl {{ $colorClass['bg'] }} flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" data-feather="bell" stroke-width="2.5"></svg>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $data['title'] ?? 'Notification' }}
                                                @if($isUnread)
                                                    <span class="ml-2 inline-flex w-2 h-2 rounded-full {{ $colorClass['bg'] }}"></span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $data['message'] ?? '' }}</p>
                                            <p class="text-xs text-gray-400 mt-2">
                                                <svg class="w-4 h-4 inline mr-1" data-feather="clock" stroke-width="2.5"></svg>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center gap-1">
                                            @if($isUnread)
                                                <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Mark as read">
                                                        <svg class="w-4 h-4" data-feather="check" stroke-width="2.5"></svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('user.notifications.destroy', $notification->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete" onclick="return confirm('Delete this notification?')">
                                                    <svg class="w-4 h-4" data-feather="trash-2" stroke-width="2.5"></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" data-feather="bell-off" stroke-width="2.5"></svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No notifications</h3>
                    <p class="text-sm text-gray-500">You'll receive notifications about your loans, contributions, and cooperative updates here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
