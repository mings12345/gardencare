<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Booking;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $messages = Message::where('booking_id', $booking->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }

    public function store(Request $request, Booking $booking)
{
    $this->authorize('view', $booking);
    
    $request->validate([
        'message' => 'required|string',
    ]);
    
    $message = Message::create([
        'booking_id' => $booking->id,
        'sender_id' => auth()->id(),
        'receiver_id' => auth()->id() == $booking->user_id 
            ? $booking->gardener_id 
            : $booking->user_id,
        'message' => $request->message,
    ]);
    
    // Broadcast using Pusher
    broadcast(new NewMessage($message))->toOthers();
    
    return response()->json($message->load('sender'), 201);
}

    public function markAsRead(Message $message)
    {
        $this->authorize('update', $message);
        
        $message->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function conversations()
    {
        $user = auth()->user();
        
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['booking', 'sender', 'receiver'])
            ->get()
            ->groupBy('booking_id');
            
        return response()->json($conversations);
    }
}
