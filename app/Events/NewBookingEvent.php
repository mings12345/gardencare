<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBookingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $userType;

    public function __construct($booking, $userType)
    {
        $this->booking = $booking;
        $this->userType = $userType;
    }

    public function broadcastOn()
    {
        return new Channel('private-' . $this->userType . '-' . $this->booking->provider_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => 'You have a new booking!',
            'booking_id' => $this->booking->id,
            'homeowner_name' => $this->booking->homeowner->name,
            'date' => $this->booking->date,
            'total_price' => $this->booking->total_price
        ];
    }
}