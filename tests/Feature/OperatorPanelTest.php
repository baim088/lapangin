<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatorPanelTest extends TestCase
{
    use RefreshDatabase;

    protected Venue $venue;

    protected Venue $otherVenue;

    protected Court $court;

    protected User $operator;

    protected User $otherOperator;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->venue = Venue::create([
            'name' => 'Senayan Sport Arena',
            'slug' => 'senayan-sport-arena',
            'city' => 'Jakarta Pusat',
            'address' => 'Jl. Pintu Satu Senayan No. 1',
            'phone' => '081233334444',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 15,
        ]);

        $this->otherVenue = Venue::create([
            'name' => 'Kelapa Gading Arena',
            'slug' => 'kelapa-gading-arena',
            'city' => 'Jakarta Utara',
            'address' => 'Jl. Boulevard Barat No. 55',
            'phone' => '081255556666',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
        ]);

        $this->court = Court::create([
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Futsal 1',
            'slug' => 'lapangan-futsal-1',
            'sport_category' => SportCategory::Futsal,
            'status' => CourtStatus::Active,
            'price_regular' => 120000,
            'price_prime' => 180000,
            'surface_type' => 'Vinyl',
        ]);

        $this->operator = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
        ]);

        $this->otherOperator = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->otherVenue->id,
        ]);

        $this->customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);
    }

    public function test_operator_dashboard_renders_live_kpis_and_occupancy(): void
    {
        Carbon::setTestNow('2026-11-09 07:00:00');
        $today = Carbon::today()->toDateString();

        // Create confirmed booking for today
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $today,
            startTimes: ['10:00', '11:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Penyewa Hari Ini', 'phone' => '081234567890']
        );
        $service->confirmByOperator($booking, $this->operator);

        $response = $this->actingAs($this->operator)->get(route('operator.dashboard'));

        $response->assertOk();
        $response->assertViewHas('todayBookingsCount', 1);
        $response->assertViewHas('todayRevenue', 240000);
        $response->assertSee('Lapangan Futsal 1');
        $response->assertSee('Penyewa Hari Ini');
    }

    public function test_operator_can_confirm_pending_booking(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['14:00'],
            paymentMethod: PaymentMethod::ManualTransfer,
            customerData: ['name' => 'Confirm Test', 'phone' => '081234567890']
        );

        $response = $this->actingAs($this->operator)->post(route('operator.booking.confirm', $booking->id));

        $response->assertRedirect();
        $booking->refresh();

        $this->assertEquals(BookingStatus::Confirmed, $booking->status);
        $this->assertEquals(PaymentStatus::Paid, $booking->payment->status);
        $this->assertEquals($this->operator->id, $booking->payment->verified_by);
    }

    public function test_operator_can_reject_pending_booking_with_reason(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['16:00'],
            paymentMethod: PaymentMethod::ManualTransfer,
            customerData: ['name' => 'Reject Test', 'phone' => '081234567890']
        );

        $this->assertEquals(1, CourtSlot::where('booking_id', $booking->id)->count());

        $response = $this->actingAs($this->operator)->post(route('operator.booking.reject', $booking->id), [
            'reason' => 'Bukti pembayaran tidak sesuai nominal.',
        ]);

        $response->assertRedirect();
        $booking->refresh();

        $this->assertEquals(BookingStatus::Cancelled, $booking->status);
        $this->assertEquals(PaymentStatus::Rejected, $booking->payment->status);
        $this->assertEquals('Bukti pembayaran tidak sesuai nominal.', $booking->cancelled_reason);
        $this->assertEquals(0, CourtSlot::where('booking_id', $booking->id)->count());
    }

    public function test_operator_can_cancel_confirmed_booking_with_reason(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['18:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Cancel Confirmed', 'phone' => '081234567890']
        );
        $service->confirmByOperator($booking, $this->operator);

        $response = $this->actingAs($this->operator)->post(route('operator.booking.cancel', $booking->id), [
            'reason' => 'Renovasi mendesak akibat kebocoran atap.',
        ]);

        $response->assertRedirect();
        $booking->refresh();

        $this->assertEquals(BookingStatus::Cancelled, $booking->status);
        $this->assertEquals(PaymentStatus::RefundPending, $booking->payment->status);
        $this->assertEquals('Renovasi mendesak akibat kebocoran atap.', $booking->cancelled_reason);
        $this->assertEquals(0, CourtSlot::where('booking_id', $booking->id)->count());
    }

    public function test_operator_from_other_venue_cannot_confirm_or_cancel_booking(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['19:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Cross Venue', 'phone' => '081234567890']
        );

        // Operator from another venue gets 403
        $this->actingAs($this->otherOperator)
            ->post(route('operator.booking.confirm', $booking->id))
            ->assertForbidden();

        $this->actingAs($this->otherOperator)
            ->post(route('operator.booking.reject', $booking->id), ['reason' => 'Test'])
            ->assertForbidden();
    }

    public function test_operator_booking_list_search_filter_and_export(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['13:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'CariSaya UniqueName', 'phone' => '081299998888']
        );

        // 1. Search works
        $response = $this->actingAs($this->operator)->get(route('operator.booking', ['search' => 'UniqueName']));
        $response->assertOk();
        $response->assertSee('CariSaya UniqueName');

        // 2. Filter works
        $responseFilter = $this->actingAs($this->operator)->get(route('operator.booking', ['status' => 'pending']));
        $responseFilter->assertOk();
        $responseFilter->assertSee($booking->booking_code);

        // 3. Export CSV stream
        $responseExport = $this->actingAs($this->operator)->get(route('operator.booking.export'));
        $responseExport->assertOk();
        $responseExport->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_operator_can_block_free_slot_manually_with_note(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();

        $response = $this->actingAs($this->operator)->post(route('operator.jadwal.block'), [
            'court_id' => $this->court->id,
            'booking_date' => $bookingDate,
            'start_time' => '15:00',
            'block_type' => SlotBlockType::Maintenance->value,
            'note' => 'Perbaikan tiang gawang',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('court_slots', [
            'court_id' => $this->court->id,
            'start_time' => '15:00:00',
            'block_type' => SlotBlockType::Maintenance->value,
            'booking_id' => null,
            'note' => 'Perbaikan tiang gawang',
        ]);
    }

    public function test_operator_cannot_block_already_occupied_slot(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();

        // Customer books 11:00
        $service = app(BookingService::class);
        $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $bookingDate,
            startTimes: ['11:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Existing', 'phone' => '081234567890']
        );

        // Operator attempts to block 11:00
        $response = $this->actingAs($this->operator)->post(route('operator.jadwal.block'), [
            'court_id' => $this->court->id,
            'booking_date' => $bookingDate,
            'start_time' => '11:00',
            'block_type' => SlotBlockType::Maintenance->value,
            'note' => 'Coba blokir',
        ]);

        $response->assertSessionHasErrors('start_time');
    }

    public function test_operator_can_unblock_manual_block_slot(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();

        $slot = CourtSlot::create([
            'court_id' => $this->court->id,
            'booking_id' => null,
            'booking_date' => $bookingDate,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'block_type' => SlotBlockType::Event,
            'note' => 'Blokir tes',
            'locked_until' => null,
        ]);

        $response = $this->actingAs($this->operator)->delete(route('operator.jadwal.unblock', $slot->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('court_slots', ['id' => $slot->id]);
    }

    public function test_operator_cannot_unblock_customer_booking_via_unblock_endpoint(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['12:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Customer Slot', 'phone' => '081234567890']
        );

        $bookingSlot = CourtSlot::where('booking_id', $booking->id)->first();

        $response = $this->actingAs($this->operator)->delete(route('operator.jadwal.unblock', $bookingSlot->id));
        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('court_slots', ['id' => $bookingSlot->id]);
    }

    public function test_operator_can_manage_courts_crud(): void
    {
        // 1. Store court with auto unique slug
        $response = $this->actingAs($this->operator)->post(route('operator.lapangan.store'), [
            'name' => 'Lapangan Badminton Baru',
            'sport_category' => SportCategory::Badminton->value,
            'surface_type' => 'Kayu Parket',
            'price_regular' => 80000,
            'price_prime' => 120000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('courts', [
            'name' => 'Lapangan Badminton Baru',
            'slug' => 'lapangan-badminton-baru',
            'venue_id' => $this->venue->id,
        ]);

        $newCourt = Court::where('slug', 'lapangan-badminton-baru')->first();

        // 2. Update court
        $updateResponse = $this->actingAs($this->operator)->put(route('operator.lapangan.update', $newCourt->id), [
            'name' => 'Lapangan Badminton Baru Updated',
            'sport_category' => SportCategory::Badminton->value,
            'surface_type' => 'Karpet Vinyl Khusus',
            'price_regular' => 90000,
            'price_prime' => 130000,
        ]);

        $updateResponse->assertRedirect();
        $newCourt->refresh();
        $this->assertEquals('Lapangan Badminton Baru Updated', $newCourt->name);
        $this->assertEquals(90000, $newCourt->price_regular);

        // 3. Toggle court status (active -> maintenance)
        $this->assertEquals(CourtStatus::Active, $newCourt->status);
        $toggleResponse = $this->actingAs($this->operator)->patch(route('operator.lapangan.toggle-status', $newCourt->id));
        $toggleResponse->assertRedirect();
        $newCourt->refresh();
        $this->assertEquals(CourtStatus::Maintenance, $newCourt->status);
    }
}
