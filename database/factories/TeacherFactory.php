<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_id' => \App\Models\Subject::factory(), // Assuming a Subject model exists
            'user_id' => \App\Models\User::factory(), // Assuming a User model exists
            'employee_type' => $this->faker->randomElement(['parttime', 'fulltime', 'freelance']),
            'monthly_salary' => $this->faker->randomFloat(2, 2000, 10000), // Random salary between 2000 and 10000
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
