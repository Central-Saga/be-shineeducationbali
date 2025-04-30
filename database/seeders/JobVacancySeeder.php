<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\JobVacancy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada beberapa subject terlebih dahulu
        $subjects = Subject::factory()->count(5)->create(); // Membuat 5 subject

        // Membuat 10 job vacancies, masing-masing terkait dengan subject acak
        foreach ($subjects as $subject) {
            JobVacancy::factory()->count(2)->create([
                'subject_id' => $subject->id,
            ]);
        }
    }
}
