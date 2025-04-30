<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeUpdateRequest extends FormRequest
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
            'student_id' => 'sometimes|required|integer|exists:students,id',
            'class_rooms_id' => 'sometimes|required|integer|exists:class_rooms,id',
            'material_id' => 'sometimes|required|integer|exists:materials,id',
            'assignment_id' => 'sometimes|required|integer|exists:assignments,id',
            'grade_category_id' => 'sometimes|required|integer|exists:grade_categories,id',
            'score' => 'sometimes|required|numeric|between:0,100',
            'input_date' => 'sometimes|required|date',
        ];
    }
}
