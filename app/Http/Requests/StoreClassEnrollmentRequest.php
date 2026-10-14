<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassEnrollmentRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
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
            'class_schedule_id' => ['required', 'exists:class_schedules,id'],
            'member_id' => ['required', 'exists:members,id'],
            'status' => ['required', 'in:enrolled,cancelled,waitlist,attended'],
        ];
    }
}
