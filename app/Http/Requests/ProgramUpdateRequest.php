<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramUpdateRequest extends FormRequest
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
            'education_level_id' => 'sometimes|exists:education_levels,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'class_type_id' => 'sometimes|exists:class_types,id',
            'meeting_frequency_id' => 'sometimes|exists:meeting_frequencies,id',
            'program_name' => 'sometimes|string|max:150',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'sku' => 'sometimes|string|max:20',
            'freelance_rate_per_session' => 'sometimes|numeric|min:0',
            'min_parttime_sessions' => 'sometimes|integer|min:0',
            'overtime_rate_per_session' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:Aktif,Non Aktif',
        ];
    }
}
