<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookingNotification extends Notification
{
    use Queueable;

    private $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Save to database and broadcast via WebSockets
    }

    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'type' => $this->booking->type,
            'address' => $this->booking->address,
            'date' => $this->booking->date,
            'time' => $this->booking->time,
            'total_price' => $this->booking->total_price,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new DatabaseMessage($this->toDatabase($notifiable));
    }
}