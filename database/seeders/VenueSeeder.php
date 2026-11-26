<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerA = User::where('email', 'owner.a@lapangin.id')->first();
        $ownerB = User::where('email', 'owner.b@lapangin.id')->first();

        // Venue A (Owner A: LapangIn Arena Sportama)
        $venueA = Venue::updateOrCreate(
            ['slug' => 'lapangin-sports-center'],
            [
                'owner_id' => $ownerA?->id,
                'name' => 'LapangIn Arena Sportama',
                'address' => 'Jl. Sportama No. 8, Kebayoran Baru, Jakarta Selatan',
                'phone' => '0811-2233-4455',
                'email' => 'arena@sportama.com',
                'open_time' => '08:00:00',
                'close_time' => '23:00:00',
                'prime_start_time' => '17:00:00',
                'prime_end_time' => '23:00:00',
                'slot_lock_duration_minutes' => 15,
                'status' => 'active',
            ]
        );

        // Venue B (Owner B: LapangIn Mega Stadium)
        $venueB = Venue::updateOrCreate(
            ['slug' => 'lapangin-mega-stadium'],
            [
                'owner_id' => $ownerB?->id,
                'name' => 'LapangIn Mega Stadium',
                'address' => 'Jl. Mega Kuningan Barat No. 12, Jakarta Selatan',
                'phone' => '0811-9988-7766',
                'email' => 'info@megastadium.id',
                'open_time' => '07:00:00',
                'close_time' => '23:00:00',
                'prime_start_time' => '17:00:00',
                'prime_end_time' => '23:00:00',
                'slot_lock_duration_minutes' => 15,
                'status' => 'active',
            ]
        );

        // Assign operators to venues
        User::where('email', 'operator.a@lapangin.id')->update(['venue_id' => $venueA?->id]);
        User::where('email', 'budi@lapangin.id')->update(['venue_id' => $venueA?->id]);
        User::where('email', 'operator.b@lapangin.id')->update(['venue_id' => $venueB?->id]);
    }
}
