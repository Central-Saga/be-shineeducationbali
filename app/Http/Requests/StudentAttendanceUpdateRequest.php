<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAttendanceUpdateRequest extends FormRequest
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
            'class_rooms_id' => 'sometimes|required|integer|exists:class_rooms,id',
            'student_id' => 'sometimes|required|integer|exists:students,id',
            'teacher_id' => 'sometimes|required|integer|exists:teachers,id',
            'attendance_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|in:present,absent',
        ];
    }
}
