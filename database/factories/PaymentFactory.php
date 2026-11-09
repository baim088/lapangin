<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'payment_method' => PaymentMethod::Qris,
            'status' => PaymentStatus::Unpaid,
            'amount' => 150000,
            'proof_path' => null,
            'proof_uploaded_at' => null,
            'provider' => null,
            'external_reference' => null,
            'verified_by' => null,
            'verified_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Paid,
            'verified_at' => now('Asia/Jakarta'),
        ]);
    }
}
