<?php

namespace App\Models;

use App\Enums\SlotBlockType;
use Database\Factories\CourtSlotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

/**
 * Invarian hubungan booking_id dan block_type (D1):
 * - block_type = 'booking' <=> booking_id NOT NULL
 * - block_type != 'booking' <=> booking_id NULL
 *
 * PERINGATAN PENTING:
 * Invarian ini hanya ditegakkan lewat event model Eloquent (saving).
 * Operasi insert massal via Query Builder (DB::table, insert, upsert) melewatinya (bypass).
 * Oleh karena itu, kode aplikasi wajib memakai CourtSlot::create(), CourtSlot::save(),
 * atau relasi Eloquent agar invarian bisnis selalu tervalidasi.
 */
#[Fillable([
    'court_id',
    'booking_id',
    'booking_date',
    'start_time',
    'end_time',
    'block_type',
    'note',
    'locked_until',
])]
class CourtSlot extends Model
{
    /** @use HasFactory<CourtSlotFactory> */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'block_type' => 'booking',
    ];

    /**
     * Boot model events to enforce business invariants (D1).
     */
    protected static function booted(): void
    {
        static::saving(function (CourtSlot $slot) {
            $blockTypeValue = $slot->block_type instanceof SlotBlockType
                ? $slot->block_type->value
                : (string) $slot->block_type;

            if ($blockTypeValue === SlotBlockType::Booking->value && $slot->booking_id === null) {
                throw new InvalidArgumentException("CourtSlot dengan block_type 'booking' wajib memiliki booking_id.");
            }

            if ($blockTypeValue !== SlotBlockType::Booking->value && $slot->booking_id !== null) {
                throw new InvalidArgumentException("CourtSlot non-booking ('{$blockTypeValue}') tidak boleh memiliki booking_id.");
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'block_type' => SlotBlockType::class,
            'locked_until' => 'datetime',
        ];
    }

    /**
     * Get the court this slot belongs to.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Get the booking that holds this slot (if any).
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
