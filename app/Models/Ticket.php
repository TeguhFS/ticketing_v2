<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'ticket_type_id',
        'user_id',
        'ticket_code',
        'qr_code',
        'holder_name',
        'holder_email',
        'holder_phone',
        'status',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validations()
    {
        return $this->hasMany(TicketValidation::class);
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
