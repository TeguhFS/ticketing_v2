<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CinemaOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cinema_order_id',
        'showtime_seat_id',
        'ticket_type',
        'price',
        'seat_number',
        'seat_type',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relasi
    public function cinemaOrder()
    {
        return $this->belongsTo(CinemaOrder::class);
    }

    public function showtimeSeat()
    {
        return $this->belongsTo(ShowtimeSeat::class);
    }

    public function ticket()
    {
        return $this->hasOne(CinemaTicket::class);
    }

    // Accessors
    public function getTicketTypeLabelAttribute(): string
    {
        return match ($this->ticket_type) {
            'regular' => 'Regular',
            'student' => 'Pelajar/Mahasiswa',
            'senior'  => 'Lansia',
            'vip'     => 'VIP',
            default   => ucfirst($this->ticket_type),
        };
    }

    public function getSeatTypeLabelAttribute(): string
    {
        return match ($this->seat_type) {
            'regular'  => 'Regular',
            'vip'      => 'VIP',
            'couple'   => 'Couple',
            'disabled' => 'Disabilitas',
            default    => ucfirst($this->seat_type),
        };
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Scopes
    public function scopeByTicketType($query, string $type)
    {
        return $query->where('ticket_type', $type);
    }
}
