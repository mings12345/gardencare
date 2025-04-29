<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlant extends Model
{
    protected $guarded = [];

    public function plant() {
        return $this->belongsTo(Plant::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
