<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 
        'user_type',
        'booking_id',
        'title',
        'message',
        'is_read'
    ];
    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
