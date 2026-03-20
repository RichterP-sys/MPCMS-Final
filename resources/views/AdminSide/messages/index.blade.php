@extends('AdminSide.layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<style>
    .message-card {
        transition: all 0.2s ease;
    }
    .message-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.2);
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 lg:p-8" style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 25%, #818cf8 50%, #a5b4fc 75%, #c7d2fe 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative">
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Contact Messages</h1>
            <p class="text-indigo-200 mt-1">Messages from the website contact form</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Messages</p>
                    <p class="text-xl font-bold text-indigo-600">{{ $messages->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                    <i class="fas fa-envelope-open text-white"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Unread</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $unreadCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="font-semibold text-slate-900">All Messages</h3>
            <p class="text-xs text-slate-500">From the welcome page contact form</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($messages as $msg)
            <a href="{{ route('admin.messages.show', $msg) }}" class="message-card block p-4 flex items-start gap-4 hover:bg-slate-50/80 {{ !$msg->is_read ? 'bg-indigo-50/50' : '' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ !$msg->is_read ? 'bg-indigo-500' : 'bg-slate-300' }}">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-semibold text-slate-900 {{ !$msg->is_read ? 'font-bold' : '' }}">{{ $msg->name }}</p>
                        <span class="text-xs text-slate-500 flex-shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-600 truncate">{{ $msg->subject }}</p>
                    <p class="text-xs text-slate-500 mt-1 truncate">{{ Str::limit($msg->message, 80) }}</p>
                </div>
                @if(!$msg->is_read)
                    <span class="px-2 py-0.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full flex-shrink-0">New</span>
                @endif
                <i class="fas fa-chevron-right text-slate-300 flex-shrink-0"></i>
            </a>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                </div>
                <p class="text-slate-600 font-medium">No messages yet</p>
                <p class="text-sm text-slate-500 mt-1">Messages from the website contact form will appear here</p>
            </div>
            @endforelse
        </div>
        @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
