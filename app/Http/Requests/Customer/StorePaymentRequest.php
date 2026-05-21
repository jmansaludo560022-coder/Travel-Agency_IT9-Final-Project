<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,credit_card,bank_transfer,installment'],
            'payment_date' => ['required', 'date'],
            'payment_ref_no' => ['required', 'string', 'max:100', 'unique:payments,payment_ref_no'],
            'schedules' => ['required_if:payment_method,installment', 'nullable', 'array'],
            'schedules.*.due_date' => ['required_if:payment_method,installment', 'nullable', 'date', 'after_or_equal:today'],
            'schedules.*.amount_due' => ['required_if:payment_method,installment', 'nullable', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'Booking is required.',
            'amount_paid.required' => 'Payment amount is required.',
            'amount_paid.min' => 'Payment amount must be greater than 0.',
            'payment_method.required' => 'Payment method is required.',
            'payment_date.required' => 'Payment date is required.',
            'payment_ref_no.required' => 'Payment reference number is required.',
            'payment_ref_no.unique' => 'This payment reference number already exists.',
            'schedules.required_if' => 'Installment schedules are required for installment payments.',
        ];
    }
}
