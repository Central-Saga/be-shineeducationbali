<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationUpdateRequest extends FormRequest
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
     * Aturan validasi untuk permintaan pembaruan data notification.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id', // user_id opsional, jika ada harus ada di tabel users
            'type' => 'sometimes|in:Payment,Leave,Grade', // Tipe opsional, jika ada hanya boleh Payment, Leave, atau Grade
            'message' => 'sometimes|string|max:65535', // Pesan opsional, jika ada harus string, maksimal 65535 karakter
            'sent_date' => 'sometimes|date', // Tanggal pengiriman opsional, jika ada harus berupa tanggal
            'status' => 'sometimes|in:read,unread', // Status opsional, jika ada hanya boleh read atau unread
        ];
    }
}
