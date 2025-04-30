<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\CertificateGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CertificateGrade>
 */
class CertificateGradeFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = CertificateGrade::class;

    /**
     * Definisi state default untuk factory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'certificate_id' => function () {
                // Karena model Certificate belum ada, kita gunakan nilai random untuk sementara
                return $this->faker->numberBetween(1, 10);
            },
            'grade_id' => function () {
                return Grade::inRandomOrder()->first()->id ?? Grade::factory()->create()->id;
            },
        ];
    }
}
