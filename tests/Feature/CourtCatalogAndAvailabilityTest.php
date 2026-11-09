<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use App\Services\CourtAvailabilityService;
use App\Services\ExpireStaleBookings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtCatalogAndAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected Venue $venue;

    protected Court $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->venue = Venue::factory()->create([
            'open_time' => '08:00:00',
            'close_time' => '23:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '23:00:00',
            'slot_lock_duration_minutes' => 10,
        ]);

        $this->court = Court::factory()->create([
            'venue_id' => $this->venue->id,
            'name' => 'Futsal Arena Pro',
            'slug' => 'futsal-arena-pro',
            'price_regular' => 150000,
            'price_prime' => 220000,
            'status' => CourtStatus::Active,
        ]);
    }

    public function test_landing_page_renders_with_database_venue_and_courts(): void
    {
        $response = $this->get(route('beranda'));

        $response->assertStatus(200);
        $response->assertSee('Futsal Arena Pro');
    }

    public function test_court_catalog_page_renders_courts_from_database(): void
    {
        $court2 = Court::factory()->create([
            'venue_id' => $this->venue->id,
            'name' => 'Badminton Court Champion',
            'slug' => 'badminton-court-champion',
            'price_regular' => 50000,
            'price_prime' => 70000,
            'status' => CourtStatus::Active,
        ]);

        $response = $this->get(route('lapangan'));

        $response->assertStatus(200);
        $response->assertSee('Futsal Arena Pro');
        $response->assertSee('Badminton Court Champion');
        $response->assertSee('Rp 150.000');
        $response->assertSee('Rp 50.000');
    }

    public function test_court_detail_page_renders_court_data_and_slots(): void
    {
        $response = $this->get(route('lapangan.detail', $this->court->slug));

        $response->assertStatus(200);
        $response->assertSee('Futsal Arena Pro');
        $response->assertSee('Mulai dari');
    }

    public function test_hourly_slots_generated_from_venue_open_to_close_time(): void
    {
        $service = app(CourtAvailabilityService::class);
        $futureDate = Carbon::now()->addDays(2)->toDateString();

        $availability = $service->getAvailability($this->court, $futureDate);

        // From 08:00 to 23:00 = 15 hourly slots
        $this->assertCount(15, $availability['slots']);

        $firstSlot = $availability['slots'][0];
        $this->assertEquals('08:00', $firstSlot['start_time']);
        $this->assertEquals('09:00', $firstSlot['end_time']);
        $this->assertFalse($firstSlot['is_prime']);
        $this->assertEquals(150000, $firstSlot['price']);
        $this->assertTrue($firstSlot['is_available']);

        $lastSlot = $availability['slots'][14];
        $this->assertEquals('22:00', $lastSlot['start_time']);
        $this->assertEquals('23:00', $lastSlot['end_time']);
        $this->assertTrue($lastSlot['is_prime']);
        $this->assertEquals(220000, $lastSlot['price']);
        $this->assertTrue($lastSlot['is_available']);
    }

    public function test_prime_slots_have_prime_pricing_and_regular_have_regular_pricing(): void
    {
        $service = app(CourtAvailabilityService::class);
        $futureDate = Carbon::now()->addDays(2)->toDateString();

        $availability = $service->getAvailability($this->court, $futureDate);

        foreach ($availability['slots'] as $slot) {
            $hour = (int) explode(':', $slot['start_time'])[0];
            if ($hour >= 17) {
                $this->assertTrue($slot['is_prime']);
                $this->assertEquals(220000, $slot['price']);
            } else {
                $this->assertFalse($slot['is_prime']);
                $this->assertEquals(150000, $slot['price']);
            }
        }
    }

    public function test_past_slots_marked_as_past(): void
    {
        // Set current time to today at 14:30
        Carbon::setTestNow(Carbon::today()->setTime(14, 30));

        $service = app(CourtAvailabilityService::class);
        $availability = $service->getAvailability($this->court, Carbon::today()->toDateString());

        foreach ($availability['slots'] as $slot) {
            $hour = (int) explode(':', $slot['start_time'])[0];
            if ($hour <= 14) {
                $this->assertEquals('past', $slot['status']);
                $this->assertFalse($slot['is_available']);
            } else {
                $this->assertEquals('available', $slot['status']);
                $this->assertTrue($slot['is_available']);
            }
        }

        Carbon::setTestNow(); // reset
    }

    public function test_maintenance_court_slots_marked_as_maintenance(): void
    {
        $maintenanceCourt = Court::factory()->create([
            'venue_id' => $this->venue->id,
            'status' => CourtStatus::Maintenance,
        ]);

        $service = app(CourtAvailabilityService::class);
        $futureDate = Carbon::now()->addDays(2)->toDateString();

        $availability = $service->getAvailability($maintenanceCourt, $futureDate);

        $this->assertFalse($availability['is_operating']);
        foreach ($availability['slots'] as $slot) {
            $this->assertEquals('maintenance', $slot['status']);
            $this->assertFalse($slot['is_available']);
        }
    }

    public function test_occupied_slots_marked_as_booked_or_blocked(): void
    {
        $futureDate = Carbon::now()->addDays(3)->toDateString();
        $user = User::factory()->create();

        // 1. Create a customer booking slot at 09:00
        $booking = Booking::factory()->create([
            'venue_id' => $this->venue->id,
            'user_id' => $user->id,
            'status' => BookingStatus::Confirmed,
        ]);

        CourtSlot::create([
            'court_id' => $this->court->id,
            'booking_id' => $booking->id,
            'booking_date' => $futureDate,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'block_type' => SlotBlockType::Booking,
            'locked_until' => null,
        ]);

        // 2. Create an operator maintenance block at 10:00
        CourtSlot::create([
            'court_id' => $this->court->id,
            'booking_id' => null,
            'booking_date' => $futureDate,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'block_type' => SlotBlockType::Maintenance,
            'note' => 'Perawatan rutin',
            'locked_until' => null,
        ]);

        $service = app(CourtAvailabilityService::class);
        $availability = $service->getAvailability($this->court, $futureDate);

        $slotsByTime = collect($availability['slots'])->keyBy('start_time');

        $this->assertEquals('booked', $slotsByTime['09:00']['status']);
        $this->assertFalse($slotsByTime['09:00']['is_available']);

        $this->assertEquals('blocked', $slotsByTime['10:00']['status']);
        $this->assertFalse($slotsByTime['10:00']['is_available']);

        $this->assertEquals('available', $slotsByTime['11:00']['status']);
        $this->assertTrue($slotsByTime['11:00']['is_available']);
    }

    public function test_expire_stale_bookings_service_expires_pending_booking_and_frees_slot(): void
    {
        $futureDate = Carbon::now()->addDays(2)->toDateString();
        $user = User::factory()->create();

        // Stale booking created with lock expired 5 minutes ago
        $expiredTime = Carbon::now()->subMinutes(5);

        $booking = Booking::factory()->create([
            'venue_id' => $this->venue->id,
            'user_id' => $user->id,
            'status' => BookingStatus::Pending,
            'expires_at' => $expiredTime,
        ]);

        BookingItem::factory()->create([
            'booking_id' => $booking->id,
            'court_id' => $this->court->id,
            'booking_date' => $futureDate,
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'price_per_hour' => 150000,
        ]);

        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'status' => PaymentStatus::Unpaid,
            'amount' => 150000,
        ]);

        CourtSlot::create([
            'court_id' => $this->court->id,
            'booking_id' => $booking->id,
            'booking_date' => $futureDate,
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'block_type' => SlotBlockType::Booking,
            'locked_until' => $expiredTime,
        ]);

        // Run ExpireStaleBookings
        $expireService = app(ExpireStaleBookings::class);
        $expiredCount = $expireService->handle();

        $this->assertEquals(1, $expiredCount);

        // Check booking and payment status
        $booking->refresh();
        $payment->refresh();
        $this->assertEquals(BookingStatus::Expired, $booking->status);
        $this->assertEquals(PaymentStatus::Expired, $payment->status);

        // Check court slot deleted
        $this->assertDatabaseMissing('court_slots', [
            'court_id' => $this->court->id,
            'booking_id' => $booking->id,
        ]);

        // Slot is now available
        $availabilityService = app(CourtAvailabilityService::class);
        $availability = $availabilityService->getAvailability($this->court, $futureDate);
        $slotsByTime = collect($availability['slots'])->keyBy('start_time');

        $this->assertEquals('available', $slotsByTime['15:00']['status']);
        $this->assertTrue($slotsByTime['15:00']['is_available']);
    }

    public function test_api_lapangan_slots_endpoint_returns_json_availability(): void
    {
        $futureDate = Carbon::now()->addDays(2)->toDateString();

        $response = $this->getJson(route('api.lapangan.slots', [
            'slug' => $this->court->slug,
            'date' => $futureDate,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'court' => ['id', 'name', 'slug', 'status', 'price_regular', 'price_prime'],
            'date',
            'is_operating',
            'slots' => [
                '*' => ['id', 'index', 'start_time', 'end_time', 'time_label', 'is_prime', 'price', 'status', 'is_available'],
            ],
        ]);
    }
}
