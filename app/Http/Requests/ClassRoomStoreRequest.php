<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRoomStoreRequest extends FormRequest
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
            'program_id' => 'required|exists:programs,id',
            'teacher_id' => 'required|exists:teachers,id',
            'class_room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Aktif,Non Aktif',

        ];
    }
}
