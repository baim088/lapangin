<?php

namespace App\Http\Requests\Owner;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'name' => ['required', 'string', 'max:255'],
            'sport_category' => ['required', 'string', Rule::enum(SportCategory::class)],
            'surface_type' => ['required', 'string', 'max:255'],
            'price_regular' => ['required', 'integer', 'min:1000'],
            'price_prime' => ['required', 'integer', 'min:1000'],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'prime_start_time' => ['required', 'date_format:H:i', 'after_or_equal:open_time', 'before:close_time'],
            'prime_end_time' => ['required', 'date_format:H:i', 'after:prime_start_time', 'before_or_equal:close_time'],
            'status' => ['nullable', 'string', Rule::enum(CourtStatus::class)],
            'specs' => ['nullable', 'array'],
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
            'venue_id.required' => 'Venue wajib dipilih.',
            'name.required' => 'Nama lapangan wajib diisi.',
            'sport_category.required' => 'Kategori cabang olahraga wajib dipilih.',
            'surface_type.required' => 'Jenis lantai/permukaan wajib diisi.',
            'price_regular.required' => 'Harga jam normal wajib diisi.',
            'price_regular.min' => 'Harga jam normal minimal Rp 1.000.',
            'price_prime.required' => 'Harga jam prime wajib diisi.',
            'price_prime.min' => 'Harga jam prime minimal Rp 1.000.',
            'open_time.required' => 'Jam buka operasional wajib diisi.',
            'close_time.required' => 'Jam tutup operasional wajib diisi.',
            'close_time.after' => 'Jam tutup harus setelah jam buka operasional.',
            'prime_start_time.required' => 'Jam mulai prime time wajib diisi.',
            'prime_start_time.after_or_equal' => 'Jam mulai prime harus di dalam jam operasional.',
            'prime_start_time.before' => 'Jam mulai prime harus sebelum jam tutup.',
            'prime_end_time.required' => 'Jam selesai prime time wajib diisi.',
            'prime_end_time.after' => 'Jam selesai prime harus setelah jam mulai prime.',
            'prime_end_time.before_or_equal' => 'Jam selesai prime tidak boleh melewati jam tutup.',
        ];
    }
}
