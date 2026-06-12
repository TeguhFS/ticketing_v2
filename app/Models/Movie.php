<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tmdb_id',
        'title',
        'original_title',
        'slug',
        'synopsis',
        'poster',
        'backdrop',
        'trailer_url',
        'genres',
        'duration',
        'age_rating',
        'language',
        'director',
        'cast',
        'production_companies',
        'release_date',
        'vote_average',
        'vote_count',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'genres'               => 'array',
        'cast'                 => 'array',
        'production_companies' => 'array',
        'release_date'         => 'date',
        'vote_average'         => 'decimal:1',
        'is_featured'          => 'boolean',
    ];

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($movie) {
            if (empty($movie->slug)) {
                $movie->slug = Str::slug($movie->title . '-' . ($movie->release_date?->year ?? date('Y')));
            }
        });
    }

    // Relasi
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function upcomingShowtimes()
    {
        return $this->hasMany(Showtime::class)
            ->where('status', 'open')
            ->where('start_time', '>', now())
            ->orderBy('start_time');
    }

    public function cinemaOrders()
    {
        return $this->hasManyThrough(CinemaOrder::class, Showtime::class);
    }

    // Accessors
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration) return '-';
        $hours   = intdiv($this->duration, 60);
        $minutes = $this->duration % 60;
        return $hours > 0 ? "{$hours}j {$minutes}m" : "{$minutes}m";
    }

    public function getGenresStringAttribute(): string
    {
        return implode(', ', $this->genres ?? []);
    }

    public function getCastStringAttribute(): string
    {
        return implode(', ', array_slice($this->cast ?? [], 0, 5));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'coming_soon'  => 'Segera Tayang',
            'now_showing'  => 'Sedang Tayang',
            'ended'        => 'Sudah Berakhir',
            default        => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'coming_soon' => 'amber',
            'now_showing' => 'emerald',
            'ended'       => 'gray',
            default       => 'gray',
        };
    }

    public function getPosterUrlAttribute(): string
    {
        if (!$this->poster) {
            return asset('images/no-poster.png');
        }
        if (str_starts_with($this->poster, 'http')) {
            return $this->poster; // URL TMDb
        }
        return \Illuminate\Support\Facades\Storage::url($this->poster);
    }

    public function getBackdropUrlAttribute(): string
    {
        if (!$this->backdrop) {
            return asset('images/no-backdrop.jpg');
        }
        if (str_starts_with($this->backdrop, 'http')) {
            return $this->backdrop;
        }
        return \Illuminate\Support\Facades\Storage::url($this->backdrop);
    }

    public function getYoutubeEmbedAttribute(): ?string
    {
        if (!$this->trailer_url) return null;

        preg_match(
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
            $this->trailer_url,
            $matches
        );

        return isset($matches[1])
            ? "https://www.youtube.com/embed/{$matches[1]}"
            : null;
    }

    public function getRatingStarsAttribute(): string
    {
        if (!$this->vote_average) return '—';
        $stars = round($this->vote_average / 2, 1);
        return "★ {$stars}/5";
    }

    // Scopes
    public function scopeNowShowing($query)
    {
        return $query->where('status', 'now_showing');
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', 'coming_soon');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByGenre($query, string $genre)
    {
        return $query->whereJsonContains('genres', $genre);
    }

    public function scopeByRating($query, string $rating)
    {
        return $query->where('age_rating', $rating);
    }

    // Helpers
    public function isNowShowing(): bool
    {
        return $this->status === 'now_showing';
    }

    public function isComingSoon(): bool
    {
        return $this->status === 'coming_soon';
    }

    public function hasTrailer(): bool
    {
        return !empty($this->trailer_url);
    }
}
