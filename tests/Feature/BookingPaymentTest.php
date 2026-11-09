<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\ExpireStaleBookings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected Venue $venue;

    protected Court $court;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->venue = Venue::create([
            'name' => 'Arena Sport Center',
            'slug' => 'arena-sport-center',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. Kebayoran Lama No. 10',
            'phone' => '081234567890',
            'open_time' => '08:00:00',
            'close_time' => '23:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 15,
        ]);

        $this->court = Court::create([
            'venue_id' => $this->venue->id,
            'name' => 'Futsal Vinyl 1',
            'slug' => 'futsal-vinyl-1',
            'sport_category' => SportCategory::Futsal,
            'status' => CourtStatus::Active,
            'price_regular' => 100000,
            'price_prime' => 150000,
            'surface_type' => 'Vinyl Interlock',
        ]);

        $this->customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);
    }

    protected function createBooking(array $attributes = []): Booking
    {
        return Booking::create(array_merge([
            'booking_code' => 'LPN-2026-'.rand(1000, 9999),
            'user_id' => $this->customer->id,
            'venue_id' => $this->venue->id,
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'customer_email' => 'customer@example.com',
            'total_hours' => 1,
            'subtotal' => 100000,
            'total_amount' => 100000,
            'status' => BookingStatus::Pending,
            'checkin_token' => bin2hex(random_bytes(32)),
        ], $attributes));
    }

    public function test_user_can_create_pending_booking_with_regular_and_prime_snapshots(): void
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $startTimes = ['14:00', '18:00']; // 14:00 is regular (100k), 18:00 is prime (150k)

        $response = $this->actingAs($this->customer)->post(route('booking.checkout.store', $this->court->slug), [
            'booking_date' => $bookingDate,
            'slots' => $startTimes,
            'payment_method' => PaymentMethod::ManualTransfer->value,
            'customer_name' => 'John Doe',
            'customer_phone' => '081234567890',
            'customer_email' => 'john@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->customer->id,
            'status' => BookingStatus::Pending->value,
            'total_amount' => 250000,
        ]);

        $booking = Booking::with('items')->latest()->first();
        $this->assertCount(2, $booking->items);

        $regularItem = $booking->items->firstWhere('start_time', '14:00:00');
        $primeItem = $booking->items->firstWhere('start_time', '18:00:00');

        $this->assertEquals(100000, $regularItem->price_per_hour);
        $this->assertEquals(150000, $primeItem->price_per_hour);

        $slots = CourtSlot::where('booking_id', $booking->id)->get();
        $this->assertCount(2, $slots);
        $this->assertNotNull($slots->first()->locked_until);
    }

    public function test_double_booking_prevention_second_user_is_rejected(): void
    {
        $otherCustomer = User::factory()->create(['role' => UserRole::Customer]);
        $bookingDate = Carbon::tomorrow()->toDateString();
        $startTimes = ['10:00'];

        // First user books successfully
        $this->actingAs($this->customer)->post(route('booking.checkout.store', $this->court->slug), [
            'booking_date' => $bookingDate,
            'slots' => $startTimes,
            'payment_method' => PaymentMethod::Qris->value,
            'customer_name' => 'Customer Satu',
            'customer_phone' => '081234567890',
            'customer_email' => 'one@example.com',
        ])->assertSessionHasNoErrors();

        $this->assertEquals(1, CourtSlot::count());

        // Second user tries to book the same slot
        $response = $this->actingAs($otherCustomer)->post(route('booking.checkout.store', $this->court->slug), [
            'booking_date' => $bookingDate,
            'slots' => $startTimes,
            'payment_method' => PaymentMethod::ManualTransfer->value,
            'customer_name' => 'Customer Dua',
            'customer_phone' => '081234567891',
            'customer_email' => 'two@example.com',
        ]);

        $response->assertSessionHasErrors('slots');
        $this->assertEquals(1, CourtSlot::count());
    }

    public function test_expired_stale_booking_frees_up_slot_and_allows_rebooking(): void
    {
        $service = app(BookingService::class);
        $bookingDate = Carbon::tomorrow()->toDateString();

        $staleBooking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $bookingDate,
            startTimes: ['11:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: [
                'name' => 'Stale User',
                'phone' => '081234567890',
            ]
        );

        // Force expired status
        $staleBooking->update([
            'expires_at' => Carbon::now()->subMinutes(10),
        ]);
        CourtSlot::where('booking_id', $staleBooking->id)->update([
            'locked_until' => Carbon::now()->subMinutes(10),
        ]);

        // Run expiration cleaner
        $cleanedCount = app(ExpireStaleBookings::class)->execute($this->court->id, $bookingDate);
        $this->assertEquals(1, $cleanedCount);

        $staleBooking->refresh();
        $this->assertEquals(BookingStatus::Expired, $staleBooking->status);
        $this->assertEquals(PaymentStatus::Expired, $staleBooking->payment->status);
        $this->assertDatabaseMissing('court_slots', ['booking_id' => $staleBooking->id]);

        // Another customer can now book this exact slot
        $newCustomer = User::factory()->create(['role' => UserRole::Customer]);
        $this->actingAs($newCustomer)->post(route('booking.checkout.store', $this->court->slug), [
            'booking_date' => $bookingDate,
            'slots' => ['11:00'],
            'payment_method' => PaymentMethod::Qris->value,
            'customer_name' => 'New User',
            'customer_phone' => '081234567899',
            'customer_email' => 'new@example.com',
        ])->assertSessionHasNoErrors();

        $this->assertEquals(1, CourtSlot::count());
    }

    public function test_upload_proof_on_time_freezes_lock_and_prevents_expiration(): void
    {
        Storage::fake('local');

        $service = app(BookingService::class);
        $bookingDate = Carbon::tomorrow()->toDateString();

        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $bookingDate,
            startTimes: ['15:00'],
            paymentMethod: PaymentMethod::ManualTransfer,
            customerData: ['name' => 'Payer', 'phone' => '081234567890']
        );

        $file = UploadedFile::fake()->image('transfer_receipt.jpg', 600, 600);

        $response = $this->actingAs($this->customer)->post(route('booking.upload-proof', $booking->id), [
            'proof' => $file,
        ]);

        $response->assertRedirect(route('booking.sukses', ['code' => $booking->booking_code]));

        $booking->refresh();
        $payment = $booking->payment;

        $this->assertEquals(PaymentStatus::PendingVerification, $payment->status);
        $this->assertNull($booking->expires_at);
        $this->assertNotNull($payment->proof_path);

        $slot = CourtSlot::where('booking_id', $booking->id)->first();
        $this->assertNull($slot->locked_until);

        // Expire service should NOT remove this booking even if now passes
        $cleaned = app(ExpireStaleBookings::class)->execute($this->court->id, $bookingDate);
        $this->assertEquals(0, $cleaned);
        $this->assertDatabaseHas('court_slots', ['booking_id' => $booking->id]);
    }

    public function test_upload_proof_on_expired_booking_is_rejected(): void
    {
        Storage::fake('local');

        $booking = $this->createBooking([
            'status' => BookingStatus::Expired,
        ]);

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->actingAs($this->customer)->post(route('booking.upload-proof', $booking->id), [
            'proof' => $file,
        ]);

        $response->assertSessionHasErrors('proof');
    }

    public function test_customer_can_cancel_pending_booking_which_deletes_slots(): void
    {
        $service = app(BookingService::class);
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: Carbon::tomorrow()->toDateString(),
            startTimes: ['09:00', '10:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: ['name' => 'Cancel User', 'phone' => '081234567890']
        );

        $this->assertEquals(2, CourtSlot::where('booking_id', $booking->id)->count());

        $response = $this->actingAs($this->customer)->post(route('user.booking.cancel', $booking->id));

        $response->assertRedirect(route('user.dashboard'));

        $booking->refresh();
        $this->assertEquals(BookingStatus::Cancelled, $booking->status);
        $this->assertEquals(0, CourtSlot::where('booking_id', $booking->id)->count());
    }

    public function test_customer_cannot_cancel_confirmed_booking(): void
    {
        $booking = $this->createBooking([
            'status' => BookingStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->customer)->post(route('user.booking.cancel', $booking->id));
        $response->assertForbidden();
    }

    public function test_user_cannot_view_another_users_booking_detail(): void
    {
        $otherCustomer = User::factory()->create(['role' => UserRole::Customer]);

        $booking = $this->createBooking();

        $response = $this->actingAs($otherCustomer)->get(route('user.booking.detail', $booking->id));
        $response->assertForbidden();
    }

    public function test_operator_from_different_venue_cannot_view_booking(): void
    {
        $otherVenue = Venue::create([
            'name' => 'Venue Lain',
            'slug' => 'venue-lain',
            'city' => 'Jakarta Barat',
            'address' => 'Jl. Barat No. 5',
            'phone' => '081299999999',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
        ]);

        $operatorSameVenue = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
        ]);

        $operatorOtherVenue = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $otherVenue->id,
        ]);

        $booking = $this->createBooking([
            'status' => BookingStatus::Confirmed,
        ]);

        // Operator from different venue gets 403
        $this->actingAs($operatorOtherVenue)->get(route('user.booking.detail', $booking->id))->assertForbidden();

        // Operator from same venue gets 200
        $this->actingAs($operatorSameVenue)->get(route('user.booking.detail', $booking->id))->assertOk();
    }

    public function test_private_proof_access_authorization(): void
    {
        Storage::fake('local');
        $filePath = 'payment-proofs/test-proof.jpg';
        Storage::disk('local')->put($filePath, 'fake image content');

        $booking = $this->createBooking();

        $booking->payments()->create([
            'payment_method' => PaymentMethod::ManualTransfer,
            'amount' => 100000,
            'status' => PaymentStatus::PendingVerification,
            'proof_path' => $filePath,
            'proof_uploaded_at' => Carbon::now(),
        ]);

        $otherCustomer = User::factory()->create(['role' => UserRole::Customer]);

        // Guest is redirected to login
        $this->get(route('booking.proof', $booking->id))->assertRedirect(route('login'));

        // Other customer gets 403
        $this->actingAs($otherCustomer)->get(route('booking.proof', $booking->id))->assertForbidden();

        // Owner gets 200
        $this->actingAs($this->customer)->get(route('booking.proof', $booking->id))->assertOk();
    }

    public function test_operator_state_machine_transitions(): void
    {
        $service = app(BookingService::class);
        $operator = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
        ]);

        $bookingDate = Carbon::tomorrow()->toDateString();
        $booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $bookingDate,
            startTimes: ['20:00'],
            paymentMethod: PaymentMethod::ManualTransfer,
            customerData: ['name' => 'State Test', 'phone' => '081234567890']
        );

        // 1. Operator confirms
        $service->confirmByOperator($booking, $operator);
        $booking->refresh();
        $this->assertEquals(BookingStatus::Confirmed, $booking->status);
        $this->assertEquals(PaymentStatus::Paid, $booking->payment->status);
        $this->assertEquals($operator->id, $booking->payment->verified_by);
        $this->assertNotNull($booking->payment->verified_at);
        $this->assertEquals(1, CourtSlot::where('booking_id', $booking->id)->count());

        // 2. Operator cancels confirmed booking
        $service->cancelConfirmedByOperator($booking, $operator, 'Maintenance darurat');
        $booking->refresh();
        $this->assertEquals(BookingStatus::Cancelled, $booking->status);
        $this->assertEquals(PaymentStatus::RefundPending, $booking->payment->status);
        $this->assertEquals('Maintenance darurat', $booking->cancelled_reason);
        $this->assertEquals(0, CourtSlot::where('booking_id', $booking->id)->count());

        // 3. New booking and rejection with required reason
        $booking2 = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: $bookingDate,
            startTimes: ['20:00'],
            paymentMethod: PaymentMethod::ManualTransfer,
            customerData: ['name' => 'Reject Test', 'phone' => '081234567890']
        );
        $service->rejectByOperator($booking2, $operator, 'Bukti transfer buram');
        $booking2->refresh();
        $this->assertEquals(BookingStatus::Cancelled, $booking2->status);
        $this->assertEquals(PaymentStatus::Rejected, $booking2->payment->status);
        $this->assertEquals('Bukti transfer buram', $booking2->cancelled_reason);
        $this->assertEquals(0, CourtSlot::where('booking_id', $booking2->id)->count());
    }
}
