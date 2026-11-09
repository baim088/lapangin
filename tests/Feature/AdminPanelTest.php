<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected Venue $venue;

    protected User $superAdmin;

    protected User $operator;

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
            'email' => 'senayan@lapangin.id',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 15,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin Test',
            'email' => 'admin@lapangin.id',
            'role' => UserRole::SuperAdmin,
            'status' => UserStatus::Active,
        ]);

        $this->operator = User::factory()->create([
            'name' => 'Operator Test',
            'email' => 'operator@lapangin.id',
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
            'status' => UserStatus::Active,
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Customer Test',
            'email' => 'customer@lapangin.id',
            'role' => UserRole::Customer,
            'status' => UserStatus::Active,
        ]);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.venues'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.users'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_and_operator_get_403_on_admin_routes(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.dashboard'));
        $response->assertForbidden();

        $response = $this->actingAs($this->operator)->get(route('admin.dashboard'));
        $response->assertForbidden();

        $response = $this->actingAs($this->operator)->get(route('admin.venues'));
        $response->assertForbidden();

        $response = $this->actingAs($this->operator)->get(route('admin.users'));
        $response->assertForbidden();
    }

    public function test_super_admin_can_view_admin_dashboard_with_kpis(): void
    {
        $court = Court::create([
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Futsal A',
            'slug' => 'senayan-lapangan-futsal-a',
            'sport_category' => SportCategory::Futsal,
            'surface_type' => 'Vinyl',
            'price_regular' => 100000,
            'price_prime' => 150000,
            'status' => CourtStatus::Active,
        ]);

        $booking = Booking::create([
            'venue_id' => $this->venue->id,
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_phone' => '08123456789',
            'customer_email' => $this->customer->email,
            'booking_code' => 'LPN-2026-9999',
            'checkin_token' => str_repeat('a', 64),
            'status' => BookingStatus::Confirmed,
            'total_hours' => 1,
            'subtotal' => 150000,
            'total_amount' => 150000,
            'notes' => 'Test Booking',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => 150000,
            'payment_method' => PaymentMethod::ManualTransfer,
            'status' => PaymentStatus::Paid,
            'verified_at' => now(),
            'verified_by' => $this->operator->id,
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('totalVenues', 1);
        $response->assertViewHas('totalUsers', 3);
        $response->assertViewHas('totalBookings', 1);
        $response->assertViewHas('totalRevenue', 150000);
        $response->assertSee('Senayan Sport Arena');
    }

    public function test_super_admin_can_view_venues_list(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.venues'));

        $response->assertOk();
        $response->assertViewIs('admin.venues');
        $response->assertViewHas('venues');
        $response->assertSee('Senayan Sport Arena');
    }

    public function test_super_admin_can_update_venue_configuration(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.venues.update', $this->venue), [
            'name' => 'Senayan Sport Center Updated',
            'address' => 'Jl. Pintu Satu Senayan No. 99, Jakarta',
            'phone' => '081299998888',
            'email' => 'contact@senayansport.id',
            'open_time' => '07:00',
            'close_time' => '23:00',
            'prime_start_time' => '18:00',
            'prime_end_time' => '23:00',
            'slot_lock_duration_minutes' => 20,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->venue->refresh();
        $this->assertEquals('Senayan Sport Center Updated', $this->venue->name);
        $this->assertEquals('07:00:00', $this->venue->open_time);
        $this->assertEquals('23:00:00', $this->venue->close_time);
        $this->assertEquals('18:00:00', $this->venue->prime_start_time);
        $this->assertEquals('23:00:00', $this->venue->prime_end_time);
        $this->assertEquals(20, $this->venue->slot_lock_duration_minutes);
    }

    public function test_venue_update_validation_rejects_invalid_hours_and_durations(): void
    {
        // Close time must be after open time
        $response = $this->actingAs($this->superAdmin)->put(route('admin.venues.update', $this->venue), [
            'name' => 'Invalid Hours Venue',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'open_time' => '18:00',
            'close_time' => '08:00', // invalid: before open_time
            'prime_start_time' => '19:00',
            'prime_end_time' => '22:00',
            'slot_lock_duration_minutes' => 15,
        ]);

        $response->assertSessionHasErrors(['close_time']);

        // Prime end time must be after prime start time
        $response = $this->actingAs($this->superAdmin)->put(route('admin.venues.update', $this->venue), [
            'name' => 'Invalid Prime Venue',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'open_time' => '08:00',
            'close_time' => '22:00',
            'prime_start_time' => '20:00',
            'prime_end_time' => '19:00', // invalid: before prime_start_time
            'slot_lock_duration_minutes' => 15,
        ]);

        $response->assertSessionHasErrors(['prime_end_time']);

        // Slot lock duration min 5, max 60
        $response = $this->actingAs($this->superAdmin)->put(route('admin.venues.update', $this->venue), [
            'name' => 'Invalid Lock Duration Venue',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'open_time' => '08:00',
            'close_time' => '22:00',
            'prime_start_time' => '17:00',
            'prime_end_time' => '22:00',
            'slot_lock_duration_minutes' => 3, // invalid: < 5
        ]);

        $response->assertSessionHasErrors(['slot_lock_duration_minutes']);
    }

    public function test_super_admin_can_view_users_with_search_and_role_filter(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.users', [
            'search' => 'Operator',
            'role' => 'operator',
        ]));

        $response->assertOk();
        $response->assertViewIs('admin.users');
        $response->assertSee('Operator Test');
        $response->assertDontSee('Customer Test');
    }

    public function test_super_admin_can_toggle_user_status_between_active_and_suspended(): void
    {
        $this->assertEquals(UserStatus::Active, $this->customer->status);

        // Suspend
        $response = $this->actingAs($this->superAdmin)->patch(route('admin.users.toggle-status', $this->customer));
        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->customer->refresh();
        $this->assertEquals(UserStatus::Suspended, $this->customer->status);

        // Reactivate
        $response = $this->actingAs($this->superAdmin)->patch(route('admin.users.toggle-status', $this->customer));
        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->customer->refresh();
        $this->assertEquals(UserStatus::Active, $this->customer->status);
    }

    public function test_super_admin_cannot_suspend_their_own_account(): void
    {
        $response = $this->actingAs($this->superAdmin)->patch(route('admin.users.toggle-status', $this->superAdmin));

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);

        $this->superAdmin->refresh();
        $this->assertEquals(UserStatus::Active, $this->superAdmin->status);
    }

    public function test_super_admin_can_update_user_role_to_operator_with_venue_id(): void
    {
        $this->assertEquals(UserRole::Customer, $this->customer->role);
        $this->assertNull($this->customer->venue_id);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update-role', $this->customer), [
            'role' => UserRole::Operator->value,
            'venue_id' => $this->venue->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->customer->refresh();
        $this->assertEquals(UserRole::Operator, $this->customer->role);
        $this->assertEquals($this->venue->id, $this->customer->venue_id);
    }

    public function test_super_admin_cannot_assign_operator_role_without_venue_id(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update-role', $this->customer), [
            'role' => UserRole::Operator->value,
            'venue_id' => null,
        ]);

        $response->assertSessionHasErrors(['venue_id']);

        $this->customer->refresh();
        $this->assertEquals(UserRole::Customer, $this->customer->role);
    }

    public function test_super_admin_can_demote_operator_to_customer_and_venue_id_is_cleared(): void
    {
        $this->assertEquals(UserRole::Operator, $this->operator->role);
        $this->assertNotNull($this->operator->venue_id);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update-role', $this->operator), [
            'role' => UserRole::Customer->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->operator->refresh();
        $this->assertEquals(UserRole::Customer, $this->operator->role);
        $this->assertNull($this->operator->venue_id);
    }

    public function test_super_admin_cannot_demote_themselves(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update-role', $this->superAdmin), [
            'role' => UserRole::Customer->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);

        $this->superAdmin->refresh();
        $this->assertEquals(UserRole::SuperAdmin, $this->superAdmin->role);
    }
}
