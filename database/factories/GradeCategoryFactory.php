<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GradeCategory>
 */
class GradeCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(), // Membuat atau mengambil Program secara otomatis
            'category_name' => $this->faker->randomElement(['Good', 'Very Good', 'Excellent', 'Needs Improvement']), // Contoh kategori
            'description' => $this->faker->paragraph(), // Deskripsi acak
        ];
    }
}
