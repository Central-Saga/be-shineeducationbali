<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationStoreRequest extends FormRequest
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
     * Aturan validasi untuk permintaan pembuatan data notification.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // user_id wajib ada dan harus ada di tabel users
            'type' => 'required|in:Payment,Leave,Grade', // Tipe wajib, hanya boleh Payment, Leave, atau Grade
            'message' => 'required|string|max:65535', // Pesan wajib, harus string, maksimal 65535 karakter
            'sent_date' => 'required|date', // Tanggal pengiriman wajib, harus berupa tanggal
            'status' => 'required|in:read,unread', // Status wajib, hanya boleh read atau unread
        ];
    }
}
