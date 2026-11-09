<?php

namespace App\Models;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use Database\Factories\CourtFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'venue_id',
    'name',
    'slug',
    'sport_category',
    'surface_type',
    'specs',
    'price_regular',
    'price_prime',
    'status',
])]
class Court extends Model
{
    /** @use HasFactory<CourtFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sport_category' => SportCategory::class,
            'status' => CourtStatus::class,
            'specs' => 'array',
            'price_regular' => 'integer',
            'price_prime' => 'integer',
        ];
    }

    /**
     * Get the venue that owns this court.
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the historical booking items for this court.
     */
    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    /**
     * Get the active slots/locks for this court.
     */
    public function courtSlots(): HasMany
    {
        return $this->hasMany(CourtSlot::class);
    }
}
