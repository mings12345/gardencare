<?php

namespace App\Events;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $booking;

    /**
     * Create a new event instance.
     *
     * @param  Payment  $payment
     * @param  Booking  $booking
     * @return void
     */
    public function __construct(Payment $payment, Booking $booking)
    {
        $this->payment = $payment;
        $this->booking = $booking;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to both homeowner and service provider
        if ($this->booking->type === 'Gardening') {
            return [
                new PrivateChannel('user.' . $this->booking->homeowner_id),
                new PrivateChannel('user.' . $this->booking->gardener_id)
            ];
        } else {
            return [
                new PrivateChannel('user.' . $this->booking->homeowner_id),
                new PrivateChannel('user.' . $this->booking->serviceprovider_id)
            ];
        }
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'payment.received';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'booking_id' => $this->booking->id,
            'payment' => [
                'amount' => $this->payment->amount_paid,
                'status' => $this->payment->payment_status,
                'type' => $this->payment->payment_type,
                'date' => $this->payment->payment_date->format('Y-m-d H:i:s')
            ]
        ];
    }
}