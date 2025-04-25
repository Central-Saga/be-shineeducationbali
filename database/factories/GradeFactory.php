<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\ClassType;
use App\Models\Material;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Definisi state default untuk factory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => function () {
                return Student::inRandomOrder()->first()->id ?? Student::factory()->create()->id;
            },
            'class_id' => function () {
                return ClassType::inRandomOrder()->first()->id ?? ClassType::factory()->create()->id;
            },
            'material_id' => function () {
                return Material::inRandomOrder()->first()->id ?? Material::factory()->create()->id;
            },
            'assignment_id' => function () {
                // Karena model Assignment belum ada, kita akan menggunakan nilai random untuk sementara
                // atau bisa juga menggunakan 1 sebagai nilai default
                return $this->faker->numberBetween(1, 10);
            },
            'grade_category_id' => function () {
                // Karena model GradeCategory belum ada, kita akan menggunakan nilai random untuk sementara
                // atau bisa juga menggunakan 1 sebagai nilai default
                return $this->faker->numberBetween(1, 5);
            },
            'score' => $this->faker->randomFloat(2, 60, 100),
            'input_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
