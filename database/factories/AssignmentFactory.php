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
        // Cek dan buat data jika tidak tersedia
        $student = Student::count() > 0 ? Student::inRandomOrder()->first() : Student::factory()->create();
        $classRoom = ClassRoom::count() > 0 ? ClassRoom::inRandomOrder()->first() : ClassRoom::factory()->create();
        $teacher = Teacher::count() > 0 ? Teacher::inRandomOrder()->first() : Teacher::factory()->create();
        $material = Material::count() > 0 ? Material::inRandomOrder()->first() : Material::factory()->create();

        return [
            'student_id' => $student->id,
            'class_room_id' => $classRoom->id,
            'teacher_id' => $teacher->id,
            'material_id' => $material->id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'due_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['Belum Terselesaikan', 'Terselesaikan', 'Ditolak', 'Dalam Pengajuan']),
        ];
    }
}
