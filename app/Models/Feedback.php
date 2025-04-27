<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $guarded = []; 

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
    public function service_provider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }
}

