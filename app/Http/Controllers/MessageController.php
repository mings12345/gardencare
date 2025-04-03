<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;                                                                                
use Illuminate\Http\Request;
use App\Events\NewMessage;
class MessageController extends Controller
{
    public function getMessages($user1, $user2)
    {
    try {
        $user1 = User::findOrFail($user1);
        $user2 = User::findOrFail($user2);

        // Mark received messages as read
        Message::unreadFrom($user2->id, $user1->id)
               ->update(['is_read' => true, 'read_at' => now()]);

        $messages = Message::with(['sender:id,name,profile_picture_url', 'receiver:id,name'])
            ->betweenUsers($user1->id, $user2->id)
            ->ordered()
            ->get();

        return response()->json([
            'meta' => [
                'total_messages' => $messages->count(),
                'unread_count' => Message::unreadFrom($user2->id, $user1->id)->count(),
            ],
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at?->toDateTimeString(),
                    'created_at' => $message->created_at->toDateTimeString(),
                    'sender' => $message->sender->only(['id', 'name', 'profile_picture_url']),
                    'receiver' => $message->receiver->only(['id', 'name']),
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to fetch messages. ' . $e->getMessage()], 500);
    }
    }


    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create($validated);
        event(new NewMessage($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message->load(['sender:id,name', 'receiver:id,name'])
        ], 201);
    }
}