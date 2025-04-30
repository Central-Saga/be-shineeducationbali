<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveUpdateRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Ubah sesuai kebutuhan otorisasi
    }

    /**
     * Aturan validasi untuk permintaan pembaruan data leave.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id', // user_id opsional, jika ada harus ada di tabel users
            'start_date' => 'sometimes|date|after_or_equal:today', // Tanggal mulai opsional, jika ada harus berupa tanggal dan tidak boleh sebelum hari ini
            'end_date' => 'sometimes|date|after_or_equal:start_date', // Tanggal selesai opsional, jika ada harus berupa tanggal dan tidak boleh sebelum tanggal mulai
            'reason' => 'sometimes|in:sick,personal,other', // Alasan opsional, jika ada hanya boleh sick, personal, atau other
            'status' => 'sometimes|in:disetujui,ditolak,menunggu konfirmasi', // Status opsional, jika ada hanya boleh disetujui, ditolak, atau menunggu konfirmasi
            'user_type' => 'sometimes|in:student,teacher', // Tipe user opsional, jika ada hanya boleh student atau teacher
            'deduction_amount' => 'nullable|numeric|min:0', // Nominal potongan opsional, harus numerik dan tidak boleh negatif
        ];
    }
}
