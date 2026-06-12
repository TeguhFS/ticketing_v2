<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cinema_id',
        'name',
        'type',
        'rows',
        'cols',
        'total_seats',
        'facilities',
        'is_active',
        'order',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active'  => 'boolean',
    ];

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        // Auto hitung total_seats dari seat_layouts
        static::updated(function ($studio) {
            $count = $studio->seatLayouts()
                ->where('seat_type', '!=', 'blocked')
                ->where('is_active', true)
                ->count();
            $studio->updateQuietly(['total_seats' => $count]);
        });
    }

    // Relasi
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function seatLayouts()
    {
        return $this->hasMany(SeatLayout::class)
            ->orderBy('row_label')
            ->orderBy('col_number');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function activeShowtimes()
    {
        return $this->hasMany(Showtime::class)
            ->where('status', 'open')
            ->where('start_time', '>', now());
    }

    // Accessors
    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'regular'  => 'Regular',
            '3d'       => '3D',
            'imax'     => 'IMAX',
            '4dx'      => '4DX',
            'vip'      => 'VIP',
            'premiere' => 'Premiere',
            default    => strtoupper($this->type),
        };
    }

    public function getRowLabelsAttribute(): array
    {
        $labels = [];
        for ($i = 0; $i < $this->rows; $i++) {
            $labels[] = chr(65 + $i); // A, B, C, ...
        }
        return $labels;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function syncTotalSeats(): void
    {
        $count = $this->seatLayouts()
            ->where('seat_type', '!=', 'blocked')
            ->where('is_active', true)
            ->count();
        $this->updateQuietly(['total_seats' => $count]);
    }
}
