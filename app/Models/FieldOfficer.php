<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldOfficer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'officer_code',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketValidations()
    {
        return $this->hasMany(TicketValidation::class, 'validated_by', 'user_id');
    }
}
