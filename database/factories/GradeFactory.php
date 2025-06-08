<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Material;
use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\Assignment;
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
            'class_rooms_id' => function () {
                return ClassRoom::inRandomOrder()->first()->id ?? ClassRoom::factory()->create()->id;
            },
            'material_id' => function () {
                return Material::inRandomOrder()->first()->id ?? Material::factory()->create()->id;
            },
            'assignment_id' => function () {
                return Assignment::count() > 0 ? Assignment::inRandomOrder()->first()->id : null;
            },
            'grade_category_id' => function () {
                return GradeCategory::inRandomOrder()->first()->id ?? GradeCategory::factory()->create()->id;
            },
            'score' => $this->faker->randomFloat(2, 60, 100),
            'input_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
