<?php

namespace Database\Factories;

use App\Models\ClassType;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAttendance>
 */
class StudentAttendanceFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = StudentAttendance::class;

    /**
     * Definisi state default untuk factory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => function () {
                return ClassType::inRandomOrder()->first()->id ?? ClassType::factory()->create()->id;
            },
            'student_id' => function () {
                return Student::inRandomOrder()->first()->id ?? Student::factory()->create()->id;
            },
            'teacher_id' => function () {
                return Teacher::inRandomOrder()->first()->id ?? Teacher::factory()->create()->id;
            },
            'attendance_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'status' => $this->faker->randomElement(['present', 'absent']),
        ];
    }
}
