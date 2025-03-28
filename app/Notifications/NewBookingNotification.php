<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewBooking extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Booking Request')
            ->line('You have a new booking request from ' . $this->booking->homeowner->name)
            ->action('View Booking', url('/bookings/' . $this->booking->id))
            ->line('Please respond within 24 hours');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_booking',
            'booking_id' => $this->booking->id,
            'message' => 'New booking request from ' . $this->booking->homeowner->name,
            'url' => '/provider/bookings/' . $this->booking->id,
        ];
    }
}

