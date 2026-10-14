<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassEnrollmentRequest extends FormRequest
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
        $enrollment = $this->route('enrollment')
            ?? $this->route('classEnrollment')
            ?? $this->route('class_enrollment');

        return [
            'class_schedule_id' => [
                'required',
                'exists:class_schedules,id',
                Rule::unique('class_enrollments', 'class_schedule_id')
                    ->where('member_id', $this->input('member_id'))
                    ->ignore($enrollment),
            ],
            'member_id' => [
                'required',
                'exists:members,id',
                Rule::unique('class_enrollments', 'member_id')
                    ->where('class_schedule_id', $this->input('class_schedule_id'))
                    ->ignore($enrollment),
            ],
            'status' => ['required', 'in:enrolled,cancelled,waitlist,attended'],
        ];
    }

    /**
     * Get custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'class_schedule_id.unique' => 'This member is already enrolled in this schedule.',
            'member_id.unique' => 'This member is already enrolled in this schedule.',
        ];
    }
}
