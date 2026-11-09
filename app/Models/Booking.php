<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'venue_id',
    'user_id',
    'booking_code',
    'customer_name',
    'customer_phone',
    'customer_email',
    'notes',
    'total_hours',
    'subtotal',
    'total_amount',
    'status',
    'expires_at',
    'checkin_token',
    'checked_in_at',
    'checked_in_by',
    'cancelled_at',
    'cancelled_reason',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'total_hours' => 'integer',
            'subtotal' => 'integer',
            'total_amount' => 'integer',
            'expires_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Get the venue where this booking was made.
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the customer who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the operator who processed the check-in.
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Get the snapshot items for this booking.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    /**
     * Get the active slots currently held by this booking.
     */
    public function slots(): HasMany
    {
        return $this->hasMany(CourtSlot::class);
    }

    /**
     * Get payments associated with this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
