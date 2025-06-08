<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'account_type' => 'required|in:Receipt,Expenditure',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
