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
        return [
            'program_id' => Program::all()->random()->id,
            'teacher_id' => Teacher::all()->random()->id,
            'class_room_name' => fake()->word(),
            'capacity' => fake()->numberBetween(1, 100),
            'status' => fake()->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
