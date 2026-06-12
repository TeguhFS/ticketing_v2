<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShowtimeSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'showtime_id',
        'seat_layout_id',
        'locked_by_user_id',
        'status',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    // Relasi
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seatLayout()
    {
        return $this->belongsTo(SeatLayout::class);
    }

    public function lockedByUser()
    {
        return $this->belongsTo(User::class, 'locked_by_user_id');
    }

    public function orderItem()
    {
        return $this->hasOne(CinemaOrderItem::class);
    }

    // Accessors
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available';
    }

    public function getIsLockedAttribute(): bool
    {
        return $this->status === 'locked'
            && $this->locked_until?->gt(now());
    }

    public function getIsBookedAttribute(): bool
    {
        return $this->status === 'booked';
    }

    public function getIsExpiredLockAttribute(): bool
    {
        return $this->status === 'locked'
            && $this->locked_until?->lt(now());
    }

    public function getEffectiveStatusAttribute(): string
    {
        // Jika locked tapi sudah expired, anggap available
        if ($this->status === 'locked' && $this->locked_until?->lt(now())) {
            return 'available';
        }
        return $this->status;
    }

    public function getSeatNumberAttribute(): string
    {
        return $this->seatLayout->seat_number ?? '';
    }

    public function getRemainingLockSecondsAttribute(): int
    {
        if (!$this->is_locked) return 0;
        return max(0, now()->diffInSeconds($this->locked_until, false));
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeActiveLocked($query)
    {
        return $query->where('status', 'locked')
            ->where('locked_until', '>', now());
    }

    public function scopeExpiredLocks($query)
    {
        return $query->where('status', 'locked')
            ->where('locked_until', '<=', now());
    }

    public function scopeLockedByUser($query, int $userId)
    {
        return $query->where('status', 'locked')
            ->where('locked_by_user_id', $userId)
            ->where('locked_until', '>', now());
    }

    // Helpers
    public function lock(int $userId, int $minutes = 10): bool
    {
        if (!in_array($this->effective_status, ['available'])) {
            return false;
        }

        return $this->update([
            'status'             => 'locked',
            'locked_by_user_id'  => $userId,
            'locked_until'       => now()->addMinutes($minutes),
        ]);
    }

    public function release(): bool
    {
        return $this->update([
            'status'             => 'available',
            'locked_by_user_id'  => null,
            'locked_until'       => null,
        ]);
    }

    public function book(): bool
    {
        return $this->update([
            'status'             => 'booked',
            'locked_by_user_id'  => null,
            'locked_until'       => null,
        ]);
    }

    public function isLockedByUser(int $userId): bool
    {
        return $this->status === 'locked'
            && $this->locked_by_user_id === $userId
            && $this->locked_until?->gt(now());
    }
}
