<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'payment_code',
        'amount',
        'fee',
        'total_paid',
        'status',
        'proof_image',
        'notes',
        'verified_by',
        'verified_at',
        'expired_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'fee'         => 'decimal:2',
        'total_paid'  => 'decimal:2',
        'verified_at' => 'datetime',
        'expired_at'  => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }
}
