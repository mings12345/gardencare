<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use App\Models\User; // Added
use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function storeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);
    
        $user = $request->user();
    
        // Check if the token already exists
        $existingToken = FcmToken::where('token', $request->token)->first();
    
        if (!$existingToken) {
            // Store new token (no deletion)
            $user->fcmTokens()->create(['token' => $request->token]);
        }
    
        return response()->json(['success' => true]);
    }
    

    public function sendNotification(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer',
        'notification' => 'required|array',
        'notification.title' => 'required|string',
        'notification.body' => 'required|string',
    ]);

    $user = User::find($request->user_id);

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    // Get the latest FCM token for this user
    $token = $user->fcmTokens()->latest()->first()->token ?? null;

    if (!$token) {
        return response()->json(['success' => false, 'message' => 'No FCM token found'], 400);
    }

    // Send notification
    $success = $this->notificationService->sendPushNotification($token, [
        'title' => $request->notification['title'],
        'body' => $request->notification['body'],
    ]);

    return response()->json(['success' => $success]);
}

}