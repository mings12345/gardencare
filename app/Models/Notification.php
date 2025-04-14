<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'data', 'read'
    ];
    
    protected $casts = [
        'read' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}