<?php

namespace Database\Factories;

use App\Models\ClassType;
use App\Models\EducationLevel;
use App\Models\MeetingFrequency;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'education_level_id' => $this->faker->randomElement(EducationLevel::pluck('id')->toArray()),
            'subject_id' => $this->faker->randomElement(Subject::pluck('id')->toArray()),
            'class_type_id' => $this->faker->randomElement(ClassType::pluck('id')->toArray()),
            'meeting_frequency_id' => $this->faker->randomElement(MeetingFrequency::pluck('id')->toArray()),
            'program_name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'sku' => $this->faker->unique()->regexify('[A-Z0-9]{20}'),
            'freelance_rate_per_session' => $this->faker->randomFloat(2, 100, 1000),
            'min_parttime_sessions' => $this->faker->numberBetween(1, 10),
            'overtime_rate_per_session' => $this->faker->randomFloat(2, 100, 1000),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
