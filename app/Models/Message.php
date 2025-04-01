<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',       // Added
        'read_at',       // Added
    ];

    protected $casts = [
        'is_read' => 'boolean',  // Cast to boolean
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime', // Cast read_at as datetime
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Helper method to mark as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

        public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
        });
    }

    public function scopeUnreadFrom($query, $senderId, $receiverId)
    {
        return $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->where('is_read', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'asc'); // For chronological chat
    }
}