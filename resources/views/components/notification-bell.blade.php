<div x-data="notificationBell()" class="relative">
    <!-- Notification Button -->
    <button
        @click="toggle()"
        type="button"
        class="relative flex rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        id="notification-button"
        :aria-expanded="isOpen.toString()"
        aria-haspopup="true"
        aria-controls="notification-dropdown"
    >
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white" x-text="unreadCount"></span>
        </template>
    </button>

    <!-- Notification Dropdown -->
    <div
        x-show="isOpen"
        @click.away="isOpen = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        id="notification-dropdown"
        class="absolute right-0 z-50 mt-2 w-72 md:w-96 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="notification-button"
        tabindex="-1"
    >
    <!-- Notification Content -->
    <div class="border-b border-gray-200 px-4 py-2">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-900">Notifications</p>
            <button
                x-show="unreadCount > 0"
                type="button"
                class="text-sm text-blue-600 hover:text-blue-800"
                @click="markAllAsRead()"
            >
                Mark all as read
            </button>
        </div>
    </div>

    @if($notifications->count() > 0)
        <div class="max-h-96 overflow-y-auto">
            @foreach($notifications as $notification)
                <a
                    href="{{ $notification->data['url'] ?? '#' }}"
                    class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100"
                    @if($notification->unread())
                        @click.prevent="markAsRead('{{ $notification->id }}', $event)"
                    @endif
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-blue-500"></i>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="mt-1 text-sm text-gray-500 break-words">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if($notification->unread())
                            <div class="ml-4 flex-shrink-0">
                                <span id="notification-{{ $notification->id }}" class="inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="border-t border-gray-200 px-4 py-2 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                View all notifications
            </a>
        </div>
    @else
        <div class="px-4 py-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
            <p class="mt-1 text-sm text-gray-500">You don't have any unread notifications.</p>
        </div>
    @endif
    </div>

    <!-- Toast Notification -->
    <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-4 right-4 px-4 py-2 rounded-md text-white z-[100]"
        :class="{
            'bg-green-500': toastType === 'success',
            'bg-red-500': toastType === 'error',
            'bg-blue-500': toastType === 'info'
        }"
        x-cloak>
        <div class="flex items-center">
            <span x-text="toastMessage"></span>
            <button @click="showToast = false" class="ml-4" aria-label="Close notification">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationBell', () => ({
            isOpen: false,
            showToast: false,
            toastMessage: '',
            toastType: 'info',
            unreadCount: {{ $unreadCount }},
            
            init() {
                // Close when clicking outside
                document.addEventListener('click', (e) => {
                    if (this.isOpen && !this.$el.contains(e.target)) {
                        this.isOpen = false;
                    }
                });
            },
            
            toggle() {
                this.isOpen = !this.isOpen;
            },
            
            close() {
                this.isOpen = false;
            },
            
            async markAsRead(notificationId, event) {
                event.preventDefault();
                event.stopPropagation();

                // Store the URL before any async operations
                const url = event.currentTarget.getAttribute('href');
                const notificationElement = event.currentTarget.closest('[data-notification]');
                const badge = document.getElementById(`notification-${notificationId}`);
                
                // Optimistically update UI
                if (badge) {
                    badge.remove();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }

                try {
                    // Make the API call
                    const response = await fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error('Failed to mark notification as read');
                    }

                    // Navigate to the URL after marking as read
                    if (url && url !== '#') {
                        window.location.href = url;
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    this.showToastMessage('Failed to mark notification as read. Please try again.', 'error');
                }
            },
            
            async markAllAsRead() {
                const unreadBadges = Array.from(document.querySelectorAll('[id^="notification-"]'));
                const originalCount = this.unreadCount;

                // Optimistically update UI
                this.unreadCount = 0;
                unreadBadges.forEach(el => el.remove());

                try {
                    const response = await fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Failed to mark all notifications as read');
                    }

                    this.showToastMessage('All notifications marked as read', 'success');
                } catch (error) {
                    console.error('Error marking all as read:', error);
                    this.unreadCount = originalCount;
                    this.showToastMessage(
                        error.message || 'Failed to mark notifications as read. Please try again.',
                        'error'
                    );
                }
            },
            
            showToastMessage(message, type = 'info') {
                this.toastMessage = message;
                this.toastType = type;
                this.showToast = true;
                
                setTimeout(() => {
                    this.showToast = false;
                }, 5000);
            }
        }));
    });
</script>
