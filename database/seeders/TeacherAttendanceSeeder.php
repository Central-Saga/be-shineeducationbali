<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing teachers and classrooms
        $teachers = Teacher::all();
        $classRooms = ClassRoom::all();

        if ($teachers->count() === 0 || $classRooms->count() === 0) {
            // Create sample data if none exists
            TeacherAttendance::factory(50)->create();
            TeacherAttendance::factory(10)->noCheckout()->create();
            TeacherAttendance::factory(5)->notAttended()->create();
        } else {
            // Create attendances for existing teachers and classrooms
            $startDate = Carbon::now()->subMonths(2);
            $endDate = Carbon::now();

            foreach ($teachers as $teacher) {
                // Get classrooms where this teacher teaches
                $teacherClassrooms = $classRooms->random(min(3, $classRooms->count()));
                
                foreach ($teacherClassrooms as $classroom) {
                    // Create attendance records for past dates
                    for ($date = clone $startDate; $date->lte($endDate); $date->addDays(rand(1, 7))) {
                        // 80% chance to have a complete attendance record
                        $randomStatus = rand(1, 100);
                        
                        if ($randomStatus <= 80) {
                            // Complete attendance
                            $checkIn = (clone $date)->setHour(rand(8, 10))->setMinute(rand(0, 59));
                            $checkOut = (clone $checkIn)->addHours(rand(1, 4));
                            
                            TeacherAttendance::create([
                                'class_id' => $classroom->id,
                                'teacher_id' => $teacher->id,
                                'attendance_date' => $date->toDateString(),
                                'check_in' => $checkIn,
                                'check_out' => $checkOut,
                            ]);
                        } elseif ($randomStatus <= 95) {
                            // No checkout
                            $checkIn = (clone $date)->setHour(rand(8, 10))->setMinute(rand(0, 59));
                            
                            TeacherAttendance::create([
                                'class_id' => $classroom->id,
                                'teacher_id' => $teacher->id,
                                'attendance_date' => $date->toDateString(),
                                'check_in' => $checkIn,
                                'check_out' => null,
                            ]);
                        } else {
                            // Not attended
                            TeacherAttendance::create([
                                'class_id' => $classroom->id,
                                'teacher_id' => $teacher->id,
                                'attendance_date' => $date->toDateString(),
                                'check_in' => null,
                                'check_out' => null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
