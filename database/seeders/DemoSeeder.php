<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo seeds for local environment.
     * Idempotent: Skips execution if demo bookings already exist.
     */
    public function run(): void
    {
        if (Booking::where('notes', 'like', '%[DEMO]%')->exists()) {
            return;
        }

        $venue = Venue::where('slug', 'lapangin-sports-center')->first();
        if (! $venue) {
            return;
        }

        $customer = User::where('email', 'ahmad@email.com')->first()
            ?? User::where('role', UserRole::Customer)->first();

        $operator = User::where('email', 'budi@lapangin.id')->first()
            ?? User::where('role', UserRole::Operator)->first();

        if (! $customer || ! $operator) {
            return;
        }

        $futsalA = Court::where('slug', 'futsal-arena-a')->first();
        $futsalB = Court::where('slug', 'futsal-arena-b')->first();
        $badminton1 = Court::where('slug', 'badminton-court-1')->first();

        if (! $futsalA || ! $badminton1 || ! $futsalB) {
            return;
        }

        /** @var BookingService $bookingService */
        $bookingService = app(BookingService::class);

        $tomorrow = Carbon::tomorrow()->toDateString();
        $dayAfter = Carbon::tomorrow()->addDay()->toDateString();
        $today = Carbon::today()->toDateString();

        // 1. Pending Unpaid Booking (Besok Pagi, 2 jam)
        $bookingService->createPending(
            $customer,
            $futsalA,
            $tomorrow,
            ['09:00', '10:00'],
            PaymentMethod::ManualTransfer,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Latihan futsal tim divisi marketing (Pending Unpaid)',
            ]
        );

        // 2. Pending Verification Booking with Uploaded Proof (Besok Siang, 2 jam)
        $b2 = $bookingService->createPending(
            $customer,
            $badminton1,
            $tomorrow,
            ['14:00', '15:00'],
            PaymentMethod::Qris,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Main santai badminton ganda putra (Pending Verifikasi)',
            ]
        );
        $fakeProof = UploadedFile::fake()->create('bukti-transfer-demo.jpg', 250, 'image/jpeg');
        $bookingService->uploadProof($b2, $fakeProof);

        // 3. Confirmed Booking (Besok Malam / Prime Time, 2 jam)
        $b3 = $bookingService->createPending(
            $customer,
            $futsalA,
            $tomorrow,
            ['19:00', '20:00'],
            PaymentMethod::VirtualAccount,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Sparring futsal antar departemen (Confirmed)',
            ]
        );
        $bookingService->confirmByOperator($b3, $operator);

        // 4. Confirmed Booking for Today (Memberi data riil pada KPI 'Hari Ini' di dashboard operator)
        $b4 = $bookingService->createPending(
            $customer,
            $badminton1,
            $dayAfter,
            ['18:00', '19:00'],
            PaymentMethod::ManualTransfer,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Turnamen badminton hari ini (Confirmed Today)',
            ]
        );
        $bookingService->confirmByOperator($b4, $operator);
        $b4->items()->update(['booking_date' => $today]);
        CourtSlot::where('booking_id', $b4->id)->update(['booking_date' => $today]);

        // 5. Rejected / Cancelled Booking by Operator (Besok Pagi, 1 jam)
        $b5 = $bookingService->createPending(
            $customer,
            $futsalB,
            $tomorrow,
            ['11:00'],
            PaymentMethod::ManualTransfer,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Booking latihan kiper (Rejected)',
            ]
        );
        $bookingService->rejectByOperator($b5, $operator, 'Bukti transfer buram dan nomor referensi tidak terbaca.');

        // 6. Cancelled Booking by Customer (Besok Sore, 1 jam)
        $b6 = $bookingService->createPending(
            $customer,
            $futsalB,
            $tomorrow,
            ['16:00'],
            PaymentMethod::EWallet,
            [
                'name' => $customer->name,
                'phone' => $customer->phone ?? '081234567890',
                'email' => $customer->email,
                'notes' => '[DEMO] Booking futsal batal mendadak (Cancelled by Customer)',
            ]
        );
        $bookingService->cancelPendingByCustomer($b6, $customer);
    }
}
