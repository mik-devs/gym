<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_date' => ['nullable', 'date'],
            'condition' => ['required', 'in:new,good,fair,damaged'],
            'status' => ['required', 'in:available,in_use,maintenance,retired'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
