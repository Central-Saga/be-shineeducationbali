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
            'title' => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'string'],
            'due_date' => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'status' => ['sometimes', 'string', 'in:Dalam Pengajuan,Terselesaikan,Ditolak,Belum Terselesaikan'],
        ];
    }
}
