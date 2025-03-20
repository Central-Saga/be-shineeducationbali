<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreRequest extends FormRequest
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
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'employee_type' => ['required', 'in:parttime,fulltime,freelance'],
            'monthly_salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'status' => ['required', 'in:Aktif,Non Aktif'],
        ];
    }
}