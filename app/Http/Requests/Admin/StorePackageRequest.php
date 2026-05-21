<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_name'    => ['required', 'string', 'max:255'],
            'destination_id'  => ['required', 'integer', 'exists:destinations,id'],
            'package_type'    => ['nullable', 'string', 'max:100'],
            'description'     => ['nullable', 'string'],
            'inclusions'      => ['nullable', 'string'],
            'exclusions'      => ['nullable', 'string'],
            'itinerary'       => ['nullable', 'string'],
            'package_cost'    => ['required', 'numeric', 'min:0'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'slots_available' => ['required', 'integer', 'min:0'],
            'image'           => ['nullable', 'string', 'max:500'],
            'is_visible'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_name.required'    => 'Package name is required.',
            'destination_id.required'  => 'Destination is required.',
            'destination_id.exists'    => 'Selected destination does not exist.',
            'package_cost.required'    => 'Package cost is required.',
            'package_cost.min'         => 'Package cost must be at least 0.',
            'start_date.required'      => 'Start date is required.',
            'end_date.required'        => 'End date is required.',
            'end_date.after_or_equal'  => 'End date must be on or after start date.',
            'slots_available.required' => 'Slots available is required.',
            'slots_available.min'      => 'Slots available must be at least 0.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize is_visible checkbox — absent means false
        $this->merge([
            'is_visible' => $this->boolean('is_visible'),
        ]);

        // Normalize empty strings to null so nullable rules work cleanly
        foreach (['inclusions', 'exclusions', 'itinerary', 'package_type', 'description', 'image'] as $field) {
            if ($this->has($field) && trim((string) $this->input($field)) === '') {
                $this->merge([$field => null]);
            }
        }
    }
}
