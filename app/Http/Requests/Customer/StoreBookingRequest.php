<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id' => ['required', 'integer', 'exists:travel_packages,id'],
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'travelers' => ['required', 'array', 'min:1'],
            'travelers.*.trav_fn' => ['required', 'string', 'max:100'],
            'travelers.*.trav_mn' => ['nullable', 'string', 'max:100'],
            'travelers.*.trav_ln' => ['required', 'string', 'max:100'],
            'travelers.*.trav_birthdate' => ['required', 'date', 'before:today'],
            'travelers.*.gender' => ['required', 'in:male,female,other'],
            'travelers.*.passport_no' => ['nullable', 'string', 'max:50'],
            'travelers.*.nationality' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_id.required' => 'Package selection is required.',
            'package_id.exists' => 'Selected package does not exist.',
            'travel_date.required' => 'Travel date is required.',
            'travel_date.after_or_equal' => 'Travel date must be today or in the future.',
            'travelers.required' => 'At least one traveler is required.',
            'travelers.min' => 'At least one traveler is required.',
        ];
    }
}
