<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingItem>
 */
class BookingItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'court_id' => Court::factory(),
            'booking_date' => now('Asia/Jakarta')->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'is_prime' => false,
            'price_per_hour' => 150000,
        ];
    }
}
