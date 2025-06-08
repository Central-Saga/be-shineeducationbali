<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionDetailUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'sometimes|exists:transactions,id',
            'program_id' => 'sometimes|nullable|exists:programs,id',
            'leave_id' => 'sometimes|nullable|exists:leaves,id',
            'desc' => 'sometimes|string|max:150',
            'amount' => 'sometimes|numeric|min:0',
            'type' => 'sometimes|in:Salary,Program_Payment,Deduction,Expenditure',
            'session_count' => 'sometimes|integer|min:0',
        ];
    }
}
