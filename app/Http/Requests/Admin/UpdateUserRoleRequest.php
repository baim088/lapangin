<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
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
            'role' => ['required', 'string', Rule::enum(UserRole::class)],
            'venue_id' => [
                'nullable',
                Rule::requiredIf($this->input('role') === UserRole::Operator->value),
                'exists:venues,id',
            ],
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
            'role.required' => 'Peran pengguna wajib dipilih.',
            'venue_id.required' => 'Operator wajib ditugaskan ke salah satu venue.',
            'venue_id.required_if' => 'Operator wajib ditugaskan ke salah satu venue.',
            'venue_id.exists' => 'Venue yang dipilih tidak ditemukan.',
        ];
    }
}
