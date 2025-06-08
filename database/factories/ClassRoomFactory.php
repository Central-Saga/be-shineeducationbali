<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Cek apakah ada data Program dan Teacher
        $program = Program::count() > 0 ? Program::inRandomOrder()->first() : Program::factory()->create();
        $teacher = Teacher::count() > 0 ? Teacher::inRandomOrder()->first() : Teacher::factory()->create();

        return [
            'program_id' => $program->id,
            'teacher_id' => $teacher->id,
            'class_room_name' => fake()->word(),
            'capacity' => fake()->numberBetween(1, 100),
            'status' => fake()->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
