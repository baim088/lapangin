<?php

namespace App\Http\Requests\Operator;

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
            'name' => ['required', 'string', 'max:255'],
            'sport_category' => ['required', 'string', Rule::enum(SportCategory::class)],
            'surface_type' => ['required', 'string', 'max:255'],
            'price_regular' => ['required', 'integer', 'min:1000'],
            'price_prime' => ['required', 'integer', 'min:1000'],
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
            'name.required' => 'Nama lapangan wajib diisi.',
            'sport_category.required' => 'Kategori cabang olahraga wajib dipilih.',
            'surface_type.required' => 'Jenis lantai/permukaan wajib diisi.',
            'price_regular.required' => 'Harga reguler wajib diisi.',
            'price_regular.min' => 'Harga reguler minimal Rp 1.000.',
            'price_prime.required' => 'Harga prime time wajib diisi.',
            'price_prime.min' => 'Harga prime time minimal Rp 1.000.',
        ];
    }
}
