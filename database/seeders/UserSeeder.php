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

        $venue = Venue::where('slug', 'lapangin-sports-center')->first();

        // 1. Super Admin (admin/dashboard.blade.php:56)
        $superAdmin = User::firstOrNew(['email' => 'admin@lapangin.id']);
        $superAdmin->forceFill([
            'name' => 'Super Admin',
            'phone' => '081100000001', // ASUMSI
            'password' => Hash::make($seedPassword),
            'role' => UserRole::SuperAdmin,
            'status' => UserStatus::Active,
            'venue_id' => null,
            'email_verified_at' => now(),
        ])->save();

        // 2. Operator (operator/dashboard.blade.php:75-76, admin/users.blade.php:85)
        $operator = User::firstOrNew(['email' => 'budi@lapangin.id']);
        $operator->forceFill([
            'name' => 'Budi Operator',
            'phone' => '089877771234',
            'password' => Hash::make($seedPassword),
            'role' => UserRole::Operator,
            'status' => UserStatus::Active,
            'venue_id' => $venue?->id,
            'email_verified_at' => now(),
        ])->save();

        // 3. Customer (booking/checkout.blade.php:97, 112, 118, admin/users.blade.php:82)
        $customer = User::firstOrNew(['email' => 'ahmad@email.com']);
        $customer->forceFill([
            'name' => 'Ahmad Fauzi',
            'phone' => '081234567890',
            'password' => Hash::make($seedPassword),
            'role' => UserRole::Customer,
            'status' => UserStatus::Active,
            'venue_id' => null,
            'email_verified_at' => now(),
        ])->save();
    }
}
