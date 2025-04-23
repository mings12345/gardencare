<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'payment_method',
        'transaction_date',
        'transaction_id',
        'status'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the payment that owns the transaction.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}