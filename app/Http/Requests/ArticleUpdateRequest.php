<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
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
     * Aturan validasi untuk permintaan pembaruan data article.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id', // user_id opsional, jika ada harus ada di tabel users
            'title' => 'sometimes|string|max:150', // Judul opsional, jika ada harus string, maksimal 150 karakter
            'content' => 'sometimes|string|max:65535', // Konten opsional, jika ada harus string, maksimal 65535 karakter
            'publication_date' => 'sometimes|date', // Tanggal publikasi opsional, jika ada harus berupa tanggal
        ];
    }
}
