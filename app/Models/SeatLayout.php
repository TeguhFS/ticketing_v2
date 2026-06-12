<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeatLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'seat_number',
        'row_label',
        'col_number',
        'seat_type',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'col_number' => 'integer',
    ];

    // Relasi
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function showtimeSeats()
    {
        return $this->hasMany(ShowtimeSeat::class);
    }

    public function cinemaOrderItems()
    {
        return $this->hasManyThrough(
            CinemaOrderItem::class,
            ShowtimeSeat::class,
        );
    }

    // Accessors
    public function getSeatTypeLabelAttribute(): string
    {
        return match ($this->seat_type) {
            'regular'  => 'Regular',
            'vip'      => 'VIP',
            'couple'   => 'Couple',
            'disabled' => 'Disabilitas',
            'blocked'  => 'Diblokir',
            default    => ucfirst($this->seat_type),
        };
    }

    public function getIsVipAttribute(): bool
    {
        return in_array($this->seat_type, ['vip', 'couple']);
    }

    public function getIsBookableAttribute(): bool
    {
        return $this->is_active
            && !in_array($this->seat_type, ['blocked', 'disabled']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBookable($query)
    {
        return $query->where('is_active', true)
            ->whereNotIn('seat_type', ['blocked']);
    }

    public function scopeByRow($query, string $row)
    {
        return $query->where('row_label', $row);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('seat_type', $type);
    }
}
