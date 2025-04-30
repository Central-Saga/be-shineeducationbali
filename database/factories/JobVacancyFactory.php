<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use App\Models\Subject;
use App\Models\JobVacancyStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobVacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(), // Membuat subject baru atau mengambil subject yang ada
            'title' => $this->faker->jobTitle(), // Contoh: "Senior Software Engineer"
            'description' => $this->faker->paragraph(3), // Deskripsi acak 3 paragraf
            'salary' => $this->faker->randomFloat(2, 1000000, 20000000), // Gaji acak antara 5 juta - 20 juta
            'application_deadline' => $this->faker->dateTimeBetween('now', '+1 month'), // Deadline 1 bulan dari sekarang
            'status' => $this->faker->randomElement(JobVacancyStatus::cases())->value, // Pilih acak antara Open/Closed
        ];
    }
}
