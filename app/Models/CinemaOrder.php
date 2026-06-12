<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CinemaOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'showtime_id',
        'order_number',
        'subtotal',
        'discount',
        'total',
        'status',
        'snap_token',
        'notes',
        'expired_at',
    ];

    protected $casts = [
        'subtotal'   => 'decimal:2',
        'discount'   => 'decimal:2',
        'total'      => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'CGV-' . strtoupper(Str::random(10));
            }
            // Cinema order expire lebih cepat — 15 menit
            if (empty($order->expired_at)) {
                $order->expired_at = now()->addMinutes(15);
            }
        });

        static::updated(function ($order) {
            // Jika order paid, hapus expired_at
            if ($order->isDirty('status') && $order->status === 'paid') {
                $order->updateQuietly(['expired_at' => null]);
            }
        });
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function orderItems()
    {
        return $this->hasMany(CinemaOrderItem::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(
            CinemaTicket::class,
            CinemaOrderItem::class,
        );
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id')
            ->where('type', 'cinema');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Pembayaran',
            'paid'      => 'Lunas',
            'cancelled' => 'Dibatalkan',
            'expired'   => 'Kadaluarsa',
            'refunded'  => 'Direfund',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'amber',
            'paid'      => 'emerald',
            'cancelled' => 'gray',
            'expired'   => 'gray',
            'refunded'  => 'blue',
            default     => 'gray',
        };
    }

    public function getTotalTicketsAttribute(): int
    {
        return $this->orderItems()->count();
    }

    public function getSeatsStringAttribute(): string
    {
        return $this->orderItems()
            ->pluck('seat_number')
            ->join(', ');
    }

    public function getRemainingMinutesAttribute(): int
    {
        if (!$this->expired_at || $this->status !== 'pending') return 0;
        return max(0, now()->diffInMinutes($this->expired_at, false));
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expired_at && now()->gt($this->expired_at);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
            ->where('expired_at', '<=', now());
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }
}
