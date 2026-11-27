<?php

namespace Tests\Feature;

use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\SportCategory;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected Venue $venue;

    protected Court $court;

    protected User $owner;

    protected User $operator;

    protected User $admin;

    protected User $customer;

    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'role' => UserRole::Owner,
        ]);

        $this->venue = Venue::create([
            'owner_id' => $this->owner->id,
            'name' => 'LapangIn Test Arena',
            'slug' => 'lapangin-test-arena',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. Sudirman No. 10',
            'phone' => '081234567890',
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'prime_start_time' => '17:00:00',
            'prime_end_time' => '22:00:00',
            'slot_lock_duration_minutes' => 10,
        ]);

        $this->court = Court::create([
            'venue_id' => $this->venue->id,
            'name' => 'Lapangan Futsal 1',
            'slug' => 'lapangan-futsal-1',
            'sport_category' => SportCategory::Futsal,
            'price_regular' => 150000,
            'price_prime' => 200000,
            'surface_type' => 'Vinyl',
            'status' => CourtStatus::Active,
        ]);

        $this->operator = User::factory()->create([
            'role' => UserRole::Operator,
            'venue_id' => $this->venue->id,
        ]);

        $this->admin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
        ]);

        $this->customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);

        $service = app(BookingService::class);
        $this->booking = $service->createPending(
            user: $this->customer,
            court: $this->court,
            bookingDate: now()->addDay()->toDateString(),
            startTimes: ['18:00'],
            paymentMethod: PaymentMethod::Qris,
            customerData: [
                'name' => 'Budi Santoso',
                'phone' => '081299998888',
                'email' => 'budi@example.com',
            ]
        );
        $service->confirmByOperator($this->booking, $this->operator);
    }

    public function test_operator_can_export_booking_and_laporan_as_csv_and_pdf(): void
    {
        $code = $this->booking->booking_code;

        $csvBooking = $this->actingAs($this->operator)->get(route('operator.booking.export', ['format' => 'csv']));
        $csvBooking->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvBooking->headers->get('content-type'));
        $this->assertStringContainsString($code, $csvBooking->streamedContent());

        $pdfBooking = $this->actingAs($this->operator)->get(route('operator.booking.export', ['format' => 'pdf']));
        $pdfBooking->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfBooking->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', (string) $pdfBooking->getContent());

        $csvLaporan = $this->actingAs($this->operator)->get(route('operator.laporan.export', ['format' => 'csv', 'period' => 'bulanan']));
        $csvLaporan->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvLaporan->headers->get('content-type'));
        $this->assertStringContainsString($code, $csvLaporan->streamedContent());

        $pdfLaporan = $this->actingAs($this->operator)->get(route('operator.laporan.export', ['format' => 'pdf', 'period' => 'bulanan']));
        $pdfLaporan->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfLaporan->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', (string) $pdfLaporan->getContent());
    }

    public function test_owner_can_export_booking_and_laporan_as_csv_and_pdf(): void
    {
        $code = $this->booking->booking_code;

        $csvBooking = $this->actingAs($this->owner)->get(route('owner.booking.export', ['format' => 'csv', 'venue_id' => 'all']));
        $csvBooking->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvBooking->headers->get('content-type'));
        $this->assertStringContainsString($code, $csvBooking->streamedContent());

        $pdfBooking = $this->actingAs($this->owner)->get(route('owner.booking.export', ['format' => 'pdf', 'venue_id' => 'all']));
        $pdfBooking->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfBooking->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', (string) $pdfBooking->getContent());

        $csvLaporan = $this->actingAs($this->owner)->get(route('owner.laporan.export', ['format' => 'csv', 'period' => 'bulanan']));
        $csvLaporan->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvLaporan->headers->get('content-type'));
        $this->assertStringContainsString($code, $csvLaporan->streamedContent());

        $pdfLaporan = $this->actingAs($this->owner)->get(route('owner.laporan.export', ['format' => 'pdf', 'period' => 'bulanan']));
        $pdfLaporan->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfLaporan->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', (string) $pdfLaporan->getContent());
    }

    public function test_admin_can_export_laporan_as_csv_and_pdf(): void
    {
        $code = $this->booking->booking_code;

        $csvLaporan = $this->actingAs($this->admin)->get(route('admin.laporan.export', ['format' => 'csv', 'period' => 'bulanan']));
        $csvLaporan->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvLaporan->headers->get('content-type'));
        $this->assertStringContainsString($code, $csvLaporan->streamedContent());

        $pdfLaporan = $this->actingAs($this->admin)->get(route('admin.laporan.export', ['format' => 'pdf', 'period' => 'bulanan']));
        $pdfLaporan->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfLaporan->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', (string) $pdfLaporan->getContent());
    }

    public function test_unauthorized_roles_cannot_access_export_endpoints(): void
    {
        $this->actingAs($this->customer)->get(route('operator.booking.export'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('operator.laporan.export'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('owner.booking.export'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('owner.laporan.export'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.laporan.export'))->assertForbidden();

        $this->actingAs($this->operator)->get(route('owner.laporan.export'))->assertForbidden();
        $this->actingAs($this->operator)->get(route('admin.laporan.export'))->assertForbidden();
    }
}
