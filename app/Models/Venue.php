<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'address',
    'phone',
    'email',
    'open_time',
    'close_time',
    'prime_start_time',
    'prime_end_time',
    'slot_lock_duration_minutes',
    'status',
])]
class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'slot_lock_duration_minutes' => 'integer',
        ];
    }

    /**
     * Get the courts belonging to this venue.
     */
    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    /**
     * Get the operators assigned to this venue.
     */
    public function operators(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all bookings for this venue.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
