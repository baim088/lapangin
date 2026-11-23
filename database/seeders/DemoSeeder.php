<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo seeds for testing multi-tenant and financial reports.
     */
    public function run(): void
    {
        $venueA = Venue::whereIn('slug', ['lapangin-sports-center', 'lapangin-arena-sportama'])->first();
        $venueB = Venue::where('slug', 'lapangin-mega-stadium')->first();

        if (! $venueA || ! $venueB) {
            return;
        }

        $kasirA = User::where('email', 'kasir.a@lapangin.id')->first() ?? User::where('email', 'budi@lapangin.id')->first();
        $kasirB = User::where('email', 'kasir.b@lapangin.id')->first();

        $c1 = User::where('email', 'customer1@email.com')->first();
        $c2 = User::where('email', 'customer2@email.com')->first();
        $c3 = User::where('email', 'customer3@email.com')->first();
        $c4 = User::where('email', 'customer4@email.com')->first();
        $c5 = User::where('email', 'customer5@email.com')->first();

        if (! $c1 || ! $c2 || ! $c3 || ! $c4 || ! $c5) {
            return;
        }

        // Courts
        $futsalA = Court::where('slug', 'futsal-arena-a')->first();
        $futsalB = Court::where('slug', 'futsal-arena-b')->first();
        $badminton1 = Court::where('slug', 'badminton-court-1')->first();

        $minisoccer = Court::where('slug', 'mini-soccer-grand-field')->first();
        $basket = Court::where('slug', 'basketball-indoor-pro')->first();

        if (! $futsalA || ! $badminton1 || ! $minisoccer || ! $basket) {
            return;
        }

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $threeDaysAgo = Carbon::today()->subDays(3)->toDateString();
        $lastWeek = Carbon::today()->subDays(6)->toDateString();
        $lastMonth = Carbon::today()->subDays(25)->toDateString();

        // Helper closure to create realistic booking records
        $createBooking = function (
            Venue $venue,
            User $customer,
            Court $court,
            string $bookingDate,
            array $startTimes,
            string $paymentType,
            BookingStatus $status,
            PaymentMethod $method,
            Carbon $createdAt,
            ?User $cashier = null,
            ?string $settleMethod = null,
            string $note = ''
        ) {
            $totalHours = count($startTimes);
            $primeStart = Carbon::createFromTimeString($venue->prime_start_time ?? '17:00:00');
            $primeEnd = Carbon::createFromTimeString($venue->prime_end_time ?? '23:00:00');

            $items = [];
            $subtotal = 0;
            foreach ($startTimes as $t) {
                $cTime = Carbon::createFromTimeString($t);
                $isPrime = $cTime->gte($primeStart) && $cTime->lt($primeEnd);
                $price = $isPrime ? (int) $court->price_prime : (int) $court->price_regular;
                $subtotal += $price;
                $items[] = [
                    'start_time' => $cTime->format('H:i:00'),
                    'end_time' => $cTime->copy()->addHour()->format('H:i:00'),
                    'is_prime' => $isPrime,
                    'price' => $price,
                ];
            }
            $totalAmount = $subtotal;

            $dpPct = 50;
            $dpAmount = ($paymentType === 'dp') ? (int) round($totalAmount * 0.5) : 0;

            if ($status === BookingStatus::DpPaid) {
                $paidAmount = $dpAmount;
                $remainingAmount = $totalAmount - $dpAmount;
            } elseif (in_array($status, [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])) {
                $paidAmount = $totalAmount;
                $remainingAmount = 0;
            } else {
                $paidAmount = 0;
                $remainingAmount = $totalAmount;
            }

            $bookingCode = sprintf('LPN-%d-%04d', $createdAt->year, random_int(1000, 9999));
            while (Booking::where('booking_code', $bookingCode)->exists()) {
                $bookingCode = sprintf('LPN-%d-%04d', $createdAt->year, random_int(1000, 9999));
            }

            $booking = Booking::create([
                'venue_id' => $venue->id,
                'user_id' => $customer->id,
                'booking_code' => $bookingCode,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone ?? '081234567890',
                'customer_email' => $customer->email,
                'notes' => $note,
                'payment_type' => $paymentType,
                'dp_percentage' => $dpPct,
                'dp_amount' => $dpAmount,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'settlement_method' => $settleMethod,
                'settled_at' => $settleMethod ? $createdAt->copy()->addHours(1) : null,
                'settled_by' => $cashier?->id,
                'total_hours' => $totalHours,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'status' => $status,
                'expires_at' => null,
                'checkin_token' => Str::random(64),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($items as $item) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'court_id' => $court->id,
                    'booking_date' => $bookingDate,
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'is_prime' => $item['is_prime'],
                    'price_per_hour' => $item['price'],
                    'created_at' => $createdAt,
                ]);

                if (! in_array($status, [BookingStatus::Cancelled, BookingStatus::Expired])) {
                    CourtSlot::create([
                        'court_id' => $court->id,
                        'booking_id' => $booking->id,
                        'booking_date' => $bookingDate,
                        'start_time' => $item['start_time'],
                        'end_time' => $item['end_time'],
                        'block_type' => SlotBlockType::Booking,
                        'locked_until' => null,
                    ]);
                }
            }

            // Payment 1: Initial Payment (DP or Full)
            $initialPayAmount = ($paymentType === 'dp') ? $dpAmount : $totalAmount;
            $payStatus = in_array($status, [BookingStatus::Pending, BookingStatus::PendingPayment])
                ? PaymentStatus::Unpaid
                : PaymentStatus::Paid;

            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $method,
                'payment_type' => ($paymentType === 'dp') ? 'dp' : 'full',
                'status' => $payStatus,
                'amount' => $initialPayAmount,
                'verified_by' => $cashier?->id,
                'verified_at' => $payStatus === PaymentStatus::Paid ? $createdAt : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Payment 2: Settlement Payment if applicable (for settled DP)
            if ($paymentType === 'dp' && $settleMethod && $remainingAmount === 0) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => PaymentMethod::tryFrom($settleMethod) ?? PaymentMethod::Cash,
                    'payment_type' => 'settlement',
                    'status' => PaymentStatus::Paid,
                    'amount' => $totalAmount - $dpAmount,
                    'cashier_id' => $cashier?->id,
                    'verified_by' => $cashier?->id,
                    'verified_at' => $createdAt->copy()->addHours(1),
                    'notes' => 'Pelunasan sisa di kasir venue',
                    'created_at' => $createdAt->copy()->addHours(1),
                    'updated_at' => $createdAt->copy()->addHours(1),
                ]);
            }

            return $booking;
        };

        // ==========================================
        // 1. SKENARIO VENUE A (Owner A: Arena Sportama)
        // ==========================================

        // [HARI INI - SIAP DIUJI PELUNASAN KASIR A]
        // Customer 1 booking Futsal A hari ini jam 19:00 (Prime), status DP Paid. Sisa Rp 110.000 menunggu pelunasan kasir!
        $createBooking(
            venue: $venueA,
            customer: $c1,
            court: $futsalA,
            bookingDate: $today,
            startTimes: ['19:00'],
            paymentType: 'dp',
            status: BookingStatus::DpPaid,
            method: PaymentMethod::Qris,
            createdAt: Carbon::now()->subHours(2),
            cashier: $kasirA,
            note: 'Sparring Futsal Malam (Booking DP 50% - Menunggu Pelunasan di Lokasi)'
        );

        // [HARI INI - Lunas Penuh]
        // Customer 2 booking Badminton 1 hari ini jam 15:00, Full Payment Rp 65.000
        $createBooking(
            venue: $venueA,
            customer: $c2,
            court: $badminton1,
            bookingDate: $today,
            startTimes: ['15:00'],
            paymentType: 'full',
            status: BookingStatus::PaidInFull,
            method: PaymentMethod::VirtualAccount,
            createdAt: Carbon::now()->subHours(3),
            cashier: $kasirA,
            note: 'Latihan Badminton Tunggal (Lunas Full Payment)'
        );

        // [MINGGU INI - Pelunasan Berhasil di Lokasi]
        // Customer 3 booking Futsal B 3 hari lalu, DP Rp 110.000 dan sudah dilunasi kasir Rian secara Cash Rp 110.000
        $createBooking(
            venue: $venueA,
            customer: $c3,
            court: $futsalB ?? $futsalA,
            bookingDate: $threeDaysAgo,
            startTimes: ['20:00'],
            paymentType: 'dp',
            status: BookingStatus::PaidInFull,
            method: PaymentMethod::Qris,
            createdAt: Carbon::today()->subDays(3)->setHour(14),
            cashier: $kasirA,
            settleMethod: 'cash',
            note: 'Turnamen Mini Antar Divisi (DP QRIS + Pelunasan Tunai di Tempat)'
        );

        // [BULAN LALU - Selesai Bermain]
        // Customer 4 booking 2 jam Futsal A bulan lalu, Full Rp 300.000
        $createBooking(
            venue: $venueA,
            customer: $c4,
            court: $futsalA,
            bookingDate: $lastMonth,
            startTimes: ['09:00', '10:00'],
            paymentType: 'full',
            status: BookingStatus::Completed,
            method: PaymentMethod::ManualTransfer,
            createdAt: Carbon::today()->subDays(25)->setHour(10),
            cashier: $kasirA,
            note: 'Latihan Rutin Komunitas (Bulan Lalu Selesai)'
        );

        // ==========================================
        // 2. SKENARIO VENUE B (Owner B: Mega Stadium)
        // ==========================================

        // [HARI INI - SIAP DIUJI PELUNASAN KASIR B]
        // Customer 5 booking Mini Soccer Grand Field hari ini jam 20:00 (Prime), DP Rp 325.000, Sisa Rp 325.000!
        $createBooking(
            venue: $venueB,
            customer: $c5,
            court: $minisoccer,
            bookingDate: $today,
            startTimes: ['20:00'],
            paymentType: 'dp',
            status: BookingStatus::DpPaid,
            method: PaymentMethod::VirtualAccount,
            createdAt: Carbon::now()->subHours(4),
            cashier: $kasirB,
            note: 'Friendly Match 7v7 (Booking DP - Siap Dilunasi Kasir Mega Stadium)'
        );

        // [HARI INI - Lunas Penuh Basket]
        // Customer 1 booking Basket Indoor Pro hari ini jam 16:00, Full Rp 250.000
        $createBooking(
            venue: $venueB,
            customer: $c1,
            court: $basket,
            bookingDate: $today,
            startTimes: ['16:00'],
            paymentType: 'full',
            status: BookingStatus::PaidInFull,
            method: PaymentMethod::Qris,
            createdAt: Carbon::now()->subHours(5),
            cashier: $kasirB,
            note: 'Latihan Tim Basket Kampus'
        );

        // [MINGGU INI - Pelunasan di Lokasi Kasir B via QRIS]
        // Customer 2 booking Basket 4 hari lalu, DP Rp 125.000 + Pelunasan QRIS Rp 125.000
        $createBooking(
            venue: $venueB,
            customer: $c2,
            court: $basket,
            bookingDate: $yesterday,
            startTimes: ['18:00'],
            paymentType: 'dp',
            status: BookingStatus::Completed,
            method: PaymentMethod::ManualTransfer,
            createdAt: Carbon::yesterday()->setHour(12),
            cashier: $kasirB,
            settleMethod: 'qris',
            note: 'Game 5 on 5 Eksibisi (Selesai Bermain)'
        );

        // [BULAN LALU - Mini Soccer 2 Jam]
        // Customer 3 booking Mini Soccer 2 jam bulan lalu, Total Rp 1.300.000 (DP Rp 650.000 + Pelunasan Rp 650.000)
        $createBooking(
            venue: $venueB,
            customer: $c3,
            court: $minisoccer,
            bookingDate: $lastMonth,
            startTimes: ['18:00', '19:00'],
            paymentType: 'dp',
            status: BookingStatus::Completed,
            method: PaymentMethod::VirtualAccount,
            createdAt: Carbon::today()->subDays(25)->setHour(14),
            cashier: $kasirB,
            settleMethod: 'manual_transfer',
            note: 'Liga Perusahaan Bulan Lalu (Selesai Bermain)'
        );
    }
}
