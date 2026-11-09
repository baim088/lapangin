<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_runs_in_local_environment_and_is_idempotent(): void
    {
        App::detectEnvironment(fn () => 'local');

        $this->seed(DatabaseSeeder::class);

        // Verify demo bookings were seeded
        $demoBookings = Booking::where('notes', 'like', '%[DEMO]%')->get();
        $this->assertNotEmpty($demoBookings);

        // Check variety of statuses
        $statuses = $demoBookings->pluck('status')->map(fn ($s) => $s->value)->unique()->all();
        $this->assertContains(BookingStatus::Pending->value, $statuses);
        $this->assertContains(BookingStatus::Confirmed->value, $statuses);
        $this->assertContains(BookingStatus::Cancelled->value, $statuses);

        $initialCount = $demoBookings->count();

        // Run DemoSeeder again to verify idempotency
        $this->seed(DemoSeeder::class);

        $afterCount = Booking::where('notes', 'like', '%[DEMO]%')->count();
        $this->assertEquals($initialCount, $afterCount);
    }

    public function test_demo_seeder_is_skipped_in_production_environment(): void
    {
        putenv('SEED_PASSWORD=password');
        App::detectEnvironment(fn () => 'production');

        $this->artisan('db:seed', ['--force' => true]);

        $demoCount = Booking::where('notes', 'like', '%[DEMO]%')->count();
        $this->assertEquals(0, $demoCount);

        putenv('SEED_PASSWORD');
    }
}
