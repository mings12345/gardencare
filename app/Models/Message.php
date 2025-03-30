<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Specify the table associated with the model (optional if the table name is plural of the model name)
    protected $table = 'messages';

    // Specify the fillable fields
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    // Define relationships if needed
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}