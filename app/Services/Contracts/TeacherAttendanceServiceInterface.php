<?php

namespace App\Services\Contracts;

use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Collection;

interface TeacherAttendanceServiceInterface
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
     * Get teacher attendance for a specific date and class
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date
     * @return TeacherAttendance|null
     */
    public function getAttendanceForTeacherClassAndDate($teacherId, $classId, $date);

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
     * Record teacher check-in
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @param string $time Optional, defaults to current time
     * @return TeacherAttendance|null
     */
    public function recordTeacherCheckIn($teacherId, $classId, $date = null, $time = null);

    /**
     * Record teacher check-out
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @param string $time Optional, defaults to current time
     * @return TeacherAttendance|null
     */
    public function recordTeacherCheckOut($teacherId, $classId, $date = null, $time = null);

    /**
     * Mark teacher as absent
     * 
     * @param int $teacherId
     * @param int $classId
     * @param string $date Optional, defaults to current date
     * @return TeacherAttendance|null
     */
    public function markTeacherAsAbsent($teacherId, $classId, $date = null);

    /**
     * Get teacher attendance report for date range
     * 
     * @param int $teacherId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTeacherAttendanceReport($teacherId, $startDate, $endDate);

    /**
     * Get class attendance report for date range
     * 
     * @param int $classId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getClassAttendanceReport($classId, $startDate, $endDate);

    /**
     * Delete teacher attendance
     * 
     * @param int $id
     * @return bool
     */
    public function deleteAttendance($id);
}
