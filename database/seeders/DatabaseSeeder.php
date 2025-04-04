<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\LeaveSeeder;
use Database\Seeders\ProgramSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\TeacherSeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\ClassTypeSeeder;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\EducationLevelSeeder;
use Database\Seeders\MeetingFrequencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;

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
            LeaveSeeder::class,
            NotificationSeeder::class,
            ArticleSeeder::class,
            JobVacancySeeder::class,
            JobApplicationSeeder::class,
        ]);
    }
}
