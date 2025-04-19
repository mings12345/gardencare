<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $notification;

    public function __construct($userId, array $notification)
    {
        $this->userId = $userId;
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'booking.notification';
    }

    public function broadcastWith()
    {
        return $this->notification;
    }
}