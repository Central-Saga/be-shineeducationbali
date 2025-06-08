<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\Student;
use App\Models\StudentQuota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentQuota>
 */
class StudentQuotaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentQuota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sessionsPaid = $this->faker->numberBetween(4, 16);
        $sessionsUsed = $this->faker->numberBetween(0, $sessionsPaid);
        $sessionsAccumulated = $this->faker->numberBetween(0, 4);
        $sessionsRemaining = $sessionsPaid + $sessionsAccumulated - $sessionsUsed;

        return [
            'student_id' => Student::factory(),
            'program_id' => Program::factory(),
            'period' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'sessions_paid' => $sessionsPaid,
            'sessions_used' => $sessionsUsed,
            'sessions_remaining' => $sessionsRemaining,
            'sessions_accumulated' => $sessionsAccumulated,
        ];
    }

    /**
     * Indicate that the quota has no remaining sessions.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function depleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'sessions_used' => $attributes['sessions_paid'] + $attributes['sessions_accumulated'],
                'sessions_remaining' => 0,
            ];
        });
    }

    /**
     * Indicate that the quota has many remaining sessions.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withExcessSessions(int $remaining = 10)
    {
        return $this->state(function (array $attributes) use ($remaining) {
            $sessionsPaid = $attributes['sessions_used'] + $remaining;
            return [
                'sessions_paid' => $sessionsPaid,
                'sessions_remaining' => $remaining,
            ];
        });
    }

    /**
     * Indicate that the quota is for a specific student.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forStudent(Student $student)
    {
        return $this->state(function (array $attributes) use ($student) {
            return [
                'student_id' => $student->id,
            ];
        });
    }

    /**
     * Indicate that the quota is for a specific program.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forProgram(Program $program)
    {
        return $this->state(function (array $attributes) use ($program) {
            return [
                'program_id' => $program->id,
            ];
        });
    }
}
