<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer',
        'user_type' => 'required|in:gardener,service_provider'
    ]);

    $notifications = Notification::where('user_id', $request->user_id)
        ->where('user_type', $request->user_type)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json(['notifications' => $notifications]);
}

public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer',
        'user_type' => 'required|in:gardener,service_provider',
        'booking_id' => 'required|integer|exists:bookings,id',
        'title' => 'required|string|max:255',
        'message' => 'required|string'
    ]);

    $notification = Notification::create($request->all());

    // Trigger Pusher event
    event(new NewBookingNotification($notification));

    return response()->json([
        'success' => true,
        'notification' => $notification
    ], 201);
}
}
