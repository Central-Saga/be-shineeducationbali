<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    public function definition(): array
    {
        $banks = ['BCA', 'BRI', 'BNI', 'Mandiri', 'CIMB Niaga', 'BTN', 'Permata', 'Danamon'];
        $accountType = $this->faker->randomElement(['Receipt', 'Expenditure']);
        $employees = ['Budi Santoso', 'Ani Rahayu', 'Dedi Pratama', 'Siti Aisyah'];
        $accountHolder = $accountType === 'Receipt'
            ? $this->faker->randomElement($employees)
            : 'Shine Education Bali';

        return [
            'bank_name' => $this->faker->randomElement($banks),
            'account_number' => $this->faker->numerify('##########'),
            'account_holder' => $accountHolder,
            'account_type' => $accountType,
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Aktif',
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Non Aktif',
            ];
        });
    }

    public function receipt()
    {
        return $this->state(function (array $attributes) {
            $employees = ['Budi Santoso', 'Ani Rahayu', 'Dedi Pratama', 'Siti Aisyah'];
            return [
                'account_type' => 'Receipt',
                'account_holder' => $this->faker->randomElement($employees),
            ];
        });
    }

    public function expenditure()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_type' => 'Expenditure',
                'account_holder' => 'Shine Education Bali',
            ];
        });
    }
}
