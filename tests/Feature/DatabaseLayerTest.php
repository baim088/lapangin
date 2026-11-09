<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class DatabaseLayerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * (a) Insert dua court_slots dengan kombinasi court_id + booking_date + start_time sama
     * melempar Illuminate\Database\UniqueConstraintViolationException.
     */
    public function test_duplicate_court_slots_throws_unique_constraint_violation_exception(): void
    {
        $court = Court::factory()->create();
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();

        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking1->id,
            'booking_date' => '2026-11-15',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'block_type' => SlotBlockType::Booking,
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking2->id,
            'booking_date' => '2026-11-15',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'block_type' => SlotBlockType::Booking,
        ]);
    }

    /**
     * (b) Slot blokir operator dan slot booking pada waktu sama saling bentrok.
     */
    public function test_operator_block_and_customer_booking_collide_on_same_slot(): void
    {
        $court = Court::factory()->create();
        $booking = Booking::factory()->create();

        // Operator blokir slot untuk maintenance
        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => null,
            'booking_date' => '2026-11-15',
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'block_type' => SlotBlockType::Maintenance,
            'note' => 'Perawatan rutin',
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        // Pelanggan mencoba booking di slot yang sama
        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'booking_date' => '2026-11-15',
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'block_type' => SlotBlockType::Booking,
        ]);
    }

    /**
     * (c) FK restrict menolak penghapusan booking yang masih punya slot atau item.
     */
    public function test_fk_restrict_prevents_deleting_booking_with_slots_or_items(): void
    {
        $booking = Booking::factory()->create();
        $court = Court::factory()->create();

        BookingItem::create([
            'booking_id' => $booking->id,
            'court_id' => $court->id,
            'booking_date' => '2026-11-15',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'is_prime' => false,
            'price_per_hour' => 150000,
        ]);

        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'booking_date' => '2026-11-15',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'block_type' => SlotBlockType::Booking,
        ]);

        $this->expectException(QueryException::class);
        $booking->delete();
    }

    /**
     * (d) Invarian block_type vs booking_id di D1:
     * block_type = 'booking' <=> booking_id NOT NULL
     */
    public function test_court_slot_enforces_booking_invariant(): void
    {
        $court = Court::factory()->create();

        // 1. Booking tanpa booking_id harus melempar InvalidArgumentException
        try {
            CourtSlot::create([
                'court_id' => $court->id,
                'booking_id' => null,
                'booking_date' => '2026-11-15',
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'block_type' => SlotBlockType::Booking,
            ]);
            $this->fail('Expected InvalidArgumentException for booking type with null booking_id.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('wajib memiliki booking_id', $e->getMessage());
        }

        // 2. Non-booking dengan booking_id harus melempar InvalidArgumentException
        $booking = Booking::factory()->create();
        try {
            CourtSlot::create([
                'court_id' => $court->id,
                'booking_id' => $booking->id,
                'booking_date' => '2026-11-15',
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'block_type' => SlotBlockType::Maintenance,
            ]);
            $this->fail('Expected InvalidArgumentException for non-booking type with non-null booking_id.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('tidak boleh memiliki booking_id', $e->getMessage());
        }

        // 3. Pasangan valid harus berhasil dibuat
        $validSlot = CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'booking_date' => '2026-11-15',
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'block_type' => SlotBlockType::Booking,
        ]);
        $this->assertNotNull($validSlot->id);

        $validMaintenance = CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => null,
            'booking_date' => '2026-11-15',
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'block_type' => SlotBlockType::Maintenance,
        ]);
        $this->assertNotNull($validMaintenance->id);
    }

    /**
     * (e) Cast enum dan relasi dasar.
     */
    public function test_enum_casts_and_basic_relations(): void
    {
        $venue = Venue::factory()->create();
        $court = Court::factory()->for($venue)->create([
            'sport_category' => SportCategory::Futsal,
            'status' => CourtStatus::Active,
            'price_regular' => 150000,
            'price_prime' => 220000,
        ]);
        $user = User::factory()->create([
            'role' => UserRole::Customer,
            'status' => UserStatus::Active,
        ]);
        $booking = Booking::factory()->for($venue)->for($user)->create([
            'status' => BookingStatus::Pending,
            'subtotal' => 150000,
            'total_amount' => 150000,
        ]);
        $slot = CourtSlot::factory()->for($court)->for($booking)->create([
            'block_type' => SlotBlockType::Booking,
        ]);
        $payment = Payment::factory()->for($booking)->create([
            'payment_method' => PaymentMethod::Qris,
            'status' => PaymentStatus::Unpaid,
            'amount' => 150000,
        ]);

        // Verifikasi enum instances
        $this->assertInstanceOf(UserRole::class, $user->role);
        $this->assertEquals(UserRole::Customer, $user->role);
        $this->assertInstanceOf(UserStatus::class, $user->status);
        $this->assertEquals(UserStatus::Active, $user->status);

        $this->assertInstanceOf(SportCategory::class, $court->sport_category);
        $this->assertEquals(SportCategory::Futsal, $court->sport_category);
        $this->assertInstanceOf(CourtStatus::class, $court->status);
        $this->assertEquals(CourtStatus::Active, $court->status);

        $this->assertInstanceOf(BookingStatus::class, $booking->status);
        $this->assertEquals(BookingStatus::Pending, $booking->status);

        $this->assertInstanceOf(SlotBlockType::class, $slot->block_type);
        $this->assertEquals(SlotBlockType::Booking, $slot->block_type);

        $this->assertInstanceOf(PaymentMethod::class, $payment->payment_method);
        $this->assertEquals(PaymentMethod::Qris, $payment->payment_method);
        $this->assertInstanceOf(PaymentStatus::class, $payment->status);
        $this->assertEquals(PaymentStatus::Unpaid, $payment->status);

        // Verifikasi integer Rupiah (D4)
        $this->assertIsInt($court->price_regular);
        $this->assertIsInt($court->price_prime);
        $this->assertIsInt($booking->subtotal);
        $this->assertIsInt($booking->total_amount);
        $this->assertIsInt($payment->amount);

        // Verifikasi relasi
        $this->assertTrue($court->venue->is($venue));
        $this->assertTrue($booking->user->is($user));
        $this->assertTrue($booking->venue->is($venue));
        $this->assertTrue($slot->court->is($court));
        $this->assertTrue($slot->booking->is($booking));
        $this->assertTrue($payment->booking->is($booking));
    }

    /**
     * (f) Mass assignment: User::create dengan role=super_admin tidak mengubah role (tetap default customer).
     */
    public function test_user_mass_assignment_protection(): void
    {
        $user = User::create([
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'phone' => '081299999999',
            'password' => 'secret123',
            'role' => 'super_admin',
            'status' => 'suspended',
            'venue_id' => 999,
        ]);

        $this->assertEquals(UserRole::Customer, $user->role);
        $this->assertEquals(UserStatus::Active, $user->status);
        $this->assertNull($user->venue_id);

        $user->refresh();

        $this->assertEquals(UserRole::Customer, $user->role);
        $this->assertEquals(UserStatus::Active, $user->status);
        $this->assertNull($user->venue_id);
    }

    /**
     * CourtSlot default block_type adalah 'booking' jika atribut block_type tidak diisi.
     */
    public function test_court_slot_default_block_type_is_booking_when_omitted(): void
    {
        $court = Court::factory()->create();
        $booking = Booking::factory()->create();

        $slot = CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'booking_date' => '2026-11-20',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
        ]);

        $this->assertEquals(SlotBlockType::Booking, $slot->block_type);

        $slot->refresh();
        $this->assertEquals(SlotBlockType::Booking, $slot->block_type);
    }
}
