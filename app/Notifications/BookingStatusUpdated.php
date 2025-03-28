<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;
    public $action;

    public function __construct(Booking $booking, $action)
    {
        $this->booking = $booking;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->action === 'accepted') {
            return (new MailMessage)
                ->subject('Booking Accepted')
                ->line('Your booking has been accepted by ' . $this->booking->provider->name)
                ->line('Amount to pay: ₱' . number_format(
                    $this->booking->payment_type === 'down_payment' 
                        ? $this->booking->down_payment_amount 
                        : $this->booking->total_price, 
                    2
                ))
                ->action('Make Payment', url('/bookings/' . $this->booking->id . '/payment'))
                ->line('Please complete payment within 24 hours');
        }

        return (new MailMessage)
            ->subject('Booking Rejected')
            ->line('Your booking has been rejected by ' . $this->booking->provider->name);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'booking_' . $this->action,
            'booking_id' => $this->booking->id,
            'message' => $this->action === 'accepted'
                ? 'Booking accepted by ' . $this->booking->provider->name
                : 'Booking rejected by ' . $this->booking->provider->name,
            'url' => $this->action === 'accepted'
                ? '/bookings/' . $this->booking->id . '/payment'
                : '/bookings',
        ];
    }
}