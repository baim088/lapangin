<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seedPassword = env('SEED_PASSWORD');
        if (empty($seedPassword)) {
            if (! app()->environment(['local', 'testing'])) {
                throw new RuntimeException('SEED_PASSWORD environment variable must be set in non-local environments.');
            }
            $seedPassword = 'password';
        }
        $hashedPassword = Hash::make($seedPassword);

        // 1. Super Admin (Global Platform Administrator)
        User::updateOrCreate(
            ['email' => 'admin@lapangin.id'],
            [
                'name' => 'Super Administrator Platform',
                'phone' => '081100000001',
                'password' => $hashedPassword,
                'role' => UserRole::SuperAdmin,
                'status' => UserStatus::Active,
                'venue_id' => null,
                'email_verified_at' => now(),
            ]
        );

        // 2. Owner A (Pemilik LapangIn Arena Sportama)
        $ownerA = User::updateOrCreate(
            ['email' => 'owner.a@lapangin.id'],
            [
                'name' => 'Hendra Wijaya (Owner Arena Sportama)',
                'phone' => '081122334401',
                'password' => $hashedPassword,
                'role' => UserRole::Owner,
                'status' => UserStatus::Active,
                'venue_id' => null,
                'email_verified_at' => now(),
            ]
        );

        // 3. Owner B (Pemilik LapangIn Mega Stadium)
        $ownerB = User::updateOrCreate(
            ['email' => 'owner.b@lapangin.id'],
            [
                'name' => 'Bambang Soediro (Owner Mega Stadium)',
                'phone' => '081122334402',
                'password' => $hashedPassword,
                'role' => UserRole::Owner,
                'status' => UserStatus::Active,
                'venue_id' => null,
                'email_verified_at' => now(),
            ]
        );

        // Link Venues to Owners if venues already seeded or create them
        $venueA = Venue::whereIn('slug', ['lapangin-sports-center', 'lapangin-arena-sportama'])->first();
        if ($venueA) {
            $venueA->update(['owner_id' => $ownerA->id]);
        }

        $venueB = Venue::where('slug', 'lapangin-mega-stadium')->first();
        if ($venueB) {
            $venueB->update(['owner_id' => $ownerB->id]);
        }

        // 4. Operator Venue A
        User::where('email', 'kasir.a@lapangin.id')->delete();
        User::updateOrCreate(
            ['email' => 'operator.a@lapangin.id'],
            [
                'name' => 'Rian Operator (Arena Sportama)',
                'phone' => '089877770001',
                'password' => $hashedPassword,
                'role' => UserRole::Operator,
                'status' => UserStatus::Active,
                'venue_id' => $venueA?->id,
                'email_verified_at' => now(),
            ]
        );

        // Also update legacy operator budi to venue A
        User::updateOrCreate(
            ['email' => 'budi@lapangin.id'],
            [
                'name' => 'Budi Operator',
                'phone' => '089877771234',
                'password' => $hashedPassword,
                'role' => UserRole::Operator,
                'status' => UserStatus::Active,
                'venue_id' => $venueA?->id,
                'email_verified_at' => now(),
            ]
        );

        // 5. Operator Venue B
        User::where('email', 'kasir.b@lapangin.id')->delete();
        User::updateOrCreate(
            ['email' => 'operator.b@lapangin.id'],
            [
                'name' => 'Siti Operator (Mega Stadium)',
                'phone' => '089877770002',
                'password' => $hashedPassword,
                'role' => UserRole::Operator,
                'status' => UserStatus::Active,
                'venue_id' => $venueB?->id,
                'email_verified_at' => now(),
            ]
        );

        // 6. 5 Customer Accounts
        $customers = [
            ['email' => 'customer1@email.com', 'name' => 'Ahmad Fauzi', 'phone' => '081234567891'],
            ['email' => 'customer2@email.com', 'name' => 'Bambang Pamungkas', 'phone' => '081234567892'],
            ['email' => 'customer3@email.com', 'name' => 'Citra Lestari', 'phone' => '081234567893'],
            ['email' => 'customer4@email.com', 'name' => 'Dedi Pratama', 'phone' => '081234567894'],
            ['email' => 'customer5@email.com', 'name' => 'Eko Kurniawan', 'phone' => '081234567895'],
            ['email' => 'ahmad@email.com', 'name' => 'Ahmad Fauzi', 'phone' => '081234567890'],
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'phone' => $c['phone'],
                    'password' => $hashedPassword,
                    'role' => UserRole::Customer,
                    'status' => UserStatus::Active,
                    'venue_id' => null,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
