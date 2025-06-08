<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(), // Membuat atau mengambil ID dari model Program
            'user_id' => User::factory(),       // Membuat atau mengambil ID dari model User
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Tanggal mulai dalam 1 tahun terakhir
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),   // Tanggal selesai dalam 1 tahun ke depan
            'registration_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'), // Tanggal registrasi dalam 2 tahun terakhir
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']), // Memilih status secara acak antara Aktif dan Non Aktif
        ];
    }
}
