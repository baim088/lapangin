<?php

namespace Database\Factories;

use App\Enums\SlotBlockType;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourtSlot>
 */
class CourtSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'court_id' => Court::factory(),
            'booking_id' => Booking::factory(),
            'booking_date' => now('Asia/Jakarta')->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'block_type' => SlotBlockType::Booking,
            'note' => null,
            'locked_until' => now('Asia/Jakarta')->addMinutes(10),
        ];
    }

    /**
     * Indicate that the slot is blocked for maintenance.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'block_type' => SlotBlockType::Maintenance,
            'booking_id' => null,
            'locked_until' => null,
            'note' => 'Perawatan rutin',
        ]);
    }

    /**
     * Indicate that the slot is blocked for an event.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'block_type' => SlotBlockType::Event,
            'booking_id' => null,
            'locked_until' => null,
            'note' => 'Turnamen komunitas',
        ]);
    }
}
