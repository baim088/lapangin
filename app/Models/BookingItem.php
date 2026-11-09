<?php

namespace App\Models;

use Database\Factories\BookingItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'court_id',
    'booking_date',
    'start_time',
    'end_time',
    'is_prime',
    'price_per_hour',
])]
class BookingItem extends Model
{
    /** @use HasFactory<BookingItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'is_prime' => 'boolean',
            'price_per_hour' => 'integer',
        ];
    }

    /**
     * Get the parent booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the court associated with this item.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }
}
