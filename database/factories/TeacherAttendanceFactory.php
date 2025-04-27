<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherAttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeacherAttendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attendanceDate = $this->faker->dateTimeBetween('-3 months', 'now');
        $checkIn = (clone $attendanceDate)->modify('+' . $this->faker->numberBetween(8, 10) . ' hours');
        $checkOut = (clone $checkIn)->modify('+' . $this->faker->numberBetween(1, 4) . ' hours');
        
        return [
            'class_id' => ClassRoom::factory(),
            'teacher_id' => Teacher::factory(),
            'attendance_date' => $attendanceDate,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ];
    }

    /**
     * Indicate that the teacher has not checked out.
     *
     * @return static
     */
    public function noCheckout(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'check_out' => null,
            ];
        });
    }

    /**
     * Indicate that the teacher has not attended yet.
     *
     * @return static
     */
    public function notAttended(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'check_in' => null,
                'check_out' => null,
            ];
        });
    }
}
