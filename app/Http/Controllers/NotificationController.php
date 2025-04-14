<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications->count(),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|uuid|exists:notifications,id',
        ]);

        $notification = $request->user()
            ->notifications()
            ->findOrFail($request->notification_id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'unread_count' => $request->user()->unreadNotifications->count(),
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    public function storeToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $request->user()->update([
            'device_token' => $request->device_token,
        ]);

        return response()->json(['message' => 'Device token stored successfully']);
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        $user = User::find($request->user_id);
        
        $user->notify(new GenericNotification(
            $request->title,
            $request->body,
            $request->type,
            $request->data ?? []
        ));

        return response()->json(['message' => 'Notification sent successfully']);
    }
}