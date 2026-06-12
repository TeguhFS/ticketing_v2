<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'gender',
        'birth_date',
        'id_card_number',
        'id_card_image',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date'        => 'date',
            'is_active'         => 'boolean',
            'password'          => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isFieldOfficer(): bool
    {
        return $this->role === 'field_officer';
    }

    // Relasi
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function fieldOfficer()
    {
        return $this->hasOne(FieldOfficer::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function ticketValidations()
    {
        return $this->hasMany(TicketValidation::class, 'validated_by');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function refund()
    {
        return $this->hasMany(Refund::class);
    }

    // Relasi Bioskop

    public function cinemaOrders()
    {
        return $this->hasMany(CinemaOrder::class);
    }

    public function cinemaTickets()
    {
        return $this->hasMany(CinemaTicket::class);
    }

    public function lockedSeats()
    {
        return $this->hasMany(ShowtimeSeat::class, 'locked_by_user_id')
            ->where('status', 'locked')
            ->where('locked_until', '>', now());
    }
}
