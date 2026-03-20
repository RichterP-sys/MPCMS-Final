@extends('AdminSide.layouts.admin')

@section('title', 'View Message')

@section('content')
<div class="space-y-6">
    <!-- Back link -->
    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
        <i class="fas fa-arrow-left"></i>
        Back to Messages
    </a>

    <!-- Message Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">{{ $message->subject }}</h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-500">{{ $message->created_at->format('M d, Y \a\t g:i A') }}</span>
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $message->name }}</p>
                    <a href="mailto:{{ $message->email }}" class="text-indigo-600 hover:text-indigo-700 text-sm">{{ $message->email }}</a>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide mb-2">Message</h3>
                <div class="prose prose-slate max-w-none text-slate-700 whitespace-pre-wrap">{{ $message->message }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
