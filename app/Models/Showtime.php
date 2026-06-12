<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'studio_id',
        'start_time',
        'end_time',
        'price_regular',
        'price_student',
        'price_senior',
        'price_vip',
        'language',
        'format',
        'status',
        'available_seats',
        'booked_seats',
        'notes',
    ];

    protected $casts = [
        'start_time'     => 'datetime',
        'end_time'       => 'datetime',
        'price_regular'  => 'decimal:2',
        'price_student'  => 'decimal:2',
        'price_senior'   => 'decimal:2',
        'price_vip'      => 'decimal:2',
    ];

    // Relasi
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function cinema()
    {
        return $this->hasOneThrough(
            Cinema::class,
            Studio::class,
            'id',
            'id',
            'studio_id',
            'cinema_id'
        );
    }

    public function showtimeSeats()
    {
        return $this->hasMany(ShowtimeSeat::class);
    }

    public function availableSeats()
    {
        return $this->hasMany(ShowtimeSeat::class)
            ->where('status', 'available');
    }

    public function lockedSeats()
    {
        return $this->hasMany(ShowtimeSeat::class)
            ->where('status', 'locked')
            ->where('locked_until', '>', now());
    }

    public function bookedSeats()
    {
        return $this->hasMany(ShowtimeSeat::class)
            ->where('status', 'booked');
    }

    public function cinemaOrders()
    {
        return $this->hasMany(CinemaOrder::class);
    }

    // Accessors
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'Tersedia',
            'full'      => 'Penuh',
            'cancelled' => 'Dibatalkan',
            'ended'     => 'Selesai',
            default     => ucfirst($this->status),
        };
    }

    public function getPriceForTypeAttribute(): array
    {
        return [
            'regular' => $this->price_regular,
            'student' => $this->price_student,
            'senior'  => $this->price_senior,
            'vip'     => $this->price_vip,
        ];
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_seats <= 0 || $this->status === 'full';
    }

    public function getOccupancyPercentAttribute(): float
    {
        $total = $this->studio->total_seats ?? 0;
        if ($total === 0) return 0;
        return round(($this->booked_seats / $total) * 100, 1);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where('start_time', '>', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->orderBy('start_time');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    public function scopeByDate($query, string $date)
    {
        return $query->whereDate('start_time', $date);
    }

    public function scopeByFormat($query, string $format)
    {
        return $query->where('format', $format);
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function scopeWithAvailability($query)
    {
        return $query->where('status', 'open')
            ->where('available_seats', '>', 0);
    }

    // Helpers
    public function getPriceFor(string $ticketType): float
    {
        return match ($ticketType) {
            'student' => (float) ($this->price_student ?: $this->price_regular),
            'senior'  => (float) ($this->price_senior  ?: $this->price_regular),
            'vip'     => (float) ($this->price_vip     ?: $this->price_regular),
            default   => (float) $this->price_regular,
        };
    }

    public function isBookable(): bool
    {
        return $this->status === 'open'
            && $this->start_time->gt(now())
            && $this->available_seats > 0;
    }

    public function syncAvailableSeats(): void
    {
        $available = $this->showtimeSeats()
            ->where('status', 'available')
            ->count();
        $booked = $this->showtimeSeats()
            ->where('status', 'booked')
            ->count();
        $this->updateQuietly([
            'available_seats' => $available,
            'booked_seats'    => $booked,
            'status'          => $available === 0 ? 'full' : 'open',
        ]);
    }
}
