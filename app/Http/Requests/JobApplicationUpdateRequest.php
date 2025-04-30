<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah sesuai kebutuhan otorisasi
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vacancy_id' => 'sometimes|exists:job_vacancies,id',
            'user_id' => 'sometimes|exists:users,id',
            'application_date' => 'sometimes|date',
            'status' => 'sometimes|in:Pending,Reviewed,Accepted,Rejected',
        ];
    }
}
