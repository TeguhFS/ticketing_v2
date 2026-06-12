<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // Auto generate slug
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function isPublished(): bool
    {
        return $this->is_active && $this->published_at?->lte(now());
    }

    // Scope
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where('published_at', '<=', now());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
