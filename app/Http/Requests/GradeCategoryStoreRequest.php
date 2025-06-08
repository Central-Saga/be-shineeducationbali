<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah sesuai kebutuhan otorisasi, misalnya menggunakan Gate atau policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'category_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('grade_categories')->where(function ($query) {
                    return $query->where('program_id', $this->program_id);
                }),
            ],
            'description' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
