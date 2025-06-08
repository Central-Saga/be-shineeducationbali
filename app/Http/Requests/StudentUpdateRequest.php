<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Ubah sesuai kebutuhan otorisasi Anda
    }

    /**
     * Aturan validasi untuk permintaan pembaruan student.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'program_id' => ['sometimes', 'integer', 'exists:programs,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'start_date' => ['sometimes', 'date', 'before_or_equal:end_date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'registration_date' => ['sometimes', 'date', 'before_or_equal:today'],
            'status' => ['sometimes', Rule::in(['Aktif', 'Non Aktif'])],
        ];
    }
}
