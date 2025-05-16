<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Define relationships if needed
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}