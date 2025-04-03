<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->message->receiver_id);
    }

    public function broadcastAs()
    {
        return 'NewMessage'; // Must match frontend
    }
    
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'is_read' => $this->message->is_read,
            'read_at' => $this->message->read_at,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'sender' => $this->message->sender->only(['id', 'name', 'profile_picture_url']),
            'receiver' => $this->message->receiver->only(['id', 'name']),
        ];
    }
}