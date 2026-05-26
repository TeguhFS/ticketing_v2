<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',       // admin yang membuat event
        'title',
        'slug',
        'description',
        'thumbnail',
        'banner',
        'location',
        'location_detail',
        'maps_url',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'max_attendees',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'is_featured' => 'boolean',
    ];

    // Admin yang membuat event
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function fieldOfficers()
    {
        return $this->hasMany(FieldOfficer::class);
    }

    // Helper cek status
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
