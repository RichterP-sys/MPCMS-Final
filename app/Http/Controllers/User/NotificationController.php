<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $member = Auth::guard('member')->user();
        
        $notifications = Notification::where('notifiable_type', \App\Models\Member::class)
            ->where('notifiable_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $unreadCount = Notification::where('notifiable_type', \App\Models\Member::class)
            ->where('notifiable_id', $member->id)
            ->unread()
            ->count();
        
        return view('UserSide.notifications.index', compact('notifications', 'unreadCount'));
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $member = Auth::guard('member')->user();
        
        $notification = Notification::where('notifiable_type', \App\Models\Member::class)
            ->where('notifiable_id', $member->id)
            ->where('id', $id)
            ->firstOrFail();
            
        $notification->markAsRead();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $member = Auth::guard('member')->user();
        
        Notification::where('notifiable_type', \App\Models\Member::class)
            ->where('notifiable_id', $member->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $member = Auth::guard('member')->user();
        
        $notification = Notification::where('notifiable_type', \App\Models\Member::class)
            ->where('notifiable_id', $member->id)
            ->where('id', $id)
            ->firstOrFail();
            
        $notification->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification deleted.');
    }
}
