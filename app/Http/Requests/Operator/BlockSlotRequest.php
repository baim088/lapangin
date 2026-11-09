<?php

namespace App\Http\Requests\Operator;

use App\Enums\SlotBlockType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlockSlotRequest extends FormRequest
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
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string'],
            'note' => ['required', 'string', 'min:3', 'max:255'],
            'block_type' => ['nullable', 'string', Rule::in([
                SlotBlockType::Maintenance->value,
                SlotBlockType::Event->value,
            ])],
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
            'court_id.required' => 'Lapangan wajib dipilih.',
            'court_id.exists' => 'Lapangan tidak ditemukan.',
            'booking_date.required' => 'Tanggal wajib dipilih.',
            'booking_date.after_or_equal' => 'Tanggal tidak boleh di masa lampau.',
            'start_time.required' => 'Jam slot wajib dipilih.',
            'note.required' => 'Catatan alasan pemblokiran wajib diisi.',
            'note.min' => 'Catatan alasan minimal 3 karakter.',
            'note.max' => 'Catatan alasan maksimal 255 karakter.',
            'block_type.in' => 'Tipe pemblokiran tidak valid.',
        ];
    }
}
