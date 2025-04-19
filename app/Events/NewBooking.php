<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBooking  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    /**
     * Create a new event instance.
     *
     * @param  Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [new PrivateChannel('user.' . $this->booking->homeowner_id)];
        
        // Add provider channel based on booking type
        if ($this->booking->type === 'Gardening' && $this->booking->gardener_id) {
            $channels[] = new PrivateChannel('user.' . $this->booking->gardener_id);
        } elseif ($this->booking->type === 'Landscaping' && $this->booking->serviceprovider_id) {
            $channels[] = new PrivateChannel('user.' . $this->booking->serviceprovider_id);
        }
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'NewBooking';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->booking->id,
            'type' => $this->booking->type,
            'homeowner_id' => $this->booking->homeowner_id,
            'gardener_id' => $this->booking->gardener_id,
            'serviceprovider_id' => $this->booking->serviceprovider_id,
            'address' => $this->booking->address,
            'date' => $this->booking->date,
            'time' => $this->booking->time,
            'total_price' => $this->booking->total_price,
            'status' => $this->booking->status ?? 'pending',
            'created_at' => $this->booking->created_at->toIso8601String(),
        ];
    }
}