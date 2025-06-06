<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

            public function user()
        {
            return $this->belongsTo(User::class);
        }

                public function services()
        {
            return $this->hasMany(Service::class);
        }
}
