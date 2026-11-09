<?php

namespace App\Http\Requests\Booking;

use App\Enums\PaymentMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('customer_phone')) {
            $this->merge([
                'customer_phone' => preg_replace('/[^0-9+]/', '', (string) $this->customer_phone),
            ]);
        }

        if ($this->has('payment_method')) {
            $method = match ($this->payment_method) {
                'bank_transfer' => PaymentMethod::ManualTransfer->value,
                'va' => PaymentMethod::VirtualAccount->value,
                'ewallet' => PaymentMethod::EWallet->value,
                default => $this->payment_method,
            };
            $this->merge(['payment_method' => $method]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*' => ['required', 'string'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', Rule::enum(PaymentMethod::class)],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh sebelum hari ini.',
            'slots.required' => 'Pilih minimal satu slot jam bermain.',
            'slots.min' => 'Pilih minimal satu slot jam bermain.',
            'customer_name.required' => 'Nama lengkap pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp pemesan wajib diisi.',
            'customer_phone.regex' => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'customer_email.required' => 'Email pemesan wajib diisi.',
            'customer_email.email' => 'Format email tidak valid.',
            'payment_method.required' => 'Pilih salah satu metode pembayaran.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak valid.',
        ];
    }
}
