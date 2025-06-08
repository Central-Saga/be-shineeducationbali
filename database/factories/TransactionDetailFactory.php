<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Program;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'program_id' => $this->faker->boolean(50) ? Program::factory() : null, // 50% kemungkinan ada program
            'leave_id' => $this->faker->boolean(30) ? Leave::factory() : null, // 30% kemungkinan ada leave
            'desc' => $this->faker->sentence(5),
            'amount' => $this->faker->randomFloat(2, 100000, 5000000), // Antara 100 ribu hingga 5 juta
            'type' => $this->faker->randomElement(['Salary', 'Program_Payment', 'Deduction', 'Expenditure']),
            'session_count' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function salary()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Salary',
                'desc' => 'Pembayaran gaji bulanan',
                'program_id' => null,
                'leave_id' => null,
            ];
        });
    }

    public function programPayment()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Program_Payment',
                'desc' => 'Pembayaran program belajar',
                'program_id' => Program::factory(),
                'leave_id' => null,
            ];
        });
    }

    public function deduction()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Deduction',
                'desc' => 'Potongan karena cuti',
                'program_id' => null,
                'leave_id' => Leave::factory(),
            ];
        });
    }

    public function expenditure()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Expenditure',
                'desc' => 'Pengeluaran untuk Shine Education Bali',
                'program_id' => null,
                'leave_id' => null,
            ];
        });
    }
}
