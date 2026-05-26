<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'logo',
        'type',
        'fee',
        'fee_percent',
        'is_active',
    ];

    protected $casts = [
        'fee'         => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'is_active'   => 'boolean',
    ];

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
