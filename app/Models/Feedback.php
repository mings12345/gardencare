<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Protect all fields from mass assignment except those explicitly allowed
    protected $guarded = []; 

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

