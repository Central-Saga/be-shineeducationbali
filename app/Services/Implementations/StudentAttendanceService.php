<?php

namespace App\Services\Implementations;

use App\Services\Contracts\StudentAttendanceServiceInterface;
use App\Repositories\Contracts\StudentAttendanceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class StudentAttendanceService implements StudentAttendanceServiceInterface
{
    protected $repository;

    // Mendefinisikan key untuk caching
    const ATTENDANCE_ALL_CACHE_KEY = 'attendance_all';
    const ATTENDANCE_BY_STUDENT_CACHE_KEY = 'attendance_by_student';
    const ATTENDANCE_BY_CLASS_CACHE_KEY = 'attendance_by_class';
    const ATTENDANCE_BY_TEACHER_CACHE_KEY = 'attendance_by_teacher';
    const ATTENDANCE_BY_DATE_CACHE_KEY = 'attendance_by_date';
    const ATTENDANCE_PRESENT_CACHE_KEY = 'attendance_present';
    const ATTENDANCE_ABSENT_CACHE_KEY = 'attendance_absent';

    public function __construct(StudentAttendanceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua data absensi siswa.
     *
     * @return mixed
     */
    public function getAllStudentAttendances()
    {
        return Cache::remember(self::ATTENDANCE_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllStudentAttendances();
        });
    }

    /**
     * Mengambil data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentAttendanceById($id)
    {
        return $this->repository->getStudentAttendanceById($id);
    }

    /**
     * Mengambil data absensi berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getAttendancesByStudentId($studentId)
    {
        $cacheKey = self::ATTENDANCE_BY_STUDENT_CACHE_KEY . '_' . $studentId;
        return Cache::remember($cacheKey, 3600, function () use ($studentId) {
            return $this->repository->getAttendancesByStudentId($studentId);
        });
    }

    /**
     * Mengambil data absensi berdasarkan ID kelas.
     *
     * @param int $classRoomsId
     * @return mixed
     */
    public function getAttendancesByClassRoomsId($classRoomsId)
    {
        $cacheKey = self::ATTENDANCE_BY_CLASS_CACHE_KEY . '_' . $classRoomsId;
        return Cache::remember($cacheKey, 3600, function () use ($classRoomsId) {
            return $this->repository->getAttendancesByClassRoomsId($classRoomsId);
        });
    }

    /**
     * Mengambil data absensi berdasarkan ID guru.
     *
     * @param int $teacherId
     * @return mixed
     */
    public function getAttendancesByTeacherId($teacherId)
    {
        $cacheKey = self::ATTENDANCE_BY_TEACHER_CACHE_KEY . '_' . $teacherId;
        return Cache::remember($cacheKey, 3600, function () use ($teacherId) {
            return $this->repository->getAttendancesByTeacherId($teacherId);
        });
    }

    /**
     * Mengambil data absensi berdasarkan tanggal tertentu.
     *
     * @param string $date
     * @return mixed
     */
    public function getAttendancesByDate($date)
    {
        $cacheKey = self::ATTENDANCE_BY_DATE_CACHE_KEY . '_' . str_replace('-', '', $date);
        return Cache::remember($cacheKey, 3600, function () use ($date) {
            return $this->repository->getAttendancesByDate($date);
        });
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
        // Untuk rentang tanggal, kita tidak menggunakan cache karena parameter bisa bervariasi
        return $this->repository->getAttendancesByDateRange($startDate, $endDate);
    }

    /**
     * Mengambil data absensi berdasarkan status (hadir/tidak hadir).
     *
     * @param string $status
     * @return mixed
     */
    public function getAttendancesByStatus($status)
    {
        $cacheKey = ($status === 'present') ? self::ATTENDANCE_PRESENT_CACHE_KEY : self::ATTENDANCE_ABSENT_CACHE_KEY;
        
        return Cache::remember($cacheKey, 3600, function () use ($status) {
            return $this->repository->getAttendancesByStatus($status);
        });
    }

    /**
     * Membuat data absensi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudentAttendance(array $data)
    {
        $result = $this->repository->createStudentAttendance($data);
        $this->clearAttendanceCaches();
        return $result;
    }

    /**
     * Memperbarui data absensi berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudentAttendance($id, array $data)
    {
        $result = $this->repository->updateStudentAttendance($id, $data);
        $this->clearAttendanceCaches();
        return $result;
    }

    /**
     * Menghapus data absensi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteStudentAttendance($id)
    {
        $result = $this->repository->deleteStudentAttendance($id);
        $this->clearAttendanceCaches();
        return $result;
    }

    /**
     * Menghapus semua cache absensi
     *
     * @return void
     */
    public function clearAttendanceCaches()
    {
        Cache::forget(self::ATTENDANCE_ALL_CACHE_KEY);
        Cache::forget(self::ATTENDANCE_PRESENT_CACHE_KEY);
        Cache::forget(self::ATTENDANCE_ABSENT_CACHE_KEY);
        
        // Untuk cache yang lebih spesifik, kita bisa menambahkan logic tambahan di sini
        // Contoh: Cache::forget(self::ATTENDANCE_BY_STUDENT_CACHE_KEY . '_' . $studentId);
    }
}
