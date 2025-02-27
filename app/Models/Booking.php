<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Define the relationship with the Homeowner (User Model)
    public function homeowner()
    {
        return $this->belongsTo(User::class, 'homeowner_id', 'id');
    }

    // Define the relationship with the Gardener (assuming it's also a User)
    public function gardener()
    {
        return $this->belongsTo(User::class, 'gardener_id', 'id');
    }

    // Define the relationship with the Service Provider (assuming it's also a User)
    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'serviceprovider_id', 'id');
    }

    // Define the relationship with BookingService
    public function services()
    {
        return $this->hasMany(BookingService::class, 'booking_id', 'id');
    }
}
