<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentStoreRequest extends FormRequest
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
            'student_id' => ['required', 'exists:students,id'],
            'class_room_id' => ['required', 'exists:class_rooms,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'due_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'status' => ['required', 'string', 'in:Dalam Pengajuan,Terselesaikan,Ditolak,Belum Terselesaikan'],
        ];
    }
}
