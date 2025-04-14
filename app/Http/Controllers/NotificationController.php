<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request)
    {
        Auth::user()->unreadNotifications()->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function sendNotification(Request $request)
    {
        // Implementation for admin to send notifications
    }

    public function storeToken(Request $request)
    {
        // Store FCM token implementation
    }
}