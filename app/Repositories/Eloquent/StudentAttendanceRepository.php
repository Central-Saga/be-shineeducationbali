<?php

namespace App\Repositories\Eloquent;

use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\StudentAttendanceRepositoryInterface;

class StudentAttendanceRepository implements StudentAttendanceRepositoryInterface
{
    protected $model;

    public function __construct(StudentAttendance $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua data absensi siswa.
     *
     * @return mixed
     */
    public function getAllStudentAttendances()
    {
        return $this->model->with(['student', 'classRoom', 'teacher'])->get();
    }

    /**
     * Mengambil data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentAttendanceById($id)
    {
        try {
            return $this->model->with(['student', 'classRoom', 'teacher'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Student Attendance with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil data absensi berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getAttendancesByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Mengambil data absensi berdasarkan ID kelas.
     *
     * @param int $classRoomsId
     * @return mixed
     */
    public function getAttendancesByClassRoomsId($classRoomsId)
    {
        return $this->model->where('class_rooms_id', $classRoomsId)
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Mengambil data absensi berdasarkan ID guru.
     *
     * @param int $teacherId
     * @return mixed
     */
    public function getAttendancesByTeacherId($teacherId)
    {
        return $this->model->where('teacher_id', $teacherId)
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Mengambil data absensi berdasarkan tanggal.
     *
     * @param string $date
     * @return mixed
     */
    public function getAttendancesByDate($date)
    {
        return $this->model->whereDate('attendance_date', $date)
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Mengambil data absensi berdasarkan rentang tanggal.
     *
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getAttendancesByDateRange($startDate, $endDate)
    {
        return $this->model->whereBetween('attendance_date', [$startDate, $endDate])
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Mengambil data absensi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAttendancesByStatus($status)
    {
        return $this->model->where('status', $status)
            ->with(['student', 'classRoom', 'teacher'])
            ->get();
    }

    /**
     * Membuat data absensi siswa baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudentAttendance(array $data)
    {
        try {
            $attendance = $this->model->create($data);
            $attendance->load(['student', 'classRoom', 'teacher']);
            return $attendance;
        } catch (\Exception $e) {
            Log::error("Failed to create student attendance: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudentAttendance($id, array $data)
    {
        $attendance = $this->findStudentAttendance($id);

        if ($attendance) {
            try {
                $attendance->update($data);
                $attendance->load(['student', 'classRoom', 'teacher']);
                return $attendance;
            } catch (\Exception $e) {
                Log::error("Failed to update student attendance with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteStudentAttendance($id)
    {
        $attendance = $this->findStudentAttendance($id);

        if ($attendance) {
            try {
                $attendance->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete student attendance with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findStudentAttendance($id)
    {
        try {
            return $this->model->with(['student', 'classRoom', 'teacher'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Student Attendance with ID {$id} not found.");
            return null;
        }
    }
}
