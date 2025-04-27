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
        return $this->hasOne(User::class, 'id', 'homeowner_id');
    }

    // Define the relationship with the Gardener (assuming it's also a User)
    public function gardener()
    {
        return $this->hasOne(User::class, 'id', 'gardener_id')
        ->where('type', 'Gardening');
    }

    // Define the relationship with the Service Provider (assuming it's also a User)
    public function serviceProvider()
    {
        return $this->hasOne(User::class,  'id', 'serviceprovider_id')
        ->where('type', 'Landscaping');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'id');
    }
    // Define the relationship with BookingService
    public function services()
    {
        return $this->hasMany(BookingService::class, 'booking_id', 'id')
        ->select('booking_services.*','services.name','services.price')
        ->join('services', 'services.id', '=', 'booking_services.service_id');
    }
    public function rating()
{
    return $this->hasOne(Rating::class);
}
}
