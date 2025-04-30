<?php

namespace App\Repositories\Contracts;

use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Collection;

interface TeacherAttendanceRepositoryInterface
{
    /**
     * Get all teacher attendances
     * 
     * @return Collection
     */
    public function getAllAttendances();

    /**
     * Get teacher attendance by id
     * 
     * @param int $id
     * @return TeacherAttendance|null
     */
    public function getAttendanceById($id);

    /**
     * Get attendances by teacher id
     * 
     * @param int $teacherId
     * @return Collection
     */
    public function getAttendancesByTeacherId($teacherId);

    /**
     * Get attendances by class id
     * 
     * @param int $classId
     * @return Collection
     */
    public function getAttendancesByClassId($classId);

    /**
     * Get attendances for a date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getAttendancesByDateRange($startDate, $endDate);

    /**
     * Get attendances for a specific date
     * 
     * @param string $date
     * @return Collection
     */
    public function getAttendancesByDate($date);

    /**
     * Get attendances by status
     * 
     * @param string $status
     * @return Collection
     */
    public function getAttendancesByStatus($status);

    /**
     * Create teacher attendance record
     * 
     * @param array $data
     * @return TeacherAttendance
     */
    public function createAttendance(array $data);

    /**
     * Update teacher attendance
     * 
     * @param int $id
     * @param array $data
     * @return TeacherAttendance|null
     */
    public function updateAttendance($id, array $data);

    /**
     * Record check-in time
     * 
     * @param int $id
     * @param string $checkInTime
     * @return TeacherAttendance|null
     */
    public function recordCheckIn($id, string $checkInTime);

    /**
     * Record check-out time
     * 
     * @param int $id
     * @param string $checkOutTime
     * @return TeacherAttendance|null
     */
    public function recordCheckOut($id, string $checkOutTime);

    /**
     * Get teacher attendance statistics (present, absent)
     * 
     * @param int $teacherId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTeacherAttendanceStats($teacherId, $startDate, $endDate);

    /**
     * Delete teacher attendance
     * 
     * @param int $id
     * @return bool
     */
    public function deleteAttendance($id);
}
