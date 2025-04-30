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
            // 1. Roles dan Permissions (harus paling awal)
            RoleAndPermissionSeeder::class,
            
            // 2. User dan data master
            UserSeeder::class,
            EducationLevelSeeder::class,
            SubjectSeeder::class,
            ClassTypeSeeder::class,
            MeetingFrequencySeeder::class,
            ProgramSeeder::class,
            
            // 3. Data pendidik dan data terkait program
            TeacherSeeder::class,
            LeaveSeeder::class,
            NotificationSeeder::class,
            ArticleSeeder::class,
            JobVacancySeeder::class,
            JobApplicationSeeder::class,
            TestimonialSeeder::class,
            StudentSeeder::class,
            MaterialSeeder::class,
            GradeCategorySeeder::class,
            // 4. Data operasional sekolah
            ClassRoomSeeder::class,
            ScheduleSeeder::class,
            AssignmentSeeder::class,
            // 5. Data penilaian dan sertifikat
            GradeSeeder::class,
            CertificateSeeder::class,
            CertificateGradeSeeder::class,
            // 6. Data absensi
            StudentAttendanceSeeder::class,

        ]);
    }
}
