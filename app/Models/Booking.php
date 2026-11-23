<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'venue_id',
    'user_id',
    'booking_code',
    'customer_name',
    'customer_phone',
    'customer_email',
    'notes',
    'payment_type',
    'dp_percentage',
    'dp_amount',
    'paid_amount',
    'remaining_amount',
    'settlement_method',
    'settled_at',
    'settled_by',
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
            'dp_percentage' => 'integer',
            'dp_amount' => 'integer',
            'paid_amount' => 'integer',
            'remaining_amount' => 'integer',
            'expires_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'settled_at' => 'datetime',
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
     * Get the cashier/operator who processed the on-site settlement.
     */
    public function settledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'settled_by');
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

    /**
     * Get the latest payment for this booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Get primary booking date from first item.
     */
    public function getBookingDateAttribute(): ?string
    {
        return $this->items->first()?->booking_date;
    }

    /**
     * Get primary court from first item.
     */
    public function getCourtAttribute(): ?Court
    {
        return $this->items->first()?->court;
    }

    /**
     * Check if this booking uses a Down Payment scheme.
     */
    public function isDp(): bool
    {
        return $this->payment_type === 'dp';
    }

    /**
     * Check if DP has been paid and waiting for on-site settlement.
     */
    public function isDpPaid(): bool
    {
        return $this->status === BookingStatus::DpPaid;
    }

    /**
     * Check if the booking has been fully paid.
     */
    public function isFullyPaid(): bool
    {
        return in_array($this->status, [
            BookingStatus::Confirmed,
            BookingStatus::PaidInFull,
            BookingStatus::Done,
            BookingStatus::Completed,
        ], true) || ($this->remaining_amount <= 0 && $this->paid_amount > 0);
    }

    /**
     * Check whether this booking can be settled on-site by cashier.
     */
    public function canBeSettled(): bool
    {
        return $this->status === BookingStatus::DpPaid && $this->remaining_amount > 0;
    }
}
