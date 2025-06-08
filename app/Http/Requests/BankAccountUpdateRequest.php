<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'sometimes|string|max:50',
            'account_number' => 'sometimes|string|max:50',
            'account_holder' => 'sometimes|string|max:100',
            'account_type' => 'sometimes|in:Receipt,Expenditure',
            'status' => 'sometimes|in:Aktif,Non Aktif',
        ];
    }
}
