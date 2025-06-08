<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 transaksi dengan status Paid dan metode Transfer
        Transaction::factory()
            ->count(10)
            ->paid()
            ->transfer()
            ->create();

        // Buat 5 transaksi dengan status Paid dan metode Cash
        Transaction::factory()
            ->count(5)
            ->paid()
            ->cash()
            ->create();

        // Buat 5 transaksi dengan status Unpaid dan metode Transfer
        Transaction::factory()
            ->count(5)
            ->unpaid()
            ->transfer()
            ->create();

        // Buat 5 transaksi dengan status Unpaid dan metode Cash
        Transaction::factory()
            ->count(5)
            ->unpaid()
            ->cash()
            ->create();
    }
}
