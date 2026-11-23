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
        $venueA = Venue::whereIn('slug', ['lapangin-sports-center', 'lapangin-arena-sportama'])->first();
        $venueB = Venue::where('slug', 'lapangin-mega-stadium')->first();

        // === LAPANGAN MILIK OWNER A (LapangIn Arena Sportama: 2 Futsal & 1 Badminton) ===
        if ($venueA) {
            $courtsVenueA = [
                [
                    'name' => 'Futsal Arena A',
                    'slug' => 'futsal-arena-a',
                    'sport_category' => SportCategory::Futsal,
                    'surface_type' => 'Karpet Vinyl & Monofilament 5cm',
                    'specs' => [
                        'Ukuran 25x15m',
                        'LED 800 Lux Floodlight',
                        'Kapasitas 10-14 Pemain',
                        'Indoor A/C Standar FIFA',
                    ],
                    'price_regular' => 150000,
                    'price_prime' => 220000,
                    'status' => CourtStatus::Active,
                ],
                [
                    'name' => 'Futsal Arena B',
                    'slug' => 'futsal-arena-b',
                    'sport_category' => SportCategory::Futsal,
                    'surface_type' => 'Interlocking Polypropylene Tiles',
                    'specs' => [
                        'Ukuran 25x15m',
                        'LED 800 Lux Floodlight',
                        'Kapasitas 10-14 Pemain',
                        'Ventilasi Udara Alami & Tribun',
                    ],
                    'price_regular' => 150000,
                    'price_prime' => 220000,
                    'status' => CourtStatus::Active,
                ],
                [
                    'name' => 'Badminton Court 1',
                    'slug' => 'badminton-court-1',
                    'sport_category' => SportCategory::Badminton,
                    'surface_type' => 'Karakal BWF Vinyl Mat 4.5mm',
                    'specs' => [
                        'Ukuran 13.4x6.1m',
                        'Standar BWF Karpet 4.5mm',
                        'Plafon 9 Meter Bebas Halangan',
                        'LED Indirect Anti-Silau',
                    ],
                    'price_regular' => 65000,
                    'price_prime' => 95000,
                    'status' => CourtStatus::Active,
                ],
            ];

            foreach ($courtsVenueA as $data) {
                Court::updateOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['venue_id' => $venueA->id])
                );
            }
        }

        // === LAPANGAN MILIK OWNER B (LapangIn Mega Stadium: 1 Mini Soccer & 1 Basket) ===
        if ($venueB) {
            $courtsVenueB = [
                [
                    'name' => 'Mini Soccer Grand Field',
                    'slug' => 'mini-soccer-grand-field',
                    'sport_category' => SportCategory::MiniSoccer,
                    'surface_type' => 'Rumput Sintetis Monofilament Silica Infill 50mm',
                    'specs' => [
                        'Ukuran 50x30m (7 vs 7)',
                        'Rumput Standar FIFA Silica Infill',
                        '4 Tiang Tower LED 1000 Lux',
                        'Gawang Standar & Jaring Tebal',
                        'Loker Pemain & Bench Eksekutif',
                    ],
                    'price_regular' => 450000,
                    'price_prime' => 650000,
                    'status' => CourtStatus::Active,
                ],
                [
                    'name' => 'Basketball Indoor Pro',
                    'slug' => 'basketball-indoor-pro',
                    'sport_category' => SportCategory::Basketball,
                    'surface_type' => 'Kayu Maple Wood Parquet FIBA Approved',
                    'specs' => [
                        'Ukuran 28x15m (Full Court)',
                        'Ring Hidrolik Perbasi Standard',
                        'Digital Shot Clock 24s',
                        'Full AC Stadium & Sound System',
                    ],
                    'price_regular' => 250000,
                    'price_prime' => 350000,
                    'status' => CourtStatus::Active,
                ],
            ];

            foreach ($courtsVenueB as $data) {
                Court::updateOrCreate(
                    ['slug' => $data['slug']],
                    array_merge($data, ['venue_id' => $venueB->id])
                );
            }
        }
    }
}
