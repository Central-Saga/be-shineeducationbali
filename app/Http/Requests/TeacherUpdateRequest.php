<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'subject_id' => ['sometimes', 'integer', 'exists:subjects,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'employee_type' => ['sometimes', 'in:parttime,fulltime,freelance'],
            'monthly_salary' => ['sometimes', 'numeric', 'min:0', 'max:99999999.99'],
            'status' => ['sometimes', 'in:Aktif,Non Aktif'],
        ];
    }
}