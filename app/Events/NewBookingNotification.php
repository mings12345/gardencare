<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification; // Add this

class NewBookingNotification implements ShouldBroadcast // Implement ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification; // Make public for Pusher

    /**
     * Create a new event instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Format: private-gardener.1 or private-service_provider.2
        return [
            new PrivateChannel(
                'private-' . $this->notification->user_type . '.' . $this->notification->user_id
            ),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'booking.created'; // Custom event name
    }

    /**
     * Data to broadcast with the event.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'booking_id' => $this->notification->booking_id,
            'created_at' => $this->notification->created_at->toDateTimeString(),
            'is_read' => $this->notification->is_read,
            'user_type' => $this->notification->user_type,
        ];
    }
}