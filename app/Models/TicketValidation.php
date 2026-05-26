<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'validated_by',
        'ticket_code',
        'status',
        'notes',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
