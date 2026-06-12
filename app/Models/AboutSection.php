<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'image',
        'items',
        'is_active',
        'order',
    ];

    protected $casts = [
        'items'     => 'array',
        'is_active' => 'boolean',
    ];

    public static function getSection(string $key): ?self
    {
        return Cache::rememberForever('about_section_' . $key, function () use ($key) {
            return static::where('key', $key)->where('is_active', true)->first();
        });
    }

    public static function clearCache(string $key): void
    {
        Cache::forget('about_section_' . $key);
    }

    public static function getAllActive(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->orderBy('order')->get();
    }
}
