<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'program_id',
        'leave_id',
        'desc',
        'amount',
        'type',
        'session_count',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'type' => 'string',
    ];

    // Relasi dengan Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi dengan Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relasi dengan Leave
    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
