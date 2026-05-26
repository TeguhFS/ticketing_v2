<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quota',
        'sold',
        'max_per_order',
        'sale_start',
        'sale_end',
        'is_active',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Sisa kuota
    public function getRemainingQuotaAttribute(): int
    {
        return $this->quota - $this->sold;
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->remaining_quota > 0;
    }
}
