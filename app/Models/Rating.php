<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'booking_id',
        'homeowner_id',
        'gardener_id',
        'rating',
        'feedback'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function homeowner()
    {
        return $this->belongsTo(User::class, 'homeowner_id');
    }

    public function gardener()
    {
        return $this->belongsTo(User::class, 'gardener_id');
    }
}
