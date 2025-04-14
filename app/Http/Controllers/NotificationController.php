<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json(['notifications' => $notifications]);
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Check if notification belongs to authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $notification->read = true;
        $notification->save();
        
        return response()->json(['message' => 'Notification marked as read']);
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
            
        return response()->json(['message' => 'All notifications marked as read']);
    }
}