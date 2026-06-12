<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CinemaTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'cinema_order_item_id',
        'user_id',
        'ticket_code',
        'qr_code',
        'movie_title',
        'cinema_name',
        'studio_name',
        'seat_number',
        'seat_type',
        'ticket_type',
        'price',
        'show_time',
        'format',
        'language',
        'holder_name',
        'holder_email',
        'status',
        'used_at',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'show_time' => 'datetime',
        'used_at'   => 'datetime',
    ];

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_code)) {
                $ticket->ticket_code = 'CIN-' . strtoupper(Str::random(4))
                    . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relasi
    public function orderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CinemaOrderItem::class, 'cinema_order_item_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'Aktif',
            'used'      => 'Sudah Digunakan',
            'cancelled' => 'Dibatalkan',
            'expired'   => 'Kadaluarsa',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'emerald',
            'used'      => 'blue',
            'cancelled' => 'red',
            'expired'   => 'gray',
            default     => 'gray',
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return match ($this->format) {
            '2d'      => '2D',
            '3d'      => '3D',
            'imax'    => 'IMAX',
            '4dx'     => '4DX',
            'imax_3d' => 'IMAX 3D',
            'dolby'   => 'Dolby Cinema',
            default   => strtoupper($this->format),
        };
    }

    public function getLanguageLabelAttribute(): string
    {
        return match ($this->language) {
            'dub'      => 'Dub',
            'sub'      => 'Sub',
            'original' => 'Original',
            default    => ucfirst($this->language),
        };
    }

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

    public function getQrCodeUrlAttribute(): ?string
    {
        if (!$this->qr_code) return null;
        return \Illuminate\Support\Facades\Storage::url($this->qr_code);
    }

    public function getShowTimeLabelAttribute(): string
    {
        return $this->show_time?->translatedFormat('l, d M Y — H:i') . ' WIB' ?? '-';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'active')
            ->where('show_time', '>', now())
            ->orderBy('show_time');
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function isUsed(): bool
    {
        return $this->status === 'used';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function canBeUsed(): bool
    {
        return $this->status === 'active'
            && $this->show_time?->isToday();
    }

    public function markAsUsed(): bool
    {
        return $this->update([
            'status'  => 'used',
            'used_at' => now(),
        ]);
    }
}
