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

class BookingStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $oldStatus;

    /**
     * Create a new event instance.
     *
     * @param  Booking  $booking
     * @param  string   $oldStatus
     * @return void
     */
    public function __construct(Booking $booking, $oldStatus)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        
        // Broadcast to the homeowner
        $channels[] = new PrivateChannel('user.' . $this->booking->homeowner_id);
        
        // Broadcast to the service provider based on booking type
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
        return 'BookingUpdated';
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->booking->status,
            'updated_at' => $this->booking->updated_at->toIso8601String(),
            'booking' => [
                'id' => $this->booking->id,
                'type' => $this->booking->type,
                'homeowner_id' => $this->booking->homeowner_id,
                'gardener_id' => $this->booking->gardener_id,
                'serviceprovider_id' => $this->booking->serviceprovider_id,
                'address' => $this->booking->address,
                'date' => $this->booking->date,
                'time' => $this->booking->time,
                'total_price' => $this->booking->total_price,
                'status' => $this->booking->status,
            ]
        ];
    }
}