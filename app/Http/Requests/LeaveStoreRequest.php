<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveStoreRequest extends FormRequest
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
     * Aturan validasi untuk permintaan pembuatan data leave.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // user_id wajib ada dan harus ada di tabel users
            'start_date' => 'required|date|after_or_equal:today', // Tanggal mulai wajib, harus berupa tanggal, dan tidak boleh sebelum hari ini
            'end_date' => 'required|date|after_or_equal:start_date', // Tanggal selesai wajib, harus berupa tanggal, dan tidak boleh sebelum tanggal mulai
            'reason' => 'required|in:sick,personal,other', // Alasan wajib, hanya boleh sick, personal, atau other
            'status' => 'required|in:disetujui,ditolak,menunggu konfirmasi', // Status wajib, hanya boleh disetujui, ditolak, atau menunggu konfirmasi
            'user_type' => 'required|in:student,teacher', // Tipe user wajib, hanya boleh student atau teacher
            'deduction_amount' => 'nullable|numeric|min:0', // Nominal potongan opsional, harus numerik dan tidak boleh negatif
        ];
    }
}
