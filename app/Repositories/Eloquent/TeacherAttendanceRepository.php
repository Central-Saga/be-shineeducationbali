<?php

namespace App\Repositories\Eloquent;

use App\Models\TeacherAttendance;
use App\Repositories\Contracts\TeacherAttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class TeacherAttendanceRepository implements TeacherAttendanceRepositoryInterface
{
    /**
     * @var TeacherAttendance
     */
    protected $model;

    /**
     * TeacherAttendanceRepository constructor.
     *
     * @param TeacherAttendance $model
     */
    public function __construct(TeacherAttendance $model)
    {
        $this->model = $model;
    }

    /**
     * Get all teacher attendances
     * 
     * @return Collection
     */
    public function getAllAttendances()
    {
        return $this->model->all();
    }

    /**
     * Get teacher attendance by id
     * 
     * @param int $id
     * @return TeacherAttendance|null
     */
    public function getAttendanceById($id): ?TeacherAttendance
    {
        $model = $this->model->find($id);
        return $model instanceof TeacherAttendance ? $model : null;
    }

    /**
     * Get attendances by teacher id
     * 
     * @param int $teacherId
     * @return Collection
     */
    public function getAttendancesByTeacherId($teacherId)
    {
        return $this->model->where('teacher_id', $teacherId)->get();
    }

    /**
     * Get attendances by class id
     * 
     * @param int $classId
     * @return Collection
     */
    public function getAttendancesByClassId($classId)
    {
        return $this->model->where('class_rooms_id', $classId)->get();
    }

    /**
     * Get attendances for a date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getAttendancesByDateRange($startDate, $endDate)
    {
        return $this->model->whereDate('attendance_date', '>=', $startDate)
                          ->whereDate('attendance_date', '<=', $endDate)
                          ->orderBy('attendance_date')
                          ->get();
    }

    /**
     * Get attendances for a specific date
     * 
     * @param string $date
     * @return Collection
     */
    public function getAttendancesByDate($date)
    {
        return $this->model->forDate($date)->get();
    }

    /**
     * Get attendances by status
     * 
     * @param string $status
     * @return Collection
     */
    public function getAttendancesByStatus($status)
    {
        return $this->model->withStatus($status)->get();
    }

    /**
     * Create teacher attendance record
     * 
     * @param array $data
     * @return TeacherAttendance
     */
    public function createAttendance(array $data)
    {
        return $this->model->create($data);
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
        $attendance = $this->getAttendanceById($id);
        
        if (!$attendance) {
            return null;
        }
        
        $attendance->update($data);
        return $attendance;
    }

    /**
     * Record check-in time
     * 
     * @param int $id
     * @param string $checkInTime
     * @return TeacherAttendance|null
     */
    public function recordCheckIn($id, string $checkInTime)
    {
        $attendance = $this->getAttendanceById($id);
        
        if (!$attendance) {
            return null;
        }
        
        $attendance->check_in = $checkInTime;
        $attendance->status = TeacherAttendance::STATUS_PRESENT;
        $attendance->save();
        
        return $attendance;
    }

    /**
     * Record check-out time
     * 
     * @param int $id
     * @param string $checkOutTime
     * @return TeacherAttendance|null
     */
    public function recordCheckOut($id, string $checkOutTime)
    {
        $attendance = $this->getAttendanceById($id);
        
        if (!$attendance) {
            return null;
        }
        
        $attendance->check_out = $checkOutTime;
        $attendance->save();
        
        return $attendance;
    }

    /**
     * Get teacher attendance statistics (present, absent)
     * 
     * @param int $teacherId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTeacherAttendanceStats($teacherId, $startDate, $endDate)
    {
        $attendances = $this->model->where('teacher_id', $teacherId)
                                  ->whereDate('attendance_date', '>=', $startDate)
                                  ->whereDate('attendance_date', '<=', $endDate)
                                  ->get();
        
        $presentCount = $attendances->where('status', TeacherAttendance::STATUS_PRESENT)->count();
        $absentCount = $attendances->where('status', TeacherAttendance::STATUS_ABSENT)->count();
        
        $totalMinutes = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->isPresent() && $attendance->check_in && $attendance->check_out) {
                $totalMinutes += $attendance->getDurationInMinutes();
            }
        }
        
        return [
            'total_records' => $attendances->count(),
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'attendance_percentage' => $attendances->count() > 0 ? 
                round(($presentCount / $attendances->count()) * 100, 2) : 0,
            'total_hours' => round($totalMinutes / 60, 2),
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
        $attendance = $this->getAttendanceById($id);
        
        if (!$attendance) {
            return false;
        }
        
        return $attendance->delete();
    }
}
