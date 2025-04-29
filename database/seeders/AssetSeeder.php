<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Program;
use App\Models\Assignment;
use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create assets for students
        Student::all()->each(function ($student) {
            Asset::factory()
                ->count(2)
                ->forAssetable($student::class, $student->id)
                ->create();
        });

        // Create assets for teachers
        Teacher::all()->each(function ($teacher) {
            Asset::factory()
                ->count(2)
                ->forAssetable($teacher::class, $teacher->id)
                ->create();
        });

        // Create assets for programs
        Program::all()->each(function ($program) {
            Asset::factory()
                ->count(1)
                ->forAssetable($program::class, $program->id)
                ->create();
        });

        // Create assets for assignments
        Assignment::all()->each(function ($assignment) {
            Asset::factory()
                ->count(3)
                ->forAssetable($assignment::class, $assignment->id)
                ->create();
        });

        // Create assets for materials
        Material::all()->each(function ($material) {
            Asset::factory()
                ->count(2)
                ->forAssetable($material::class, $material->id)
                ->create();
        });
    }
}
