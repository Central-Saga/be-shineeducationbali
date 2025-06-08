<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentQuotaStoreRequest extends FormRequest
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
            'student_id' => 'required|integer|exists:students,id',
            'program_id' => 'required|integer|exists:programs,id',
            'period' => 'required|date',
            'sessions_paid' => 'required|integer|min:0',
            'sessions_used' => 'nullable|integer|min:0',
            'sessions_remaining' => 'nullable|integer',
            'sessions_accumulated' => 'nullable|integer|min:0',
        ];
    }
}
