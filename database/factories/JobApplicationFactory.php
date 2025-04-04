<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobApplicationStatus;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vacancy_id' => JobVacancy::factory(), // Membuat atau mengambil job vacancy
            'user_id' => User::factory(), // Membuat atau mengambil user
            'application_date' => $this->faker->dateTimeBetween('-1 month', 'now'), // Tanggal aplikasi dalam 1 bulan terakhir
            'status' => $this->faker->randomElement(JobApplicationStatus::cases())->value, // Pilih acak dari enum
        ];
    }
}
