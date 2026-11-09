<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVenueRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'prime_start_time' => ['required', 'date_format:H:i'],
            'prime_end_time' => ['required', 'date_format:H:i', 'after:prime_start_time'],
            'slot_lock_duration_minutes' => ['required', 'integer', 'min:5', 'max:60'],
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
            'name.required' => 'Nama venue wajib diisi.',
            'address.required' => 'Alamat venue wajib diisi.',
            'phone.required' => 'Nomor telepon venue wajib diisi.',
            'open_time.required' => 'Jam buka operasional wajib diisi.',
            'close_time.required' => 'Jam tutup operasional wajib diisi.',
            'close_time.after' => 'Jam tutup operasional harus setelah jam buka.',
            'prime_start_time.required' => 'Awal jam prime time wajib diisi.',
            'prime_end_time.required' => 'Akhir jam prime time wajib diisi.',
            'prime_end_time.after' => 'Akhir jam prime time harus setelah jam mulai prime time.',
            'slot_lock_duration_minutes.required' => 'Durasi kunci slot wajib diisi.',
            'slot_lock_duration_minutes.min' => 'Durasi kunci slot minimal 5 menit.',
            'slot_lock_duration_minutes.max' => 'Durasi kunci slot maksimal 60 menit.',
        ];
    }
}
