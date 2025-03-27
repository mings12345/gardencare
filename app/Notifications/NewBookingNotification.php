<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $homeownerName;

    public function __construct($booking, $homeownerName)
    {
        $this->booking = $booking;
        $this->homeownerName = $homeownerName;
    }

    public function via($notifiable)
{
    return ['database', 'firebase'];
}

public function toFirebase($notifiable)
{
    $tokens = $notifiable->fcmTokens()->pluck('token')->toArray();
    
    return (new FirebaseService)->sendNotification(
        $tokens[0], // Or loop through all tokens
        $this->title,
        $this->body,
        [
            'type' => 'new_booking',
            'booking_id' => $this->booking->id,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ]
    );
}
}
