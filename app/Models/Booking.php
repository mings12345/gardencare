<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function homeowner()
    {
        return $this->belongsTo(User::class,'id','homeowner_id');
    }
    public function Services(){
        return $this->hasMany(BookingService::class,'booking_id','id')
        ->selectRaw('booking_services.*, services.name, services.price')
        ->join('services','services.id','booking_services.service_id');
    }

}
