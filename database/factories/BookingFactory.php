<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = now('Asia/Jakarta')->format('Y');
        $randomCode = strtoupper(Str::random(6));

        return [
            'venue_id' => Venue::factory(),
            'user_id' => User::factory(),
            'booking_code' => "LPN-{$year}-{$randomCode}",
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'notes' => fake()->optional()->sentence(),
            'total_hours' => 1,
            'subtotal' => 150000,
            'total_amount' => 150000,
            'status' => BookingStatus::Pending,
            'expires_at' => now('Asia/Jakarta')->addMinutes(10),
            'checkin_token' => Str::random(64),
            'checked_in_at' => null,
            'checked_in_by' => null,
            'cancelled_at' => null,
            'cancelled_reason' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Confirmed,
            'expires_at' => null,
        ]);
    }
}
