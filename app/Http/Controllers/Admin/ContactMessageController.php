<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = ContactMessage::unread()->count();

        return view('AdminSide.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display the specified contact message.
     */
    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return view('AdminSide.messages.show', compact('message'));
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Remove the specified contact message.
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}
