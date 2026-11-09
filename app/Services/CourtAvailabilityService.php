<?php

namespace App\Services;

use App\Enums\CourtStatus;
use App\Enums\SlotBlockType;
use App\Models\Court;
use App\Models\CourtSlot;
use Carbon\Carbon;

class CourtAvailabilityService
{
    public function __construct(
        protected ExpireStaleBookings $expireStaleBookings
    ) {}

    /**
     * Get availability of all hourly slots for a court on a given date.
     *
     * @return array{
     *     court: array{id: int, name: string, slug: string, status: string, price_regular: int, price_prime: int},
     *     date: string,
     *     is_operating: bool,
     *     slots: array<int, array{
     *         id: string,
     *         start_time: string,
     *         end_time: string,
     *         time_label: string,
     *         is_prime: bool,
     *         price: int,
     *         status: string,
     *         is_available: bool
     *     }>
     * }
     */
    public function getAvailability(Court $court, string $date): array
    {
        // 1. Lazy expire stale locks for this court & date
        $this->expireStaleBookings->handle($court->id, $date);

        $court->loadMissing('venue');
        $venue = $court->venue;
        $now = Carbon::now();

        // 2. Fetch existing slots for this court & date
        $occupiedSlots = CourtSlot::query()
            ->where('court_id', $court->id)
            ->whereDate('booking_date', $date)
            ->where(function ($q) use ($now) {
                $q->whereNull('locked_until')
                    ->orWhere('locked_until', '>', $now);
            })
            ->get()
            ->keyBy(fn (CourtSlot $slot) => Carbon::parse($slot->start_time)->format('H:i'));

        // 3. Determine operating hours
        $openTime = Carbon::createFromTimeString($venue->open_time ?? '08:00:00');
        $closeTime = Carbon::createFromTimeString($venue->close_time ?? '23:00:00');
        $primeStart = Carbon::createFromTimeString($venue->prime_start_time ?? '17:00:00');
        $primeEnd = Carbon::createFromTimeString($venue->prime_end_time ?? '23:00:00');

        $isCourtMaintenance = $court->status === CourtStatus::Maintenance;

        $slots = [];
        $current = $openTime->copy();
        $index = 1;

        while ($current->lt($closeTime)) {
            $next = $current->copy()->addHour();
            $timeKey = $current->format('H:i');
            $endTimeKey = $next->format('H:i');
            $slotDateTime = Carbon::parse("{$date} {$timeKey}:00");

            $isPrime = $current->gte($primeStart) && $current->lt($primeEnd);
            $price = $isPrime ? (int) $court->price_prime : (int) $court->price_regular;

            $status = 'available';

            if ($isCourtMaintenance) {
                $status = 'maintenance';
            } elseif ($slotDateTime->lte($now)) {
                $status = 'past';
            } elseif ($occupiedSlots->has($timeKey)) {
                /** @var CourtSlot $occupied */
                $occupied = $occupiedSlots->get($timeKey);
                $blockType = $occupied->block_type instanceof SlotBlockType
                    ? $occupied->block_type->value
                    : (string) $occupied->block_type;

                $status = ($blockType === SlotBlockType::Booking->value) ? 'booked' : 'blocked';
            }

            $slots[] = [
                'id' => "{$court->slug}_{$index}",
                'index' => $index,
                'start_time' => $timeKey,
                'end_time' => $endTimeKey,
                'time_label' => "{$timeKey} - {$endTimeKey}",
                'is_prime' => $isPrime,
                'price' => $price,
                'status' => $status,
                'is_available' => ($status === 'available'),
            ];

            $current->addHour();
            $index++;
        }

        return [
            'court' => [
                'id' => $court->id,
                'name' => $court->name,
                'slug' => $court->slug,
                'status' => $court->status->value,
                'price_regular' => (int) $court->price_regular,
                'price_prime' => (int) $court->price_prime,
            ],
            'date' => $date,
            'is_operating' => ! $isCourtMaintenance,
            'slots' => $slots,
        ];
    }
}
