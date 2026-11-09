<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Venue::updateOrCreate(
            ['slug' => 'lapangin-sports-center'],
            [
                'name' => 'Lapangin Sports Center',
                'address' => 'Jl. Olahraga No. 1, Jakarta Selatan',
                'phone' => '0800-1234-5678',
                'email' => 'kontak@lapangin.id',
                'open_time' => '08:00:00',
                'close_time' => '23:00:00',
                'prime_start_time' => '17:00:00',
                'prime_end_time' => '23:00:00',
                'slot_lock_duration_minutes' => 10,
                'status' => 'active',
            ]
        );
    }
}
