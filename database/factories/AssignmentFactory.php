<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Material;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::all()->random()->id,
            'class_room_id' => ClassRoom::all()->random()->id,
            'teacher_id' => Teacher::all()->random()->id,
            'material_id' => Material::all()->random()->id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'due_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['Belum Terselesaikan', 'Terselesaikan', 'Ditolak', 'Dalam Pengajuan']),
        ];
    }
}
