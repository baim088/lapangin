<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'role', 'status', 'venue_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'customer',
        'status' => 'active',
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
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    /**
     * Get the venue managed by this user (operator / cashier).
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get venues owned by this user (owner).
     */
    public function ownedVenues(): HasMany
    {
        return $this->hasMany(Venue::class, 'owner_id');
    }

    /**
     * Get all bookings created by this user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get payments verified by this operator.
     */
    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    /**
     * Get on-site payments handled by this cashier.
     */
    public function cashierPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'cashier_id');
    }

    /**
     * Get bookings checked-in by this operator.
     */
    public function checkedInBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'checked_in_by');
    }

    /**
     * Get bookings settled on-site by this cashier.
     */
    public function settledBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'settled_by');
    }

    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    public function isOperator(): bool
    {
        return $this->role === UserRole::Operator;
    }

    public function isOwner(): bool
    {
        return $this->role === UserRole::Owner;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }
}
