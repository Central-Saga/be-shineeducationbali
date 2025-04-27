<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Student;
use App\Models\StudentQuota;
use Illuminate\Database\Seeder;

class StudentQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing students and programs
        $students = Student::all();
        $programs = Program::all();

        if ($students->count() === 0 || $programs->count() === 0) {
            // Create sample data if none exists
            StudentQuota::factory(30)->create();
        } else {
            // Create quotas for existing students and programs
            foreach ($students as $student) {
                $programsCount = min(3, $programs->count()); // Create quotas for up to 3 programs
                $selectedPrograms = $programs->random($programsCount);
                
                foreach ($selectedPrograms as $program) {
                    $sessionsPaid = rand(10, 50);
                    $sessionsUsed = rand(0, $sessionsPaid);
                    $sessionsRemaining = $sessionsPaid - $sessionsUsed;

                    StudentQuota::create([
                        'student_id' => $student->id,
                        'program_id' => $program->id,
                        'period' => now()->addMonths(rand(-3, 3)),
                        'sessions_paid' => $sessionsPaid,
                        'sessions_used' => $sessionsUsed,
                        'sessions_remaining' => $sessionsRemaining,
                        'sessions_accumulated' => rand(0, 10),
                    ]);
                }
            }
        }
    }
}
