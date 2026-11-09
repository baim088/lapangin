<?php

namespace Database\Seeders;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use App\Models\Court;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venue = Venue::where('slug', 'lapangin-sports-center')->first();
        if (! $venue) {
            return;
        }

        // Daftar 7 lapangan fisik berdasarkan operator/lapangan.blade.php:69-75 dan operator/jadwal.blade.php:108
        // Harga diambil dari katalog landing page (welcome.blade.php:1116-1141) sebagai PLACEHOLDER
        $courtsData = [
            [
                'name' => 'Futsal Arena A',
                'slug' => 'futsal-arena-a',
                'sport_category' => SportCategory::Futsal,
                'surface_type' => 'Karpet Vinyl & Monofilament 5cm', // lapangan.blade.php:121,131
                'specs' => [
                    'Ukuran 25x15m', // lapangan.blade.php:109
                    'LED 800 Lux Floodlight', // lapangan.blade.php:135
                    'Kapasitas 10-14 Pemain', // lapangan.blade.php:118
                    'Indoor A/C', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 150000, // PLACEHOLDER (welcome.blade.php:1119 vs operator/lapangan.blade.php:24)
                'price_prime' => 220000,   // PLACEHOLDER (welcome.blade.php:1120 vs operator/lapangan.blade.php:25)
                'status' => CourtStatus::Active,
            ],
            [
                'name' => 'Futsal Arena B',
                'slug' => 'futsal-arena-b',
                'sport_category' => SportCategory::Futsal,
                'surface_type' => 'Karpet Vinyl & Monofilament 5cm',
                'specs' => [
                    'Ukuran 25x15m', // lapangan.blade.php:109
                    'LED 800 Lux Floodlight', // lapangan.blade.php:135
                    'Kapasitas 10-14 Pemain', // lapangan.blade.php:118
                    'Indoor A/C', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 150000, // PLACEHOLDER
                'price_prime' => 220000,   // PLACEHOLDER
                'status' => CourtStatus::Active,
            ],
            [
                'name' => 'Badminton Court 1',
                'slug' => 'badminton-court-1',
                'sport_category' => SportCategory::Badminton,
                'surface_type' => 'Karakal BWF Vinyl Mat 4.5mm', // lapangan.blade.php:196,206
                'specs' => [
                    'Ukuran 13.4x6.1m', // lapangan.blade.php:184
                    'Standar BWF Karpet 4.5mm', // lapangan.blade.php:206
                    'Plafon 9 Meter Bebas Halangan', // lapangan.blade.php:210
                    'LED Indirect Anti-Silau', // lapangan.blade.php:214
                    'Kursi Wasit', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 45000,  // PLACEHOLDER (welcome.blade.php:1125 vs operator/lapangan.blade.php:24)
                'price_prime' => 65000,    // PLACEHOLDER (welcome.blade.php:1126 vs operator/lapangan.blade.php:25)
                'status' => CourtStatus::Active,
            ],
            [
                'name' => 'Badminton Court 2',
                'slug' => 'badminton-court-2',
                'sport_category' => SportCategory::Badminton,
                'surface_type' => 'Karakal BWF Vinyl Mat 4.5mm',
                'specs' => [
                    'Ukuran 13.4x6.1m', // lapangan.blade.php:184
                    'Standar BWF Karpet 4.5mm', // lapangan.blade.php:206
                    'Plafon 9 Meter Bebas Halangan', // lapangan.blade.php:210
                    'LED Indirect Anti-Silau', // lapangan.blade.php:214
                    'Kursi Wasit', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 45000,  // PLACEHOLDER
                'price_prime' => 65000,    // PLACEHOLDER
                'status' => CourtStatus::Active,
            ],
            [
                'name' => 'Badminton Court 3',
                'slug' => 'badminton-court-3',
                'sport_category' => SportCategory::Badminton,
                'surface_type' => 'Karakal BWF Vinyl Mat 4.5mm',
                'specs' => [
                    'Ukuran 13.4x6.1m', // lapangan.blade.php:184
                    'Standar BWF Karpet 4.5mm', // lapangan.blade.php:206
                    'Plafon 9 Meter Bebas Halangan', // lapangan.blade.php:210
                    'LED Indirect Anti-Silau', // lapangan.blade.php:214
                ],
                'price_regular' => 45000,  // PLACEHOLDER
                'price_prime' => 65000,    // PLACEHOLDER
                'status' => CourtStatus::Maintenance, // operator/lapangan.blade.php:73
            ],
            [
                'name' => 'Mini Soccer 7v7',
                'slug' => 'mini-soccer-7v7',
                'sport_category' => SportCategory::MiniSoccer,
                'surface_type' => 'Rumput Sintetis Monofilament Silica Infill 50mm', // lapangan.blade.php:281
                'specs' => [
                    'Ukuran 50x30m', // lapangan.blade.php:259
                    'Rumput Silica Infill 50mm', // lapangan.blade.php:281
                    '4 Tiang Tower LED 800 Lux', // lapangan.blade.php:285
                    'Gawang Standar & Jaring', // lapangan.blade.php:289
                    'Bench Pemain', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 450000, // PLACEHOLDER (welcome.blade.php:1131 vs operator/lapangan.blade.php:24)
                'price_prime' => 650000,   // PLACEHOLDER (welcome.blade.php:1132 vs operator/lapangan.blade.php:25)
                'status' => CourtStatus::Active,
            ],
            [
                'name' => 'Padel Court Glass',
                'slug' => 'padel-court-glass',
                'sport_category' => SportCategory::Padel,
                'surface_type' => 'Blue Textured Padel Turf', // lapangan.blade.php:360
                'specs' => [
                    'Ukuran 20x10m', // lapangan.blade.php:334
                    'Tempered Glass 12mm Panoramic', // lapangan.blade.php:349,356
                    'Blue Textured Padel Turf', // lapangan.blade.php:360
                    'Tersedia Raket & Bola Match', // lapangan.blade.php:364
                    'LED Ring Lighting', // KARANGAN/PLACEHOLDER, bukan dari frontend
                ],
                'price_regular' => 250000, // PLACEHOLDER (welcome.blade.php:1137 vs operator/lapangan.blade.php:24)
                'price_prime' => 330000,   // PLACEHOLDER (welcome.blade.php:1138 vs operator/lapangan.blade.php:25)
                'status' => CourtStatus::Active,
            ],
        ];

        foreach ($courtsData as $data) {
            Court::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['venue_id' => $venue->id])
            );
        }
    }
}
