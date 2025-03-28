<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'homeowner_id',
        'gardener_id',
        'service_provider_id',
        'last_message_at'
    ];

    protected $dates = ['last_message_at'];

    public function homeowner()
    {
        return $this->belongsTo(User::class, 'homeowner_id');
    }

    public function gardener()
    {
        return $this->belongsTo(User::class, 'gardener_id');
    }

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function getOtherParticipantAttribute()
    {
        $user = auth()->user();
        
        if ($this->homeowner_id == $user->id) {
            return $this->gardener ?? $this->serviceProvider;
        }
        
        return $this->homeowner;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('homeowner_id', $userId)
              ->orWhere('gardener_id', $userId)
              ->orWhere('service_provider_id', $userId);
        });
    }

    public function markAsReadForUser($userId)
    {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
