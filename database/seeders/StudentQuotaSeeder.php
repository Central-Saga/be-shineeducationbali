<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Student;
use App\Models\StudentQuota;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = Student::all();
        
        if ($students->isEmpty()) {
            echo "No students found. Skipping StudentQuota seeding.\n";
            return;
        }
        
        // Get current month and previous months
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = (clone $currentMonth)->subMonth();
        $twoMonthsAgo = (clone $currentMonth)->subMonths(2);
        
        foreach ($students as $student) {
            // Get the student's program
            $program = $student->program;
            
            if (!$program) {
                // If student doesn't have a program, get a random one
                $program = Program::inRandomOrder()->first();
                if (!$program) {
                    continue; // Skip if no programs exist
                }
            }
            
            // Create quota for current month
            $sessionsPaid = rand(4, 12);
            $sessionsUsed = rand(0, $sessionsPaid - 2); // Leave some remaining
            $sessionsAccumulated = rand(0, 3);
            $sessionsRemaining = $sessionsPaid + $sessionsAccumulated - $sessionsUsed;
            
            StudentQuota::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'period' => $currentMonth->format('Y-m-d'),
                'sessions_paid' => $sessionsPaid,
                'sessions_used' => $sessionsUsed,
                'sessions_remaining' => $sessionsRemaining,
                'sessions_accumulated' => $sessionsAccumulated,
            ]);
            
            // Create quota for previous month (fully used)
            $prevSessionsPaid = rand(4, 12);
            $prevSessionsAccumulated = rand(0, 3);
            $prevSessionsUsed = $prevSessionsPaid + $prevSessionsAccumulated; // All used
            
            StudentQuota::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'period' => $previousMonth->format('Y-m-d'),
                'sessions_paid' => $prevSessionsPaid,
                'sessions_used' => $prevSessionsUsed,
                'sessions_remaining' => 0, // All used
                'sessions_accumulated' => $prevSessionsAccumulated,
            ]);
            
            // Create quota for two months ago (50% random students)
            if (rand(0, 1) == 1) {
                $olderSessionsPaid = rand(4, 12);
                $olderSessionsAccumulated = rand(0, 3);
                $olderSessionsUsed = $olderSessionsPaid + $olderSessionsAccumulated; // All used
                
                StudentQuota::create([
                    'student_id' => $student->id,
                    'program_id' => $program->id,
                    'period' => $twoMonthsAgo->format('Y-m-d'),
                    'sessions_paid' => $olderSessionsPaid,
                    'sessions_used' => $olderSessionsUsed,
                    'sessions_remaining' => 0, // All used
                    'sessions_accumulated' => $olderSessionsAccumulated,
                ]);
            }
        }
    }
}
