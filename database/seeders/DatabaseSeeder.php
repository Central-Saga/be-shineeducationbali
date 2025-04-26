<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            EducationLevelSeeder::class,
            SubjectSeeder::class,
            ClassTypeSeeder::class,
            MeetingFrequencySeeder::class,
            ProgramSeeder::class,
            MaterialSeeder::class,
            TeacherSeeder::class,
            GradeCategorySeeder::class,
            CertificateSeeder::class,
            // Tambahkan seeder baru
            GradeSeeder::class,
            CertificateGradeSeeder::class,
            StudentAttendanceSeeder::class,

        ]);
    }
}
