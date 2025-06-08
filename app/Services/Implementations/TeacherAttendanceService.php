<?php

namespace App\Services\Implementations;

use App\Models\TeacherAttendance;
use App\Repositories\Contracts\TeacherAttendanceRepositoryInterface;
use App\Services\Contracts\TeacherAttendanceServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TeacherAttendanceService implements TeacherAttendanceServiceInterface
{
    /**
     * @var TeacherAttendanceRepositoryInterface
     */
    protected $teacherAttendanceRepository;

    /**
     * TeacherAttendanceService constructor.
     *
     * @param TeacherAttendanceRepositoryInterface $teacherAttendanceRepository
     */
    public function __construct(TeacherAttendanceRepositoryInterface $teacherAttendanceRepository)
    {
        $this->teacherAttendanceRepository = $teacherAttendanceRepository;
    }

    /**
     * Get all teacher attendances
     * 
     * @return Collection
     */
    public function getAllAttendances()
    {
        return $this->teacherAttendanceRepository->getAllAttendances();
    }

    /**
     * Get teacher attendance by id
     * 
     * @param int $id
     * @return TeacherAttendance|null
     */
    public function getAttendanceById($id)
    {
        return $this->teacherAttendanceRepository->getAttendanceById($id);
    }

    /**
     * Get attendances by teacher id
     * 
     * @param int $teacherId
     * @return Collection
     */
    public function getAttendancesByTeacherId($teacherId)
    {
        return $this->teacherAttendanceRepository->getAttendancesByTeacherId($teacherId);
    }

    /**
     * Get attendances by class id
     * 
     * @param int $classId
     * @return Collection
     */
    public function getAttendancesByClassId($classId)
    {
        return $this->teacherAttendanceRepository->getAttendancesByClassId($classId);
    }

    /**
     * Get teacher attendance for a specific date and class
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date
     * @return TeacherAttendance|null
     */
    public function getAttendanceForTeacherClassAndDate($teacherId, $classId, $date)
    {
        $formattedDate = $date instanceof Carbon ? $date->format('Y-m-d') : $date;
        
        $attendances = $this->teacherAttendanceRepository->getAttendancesByDate($formattedDate);
        return $attendances->where('teacher_id', $teacherId)
                          ->where('class_rooms_id', $classId)
                          ->first();
    }

    /**
     * Create teacher attendance record
     * 
     * @param array $data
     * @return TeacherAttendance
     */
    public function createAttendance(array $data)
    {
        // Ensure attendance date is properly formatted
        if (isset($data['attendance_date']) && !$data['attendance_date'] instanceof Carbon) {
            try {
                $data['attendance_date'] = Carbon::parse($data['attendance_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $data['attendance_date'] = Carbon::now()->format('Y-m-d');
            }
        } elseif (!isset($data['attendance_date'])) {
            $data['attendance_date'] = Carbon::now()->format('Y-m-d');
        }
        
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = isset($data['check_in']) ? 
                TeacherAttendance::STATUS_PRESENT : 
                TeacherAttendance::STATUS_ABSENT;
        }
        
        return $this->teacherAttendanceRepository->createAttendance($data);
    }

    /**
     * Update teacher attendance
     * 
     * @param int $id
     * @param array $data
     * @return TeacherAttendance|null
     */
    public function updateAttendance($id, array $data)
    {
        // Format attendance_date if provided
        if (isset($data['attendance_date']) && !$data['attendance_date'] instanceof Carbon) {
            try {
                $data['attendance_date'] = Carbon::parse($data['attendance_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                unset($data['attendance_date']);
            }
        }
        
        return $this->teacherAttendanceRepository->updateAttendance($id, $data);
    }

    /**
     * Record teacher check-in
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @param string $time Optional, defaults to current time
     * @return TeacherAttendance|null
     */
    public function recordTeacherCheckIn($teacherId, $classId, $date = null, $time = null)
    {
        $attendanceDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $checkInTime = $time ? Carbon::parse($time)->format('H:i:s') : Carbon::now()->format('H:i:s');
        
        // Check if an attendance record already exists for this teacher, class and date
        $attendance = $this->getAttendanceForTeacherClassAndDate($teacherId, $classId, $attendanceDate);
        
        if ($attendance) {
            // Update existing record - menggunakan getKey() untuk mendapatkan primary key
            return $this->teacherAttendanceRepository->recordCheckIn($attendance->getKey(), $checkInTime);
        } else {
            // Create new attendance record
            return $this->createAttendance([
                'teacher_id' => $teacherId,
                'class_rooms_id' => $classId,
                'attendance_date' => $attendanceDate,
                'check_in' => $checkInTime,
                'status' => TeacherAttendance::STATUS_PRESENT,
            ]);
        }
    }

    /**
     * Record teacher check-out
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @param string $time Optional, defaults to current time
     * @return TeacherAttendance|null
     */
    public function recordTeacherCheckOut($teacherId, $classId, $date = null, $time = null)
    {
        $attendanceDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $checkOutTime = $time ? Carbon::parse($time)->format('H:i:s') : Carbon::now()->format('H:i:s');
        
        // Get the attendance record for this teacher and class
        $attendance = $this->getAttendanceForTeacherClassAndDate($teacherId, $classId, $attendanceDate);
        
        if (!$attendance) {
            // No attendance record found - can't check out without checking in first
            return null;
        }
        
        // Record check-out time - menggunakan getKey() untuk mendapatkan primary key
        return $this->teacherAttendanceRepository->recordCheckOut($attendance->getKey(), $checkOutTime);
    }

    /**
     * Mark teacher as absent
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @return TeacherAttendance|null
     */
    public function markTeacherAsAbsent($teacherId, $classId, $date = null)
    {
        $attendanceDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        
        // Check if an attendance record already exists for this teacher, class and date
        $attendance = $this->getAttendanceForTeacherClassAndDate($teacherId, $classId, $attendanceDate);
        
        if ($attendance) {
            // Update existing record - menggunakan getKey() untuk mendapatkan primary key
            return $this->teacherAttendanceRepository->updateAttendance($attendance->getKey(), [
                'check_in' => null,
                'check_out' => null,
                'status' => TeacherAttendance::STATUS_ABSENT
            ]);
        } else {
            // Create new absence record
            return $this->createAttendance([
                'teacher_id' => $teacherId,
                'class_rooms_id' => $classId,
                'attendance_date' => $attendanceDate,
                'check_in' => null,
                'check_out' => null,
                'status' => TeacherAttendance::STATUS_ABSENT
            ]);
        }
    }

    /**
     * Get teacher attendance report for date range
     * 
     * @param int $teacherId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTeacherAttendanceReport($teacherId, $startDate, $endDate)
    {
        // Ensure dates are properly formatted
        $startDate = $startDate instanceof Carbon ? $startDate->format('Y-m-d') : $startDate;
        $endDate = $endDate instanceof Carbon ? $endDate->format('Y-m-d') : $endDate;
        
        // Get basic attendance statistics
        $stats = $this->teacherAttendanceRepository->getTeacherAttendanceStats($teacherId, $startDate, $endDate);
        
        // Get all attendances for detailed information
        $attendances = $this->teacherAttendanceRepository->getAttendancesByDateRange($startDate, $endDate)
                                                        ->where('teacher_id', $teacherId);
        
        // Group attendances by class
        $classSummaries = [];
        foreach ($attendances as $attendance) {
            $classId = $attendance->class_rooms_id;
            $className = $attendance->classRoom ? $attendance->classRoom->name : "Class #$classId";
            
            if (!isset($classSummaries[$classId])) {
                $classSummaries[$classId] = [
                    'class_id' => $classId,
                    'class_name' => $className,
                    'total_records' => 0,
                    'present_count' => 0,
                    'absent_count' => 0,
                    'attendance_percentage' => 0,
                    'total_hours' => 0,
                ];
            }
            
            $classSummaries[$classId]['total_records']++;
            
            if ($attendance->isPresent()) {
                $classSummaries[$classId]['present_count']++;
                if ($attendance->check_in && $attendance->check_out) {
                    $classSummaries[$classId]['total_hours'] += $attendance->getDurationInMinutes() / 60;
                }
            } elseif ($attendance->isAbsent()) {
                $classSummaries[$classId]['absent_count']++;
            }
            
            // Calculate attendance percentage for this class
            $totalRecords = $classSummaries[$classId]['total_records'];
            $classSummaries[$classId]['attendance_percentage'] = $totalRecords > 0 ? 
                round(($classSummaries[$classId]['present_count'] / $totalRecords) * 100, 2) : 0;
        }
        
        // Add teacher and date information to the report
        return [
            'teacher_id' => $teacherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'overall_stats' => $stats,
            'class_summaries' => array_values($classSummaries),
            'detailed_attendances' => $attendances->map(function($attendance) {
                return $attendance->only([
                    'id', 'class_rooms_id', 'attendance_date', 'check_in', 'check_out', 'status'
                ]);
            })->toArray(),
        ];
    }

    /**
     * Get class attendance report for date range
     * 
     * @param int $classId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getClassAttendanceReport($classId, $startDate, $endDate)
    {
        // Ensure dates are properly formatted
        $startDate = $startDate instanceof Carbon ? $startDate->format('Y-m-d') : $startDate;
        $endDate = $endDate instanceof Carbon ? $endDate->format('Y-m-d') : $endDate;
        
        // Get all attendance records for this class within date range
        $attendances = $this->teacherAttendanceRepository->getAttendancesByDateRange($startDate, $endDate)
                                                        ->where('class_rooms_id', $classId);
        
        // Group attendances by teacher
        $teacherSummaries = [];
        $overallPresentCount = 0;
        $overallAbsentCount = 0;
        $overallTotalHours = 0;
        
        foreach ($attendances as $attendance) {
            $teacherId = $attendance->teacher_id;
            $teacherName = $attendance->teacher && $attendance->teacher->user ? 
                            $attendance->teacher->user->name : "Teacher #$teacherId";
            
            if (!isset($teacherSummaries[$teacherId])) {
                $teacherSummaries[$teacherId] = [
                    'teacher_id' => $teacherId,
                    'teacher_name' => $teacherName,
                    'total_records' => 0,
                    'present_count' => 0,
                    'absent_count' => 0,
                    'attendance_percentage' => 0,
                    'total_hours' => 0,
                    'attendances' => [],
                ];
            }
            
            $teacherSummaries[$teacherId]['total_records']++;
            $teacherSummaries[$teacherId]['attendances'][] = $attendance->only([
                'id', 'attendance_date', 'check_in', 'check_out', 'status'
            ]);
            
            if ($attendance->isPresent()) {
                $teacherSummaries[$teacherId]['present_count']++;
                $overallPresentCount++;
                
                if ($attendance->check_in && $attendance->check_out) {
                    $durationHours = $attendance->getDurationInMinutes() / 60;
                    $teacherSummaries[$teacherId]['total_hours'] += $durationHours;
                    $overallTotalHours += $durationHours;
                }
            } elseif ($attendance->isAbsent()) {
                $teacherSummaries[$teacherId]['absent_count']++;
                $overallAbsentCount++;
            }
            
            // Calculate attendance percentage for this teacher
            $totalRecords = $teacherSummaries[$teacherId]['total_records'];
            $teacherSummaries[$teacherId]['attendance_percentage'] = $totalRecords > 0 ? 
                round(($teacherSummaries[$teacherId]['present_count'] / $totalRecords) * 100, 2) : 0;
        }
        
        // Calculate overall attendance percentage
        $totalRecords = $overallPresentCount + $overallAbsentCount;
        $overallPercentage = $totalRecords > 0 ? 
            round(($overallPresentCount / $totalRecords) * 100, 2) : 0;
        
        // Add class and date information to the report
        return [
            'class_id' => $classId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'overall_stats' => [
                'total_records' => $totalRecords,
                'present_count' => $overallPresentCount,
                'absent_count' => $overallAbsentCount,
                'attendance_percentage' => $overallPercentage,
                'total_hours' => round($overallTotalHours, 2),
            ],
            'teacher_summaries' => array_values($teacherSummaries),
        ];
    }

    /**
     * Delete teacher attendance
     * 
     * @param int $id
     * @return bool
     */
    public function deleteAttendance($id)
    {
        return $this->teacherAttendanceRepository->deleteAttendance($id);
    }
}