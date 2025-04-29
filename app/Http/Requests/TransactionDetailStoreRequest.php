<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionDetailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|exists:transactions,id',
            'program_id' => 'nullable|exists:programs,id',
            'leave_id' => 'nullable|exists:leaves,id',
            'desc' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:Salary,Program_Payment,Deduction,Expenditure',
            'session_count' => 'required|integer|min:0',
        ];
    }
}
