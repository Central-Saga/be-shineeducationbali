<?php

namespace App\Http\Requests;

use App\Models\TeacherAttendance;
use Illuminate\Foundation\Http\FormRequest;

class TeacherAttendanceStoreRequest extends FormRequest
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
            'teacher_id' => 'required|integer|exists:teachers,id',
            'class_rooms_id' => 'required|integer|exists:class_rooms,id',
            'attendance_date' => 'required|date',
            'check_in' => 'required|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'required|in:' . TeacherAttendance::STATUS_PRESENT . ',' . TeacherAttendance::STATUS_ABSENT,
        ];
    }
}
