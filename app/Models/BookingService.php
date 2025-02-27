<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    // Define relationship to Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
