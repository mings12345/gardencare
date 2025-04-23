<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_type',
        'payment_method',
        'amount_paid',
        'remaining_balance',
        'payment_date',
        'payment_status',
        'transaction_id'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2'
    ];

    /**
     * Get the booking associated with the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the transactions for this payment.
     */
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}