<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function conversations()
    {
        return Conversation::where(function($query) {
            $query->where('homeowner_id', $this->id)
                ->orWhere('gardener_id', $this->id)
                ->orWhere('service_provider_id', $this->id);
        });
    }

    public function hasRole($role)
    {
        // Implement your role checking logic here
        // This might vary based on your authentication setup
        return $this->role === $role;
    }

        public function gardenerBookings()
    {
        return $this->hasMany(Booking::class, 'gardener_id');
    }

    public function serviceProviderBookings()
    {
        return $this->hasMany(Booking::class, 'serviceprovider_id');
    }

    public function homeownerBookings()
    {
        return $this->hasMany(Booking::class, 'homeowner_id');
    }
    
            public function bookings()
        {
            return $this->hasMany(Booking::class, 'gardener_id');
        }
}
