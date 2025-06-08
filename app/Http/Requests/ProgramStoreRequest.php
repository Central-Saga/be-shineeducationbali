<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramStoreRequest extends FormRequest
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
            'education_level_id' => 'required|exists:education_levels,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_type_id' => 'required|exists:class_types,id',
            'meeting_frequency_id' => 'required|exists:meeting_frequencies,id',
            'program_name' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:20',
            'freelance_rate_per_session' => 'required|numeric|min:0',
            'min_parttime_sessions' => 'required|integer|min:0',
            'overtime_rate_per_session' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
