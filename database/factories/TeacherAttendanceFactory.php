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
        $checkIn = $this->faker->time('H:i:s');
        $checkOut = $this->faker->time('H:i:s', $checkIn);
        $statuses = ['present', 'absent'];
        
        return [
            'class_rooms_id' => ClassRoom::factory(),
            'teacher_id' => Teacher::factory(),
            'attendance_date' => $attendanceDate,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => $this->faker->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the teacher is present.
     *
     * @return static
     */
    public function present(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TeacherAttendance::STATUS_PRESENT,
            ];
        });
    }

    /**
     * Indicate that the teacher is absent.
     *
     * @return static
     */
    public function absent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'check_in' => null,
                'check_out' => null,
                'status' => TeacherAttendance::STATUS_ABSENT,
            ];
        });
    }
}
