<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => function () {
                return Student::inRandomOrder()->first()->id ?? Student::factory()->create()->id;
            },
            'program_id' => function () {
                return Program::inRandomOrder()->first()->id ?? Program::factory()->create()->id;
            },
            'issue_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
