<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentQuotaUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'sometimes|integer|exists:students,id',
            'program_id' => 'sometimes|integer|exists:programs,id',
            'period' => 'sometimes|date',
            'sessions_paid' => 'sometimes|integer|min:0',
            'sessions_used' => 'sometimes|integer|min:0',
            'sessions_remaining' => 'sometimes|integer',
            'sessions_accumulated' => 'sometimes|integer|min:0',
        ];
    }
}
