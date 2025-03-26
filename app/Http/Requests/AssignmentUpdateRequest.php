<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentUpdateRequest extends FormRequest
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
            'student_id' => ['sometimes', 'exists:students,id'],
            'class_room_id' => ['sometimes', 'exists:class_rooms,id'],
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'material_id' => ['sometimes', 'exists:materials,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'in:pending,submitted,graded'],
        ];
    }
}
