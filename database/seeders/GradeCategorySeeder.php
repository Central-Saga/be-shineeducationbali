<?php

namespace Database\Seeders;

use App\Models\GradeCategory;
use App\Models\Program;
use Illuminate\Database\Seeder;

class GradeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data Program terlebih dahulu
        $program = Program::firstOrCreate(['name' => 'Default Program']); // Contoh Program

        // Membuat beberapa GradeCategory untuk Program tersebut
        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Good',
            'description' => 'Score range: 70-79',
        ]);

        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Very Good',
            'description' => 'Score range: 80-89',
        ]);

        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Excellent',
            'description' => 'Score range: 90-100',
        ]);
    }
}
