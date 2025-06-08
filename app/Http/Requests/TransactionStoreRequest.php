<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:transfer,cash',
            'status' => 'required|in:paid,unpaid',
        ];
    }
}
