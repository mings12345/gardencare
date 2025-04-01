<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\NewMessage;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
{
    $request->validate([
        'sender_id' => 'required|exists:users,id',
        'receiver_id' => 'required|exists:users,id',
        'message' => 'required|string',
    ]);

    $message = Message::create($request->all());
    broadcast(new NewMessage($message))->toOthers();
    
    // Optional: Mark as read if sent to self (e.g., notes)
    if ($message->sender_id === $message->receiver_id) {
        $message->update(['is_read' => true]);
    }
    
    return response()->json($message, 201);
}

public function getMessages(User $user1, User $user2)
{
    // Mark received messages as read (only unread ones)
    $this->markMessagesAsRead($user1, $user2);

    // Get messages with optimized query
    $messages = Message::query()
        ->with(['sender:id,name,profile_picture_url', 'receiver:id,name'])
        ->betweenUsers($user1->id, $user2->id)
        ->ordered()
        ->get(['id', 'sender_id', 'receiver_id', 'message', 'is_read', 'read_at', 'created_at']);

    return response()->json([
        'meta' => $this->getMessageMeta($user1, $user2),
        'messages' => $this->formatMessages($messages)
    ]);
}

// Helper method: Mark messages as read
protected function markMessagesAsRead(User $reader, User $sender): void
{
    Message::where('sender_id', $sender->id)
           ->where('receiver_id', $reader->id)
           ->where('is_read', false)
           ->update([
               'is_read' => true,
               'read_at' => now(),
               'updated_at' => now() // Explicitly update
           ]);
}

// Helper method: Get message metadata
protected function getMessageMeta(User $user1, User $user2): array
{
    return [
        'total_messages' => Message::betweenUsers($user1->id, $user2->id)->count(),
        'unread_count' => Message::unreadFrom($user2->id, $user1->id)->count(),
    ];
}

// Helper method: Format message data
protected function formatMessages($messages)
{
    return $messages->map(function ($message) {
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
    });
}
}
