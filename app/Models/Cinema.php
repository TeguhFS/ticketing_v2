<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cinema extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'address',
        'logo',
        'phone',
        'email',
        'description',
        'facilities',
        'latitude',
        'longitude',
        'maps_url',
        'is_active',
        'order',
    ];

    protected $casts = [
        'facilities' => 'array',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
        'is_active'  => 'boolean',
    ];

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($cinema) {
            if (empty($cinema->slug)) {
                $cinema->slug = Str::slug($cinema->name);
            }
        });
    }

    // Relasi
    public function studios()
    {
        return $this->hasMany(Studio::class)->orderBy('order');
    }

    public function activeStudios()
    {
        return $this->hasMany(Studio::class)
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function showtimes()
    {
        return $this->hasManyThrough(Showtime::class, Studio::class);
    }

    // Accessors
    public function getTotalStudiosAttribute(): int
    {
        return $this->studios()->count();
    }

    public function getTotalSeatsAttribute(): int
    {
        return $this->studios()->sum('total_seats');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    // Helpers
    public function hasFacility(string $facility): bool
    {
        return in_array($facility, $this->facilities ?? []);
    }
}
