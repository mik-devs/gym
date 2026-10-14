<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
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
            'membership_id' => ['required', 'exists:memberships,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'method' => ['required', 'in:cash,card,gcash,bank_transfer'],
            'status' => ['required', 'in:pending,paid,refunded,overdue'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'invoice_no' => [
                'nullable',
                'string',
                Rule::unique('payments', 'invoice_no')->ignore($this->route('payment')),
            ],
        ];
    }
}
