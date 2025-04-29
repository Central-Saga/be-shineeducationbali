<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teachers and classes
        $teachers = Teacher::all();
        $classRooms = ClassRoom::all();

        if ($teachers->isEmpty() || $classRooms->isEmpty()) {
            echo "No teachers or classes found. Skipping TeacherAttendance seeding.\n";
            return;
        }

        // Generate attendance records for the past 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($teachers as $teacher) {
            // Find classes this teacher is assigned to
            $teacherClasses = $classRooms->where('teacher_id', $teacher->id);
            
            if ($teacherClasses->isEmpty()) {
                // Use random classes if teacher has no assigned classes
                $teacherClasses = $classRooms->random(min(3, $classRooms->count()));
            }

            foreach ($teacherClasses as $classRoom) {
                // Generate 10-15 attendance records per teacher per class
                $attendanceDates = [];
                for ($i = 0; $i < rand(10, 15); $i++) {
                    // Generate a random date within the range
                    $randomDay = rand(0, 30);
                    $attendanceDate = (clone $startDate)->addDays($randomDay);
                    $dateString = $attendanceDate->format('Y-m-d');
                    
                    // Skip if we already have attendance for this date to avoid duplicates
                    if (in_array($dateString, $attendanceDates)) {
                        continue;
                    }
                    
                    $attendanceDates[] = $dateString;

                    // Determine status with weighted probabilities
                    $statusRoll = rand(1, 100);
                    if ($statusRoll <= 85) {
                        // 85% present
                        TeacherAttendance::factory()->create([
                            'teacher_id' => $teacher->id,
                            'class_rooms_id' => $classRoom->id,
                            'attendance_date' => $dateString,
                            'status' => TeacherAttendance::STATUS_PRESENT,
                        ]);
                    } else {
                        // 15% absent
                        TeacherAttendance::factory()->absent()->create([
                            'teacher_id' => $teacher->id,
                            'class_rooms_id' => $classRoom->id,
                            'attendance_date' => $dateString,
                        ]);
                    }
                }
            }
        }
    }
}
