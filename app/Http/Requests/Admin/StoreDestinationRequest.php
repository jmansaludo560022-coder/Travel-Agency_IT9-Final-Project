<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $destinationId = $this->route('destination');

        return [
            'city_name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('destinations', 'city_name')
                    ->where('country', $this->country)
                    ->ignore($destinationId),
            ],
            'country' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2MB max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'city_name.required' => 'City name is required.',
            'city_name.max' => 'City name must not exceed 150 characters.',
            'city_name.unique' => 'A destination with this city and country combination already exists.',
            'country.required' => 'Country is required.',
            'country.max' => 'Country must not exceed 100 characters.',
            'image.max' => 'Image path must not exceed 255 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Check if the failure is due to unique constraint violation
        if ($validator->errors()->has('city_name')) {
            $errors = $validator->errors()->get('city_name');
            foreach ($errors as $error) {
                if (str_contains($error, 'already exists')) {
                    throw new HttpResponseException(
                        response()->json([
                            'error' => [
                                'code' => 'DUPLICATE_RECORD',
                                'message' => 'A destination with this city and country combination already exists.',
                                'status' => 409,
                                'details' => $validator->errors(),
                            ]
                        ], 409)
                    );
                }
            }
        }

        parent::failedValidation($validator);
    }
}
