<?php

namespace Database\Factories;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use App\Models\Court;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Court>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sport = fake()->randomElement(SportCategory::cases());
        $name = $sport->label().' Court '.fake()->unique()->numberBetween(1, 99);

        return [
            'venue_id' => Venue::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'sport_category' => $sport,
            'surface_type' => 'Karpet Vinyl',
            'specs' => ['LED 800 Lux', 'Indoor A/C'],
            'price_regular' => 150000,
            'price_prime' => 220000,
            'status' => CourtStatus::Active,
        ];
    }
}
