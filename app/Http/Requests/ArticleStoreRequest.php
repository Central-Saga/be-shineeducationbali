<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
     * Aturan validasi untuk permintaan pembuatan data article.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // user_id wajib ada dan harus ada di tabel users
            'title' => 'required|string|max:150', // Judul wajib, harus string, maksimal 150 karakter
            'content' => 'required|string|max:65535', // Konten wajib, harus string, maksimal 65535 karakter
            'publication_date' => 'required|date', // Tanggal publikasi wajib, harus berupa tanggal
        ];
    }
}
