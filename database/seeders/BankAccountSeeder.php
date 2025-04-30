<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat 20 akun tipe Receipt (bank karyawan)
        BankAccount::factory()
            ->count(15)
            ->receipt()
            ->active()
            ->create();


        // Buat 5 akun tipe Receipt (bank karyawan) dengan status Non Aktif
        BankAccount::factory()
            ->count(5)
            ->receipt()
            ->inactive()
            ->create();


        // Buat 3 akun tipe Expenditure (bank Shine Education Bali)
        BankAccount::factory()
            ->count(3)
            ->expenditure()
            ->active()
            ->create();
    }
}
