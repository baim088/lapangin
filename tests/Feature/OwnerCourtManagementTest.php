<?php

namespace Tests\Feature;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Models\Court;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerCourtManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected User $otherOwner;

    protected User $operator;

    protected Venue $venue;

    protected Venue $otherVenue;

    protected Court $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create(['role' => UserRole::Owner]);
        $this->otherOwner = User::factory()->create(['role' => UserRole::Owner]);

        $this->venue = Venue::create([
            'owner_id' => $this->owner->id,
            'name' => 'Owner Sports Hub',
            'slug' => 'owner-sports-hub',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. Kemang Raya No. 1',
            'phone' => '081211112222',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 10,
        ]);

        $this->otherVenue = Venue::create([
            'owner_id' => $this->otherOwner->id,
            'name' => 'Competitor Arena',
            'slug' => 'competitor-arena',
            'city' => 'Jakarta Utara',
            'address' => 'Jl. Kelapa Gading No. 9',
            'phone' => '081233334444',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 10,
        ]);

        $this->operator = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
        ]);

        $this->court = Court::create([
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Padel 1',
            'slug' => 'lapangan-padel-1',
            'sport_category' => SportCategory::Padel,
            'surface_type' => 'Panoramic Glass',
            'price_regular' => 250000,
            'price_prime' => 350000,
            'status' => CourtStatus::Active,
        ]);
    }

    public function test_operator_no_longer_has_court_management_routes(): void
    {
        $this->actingAs($this->operator)->get('/operator/lapangan')->assertNotFound();
    }

    public function test_owner_can_create_court_with_operating_and_prime_hours(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.lapangan.store'), [
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Futsal VIP',
            'sport_category' => 'futsal',
            'surface_type' => 'Karpet Vinyl',
            'price_regular' => 140000,
            'price_prime' => 210000,
            'open_time' => '07:00',
            'close_time' => '23:00',
            'prime_start_time' => '18:00',
            'prime_end_time' => '23:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courts', [
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Futsal VIP',
            'sport_category' => 'futsal',
            'surface_type' => 'Karpet Vinyl',
            'price_regular' => 140000,
            'price_prime' => 210000,
        ]);

        $this->venue->refresh();
        $this->assertEquals('07:00:00', $this->venue->open_time);
        $this->assertEquals('23:00:00', $this->venue->close_time);
        $this->assertEquals('18:00:00', $this->venue->prime_start_time);
        $this->assertEquals('23:00:00', $this->venue->prime_end_time);
    }

    public function test_owner_can_update_court_and_toggle_status(): void
    {
        $updateResponse = $this->actingAs($this->owner)->put(route('owner.lapangan.update', $this->court->id), [
            'name' => 'Lapangan Padel Pro',
            'sport_category' => 'padel',
            'surface_type' => 'Pro Turf',
            'price_regular' => 275000,
            'price_prime' => 380000,
            'open_time' => '06:00',
            'close_time' => '22:00',
            'prime_start_time' => '17:00',
            'prime_end_time' => '21:00',
        ]);

        $updateResponse->assertRedirect();
        $this->court->refresh();
        $this->venue->refresh();

        $this->assertEquals('Lapangan Padel Pro', $this->court->name);
        $this->assertEquals(275000, $this->court->price_regular);
        $this->assertEquals('06:00:00', $this->venue->open_time);
        $this->assertEquals('21:00:00', $this->venue->prime_end_time);

        $toggleResponse = $this->actingAs($this->owner)->patch(route('owner.lapangan.toggle-status', $this->court->id));
        $toggleResponse->assertRedirect();

        $this->court->refresh();
        $this->assertEquals(CourtStatus::Maintenance, $this->court->status);
    }

    public function test_owner_cannot_modify_other_owners_court(): void
    {
        $otherCourt = Court::create([
            'venue_id' => $this->otherVenue->id,
            'name' => 'Other Court',
            'slug' => 'other-court',
            'sport_category' => SportCategory::Badminton,
            'surface_type' => 'Kayu',
            'price_regular' => 80000,
            'price_prime' => 110000,
            'status' => CourtStatus::Active,
        ]);

        $this->actingAs($this->owner)
            ->patch(route('owner.lapangan.toggle-status', $otherCourt->id))
            ->assertForbidden();
    }
}
