<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'teacher_id' => Teacher::factory(),
            'bank_account_id' => BankAccount::factory(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'total_amount' => $this->faker->randomFloat(2, 500000, 10000000), // Antara 500 ribu hingga 10 juta
            'payment_method' => $this->faker->randomElement(['transfer', 'cash']),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
        ];
    }

    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
            ];
        });
    }

    public function unpaid()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'unpaid',
            ];
        });
    }

    public function transfer()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'transfer',
            ];
        });
    }

    public function cash()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'cash',
            ];
        });
    }
}
