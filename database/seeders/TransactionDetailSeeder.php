<?php

namespace Database\Seeders;

use App\Models\TransactionDetail;
use Illuminate\Database\Seeder;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 detail transaksi dengan tipe Salary
        TransactionDetail::factory()
            ->count(10)
            ->salary()
            ->create();

        // Buat 10 detail transaksi dengan tipe Program_Payment
        TransactionDetail::factory()
            ->count(10)
            ->programPayment()
            ->create();

        // Buat 5 detail transaksi dengan tipe Deduction
        TransactionDetail::factory()
            ->count(5)
            ->deduction()
            ->create();

        // Buat 5 detail transaksi dengan tipe Expenditure
        TransactionDetail::factory()
            ->count(5)
            ->expenditure()
            ->create();
    }
}
