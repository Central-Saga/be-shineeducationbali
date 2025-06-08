<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'bank_account_id',
        'transaction_date',
        'total_amount',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'total_amount' => 'decimal:2',
        'payment_method' => 'string',
        'status' => 'string',
    ];

    // Relasi dengan Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi dengan Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Relasi dengan BankAccount
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
