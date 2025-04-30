<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'student_id' => 'sometimes|exists:students,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'bank_account_id' => 'sometimes|exists:bank_accounts,id',
            'transaction_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:transfer,cash',
            'status' => 'sometimes|in:paid,unpaid',
        ];
    }
}
