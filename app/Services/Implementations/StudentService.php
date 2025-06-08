<?php

namespace App\Services\Implementations;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\Contracts\StudentServiceInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentService implements StudentServiceInterface
{
    protected $repository;

    // Cache keys
    const ALL_STUDENTS_CACHE_KEY = 'all_students';
    const STUDENT_BY_ID_CACHE_KEY = 'student_by_id_';
    const STUDENT_BY_NAME_CACHE_KEY = 'student_by_name_';
    const STUDENT_BY_EMAIL_CACHE_KEY = 'student_by_email_';
    const ACTIVE_STUDENTS_CACHE_KEY = 'active_students';
    const INACTIVE_STUDENTS_CACHE_KEY = 'inactive_students';

    public function __construct(StudentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua data student.
     *
     * @return mixed
     */
    public function getAllStudents()
    {
        return Cache::remember(self::ALL_STUDENTS_CACHE_KEY, 3600, function () {
            return $this->repository->getAllStudents();
        });
    }

    /**
     * Mengambil data student berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentById($id)
    {
        return Cache::remember(self::STUDENT_BY_ID_CACHE_KEY . $id, 3600, function () use ($id) {
            return $this->repository->getStudentById($id);
        });
    }

    /**
     * Mengambil data student berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getStudentByName($name)
    {
        return Cache::remember(self::STUDENT_BY_NAME_CACHE_KEY . $name, 3600, function () use ($name) {
            return $this->repository->getStudentByName($name);
        });
    }

    /**
     * Mengambil data student berdasarkan email.
     *
     * @param string $email
     * @return mixed
     */
    public function getStudentByEmail($email)
    {
        return Cache::remember(self::STUDENT_BY_EMAIL_CACHE_KEY . $email, 3600, function () use ($email) {
            return $this->repository->getStudentByEmail($email);
        });
    }

    /**
     * Mengambil data student yang aktif.
     *
     * @return mixed
     */
    public function getActiveStudents()
    {
        return Cache::remember(self::ACTIVE_STUDENTS_CACHE_KEY, 3600, function () {
            return $this->repository->getActiveStudents();
        });
    }

    /**
     * Mengambil data student yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveStudents()
    {
        return Cache::remember(self::INACTIVE_STUDENTS_CACHE_KEY, 3600, function () {
            return $this->repository->getInactiveStudents();
        });
    }

    /**
     * Membuat data student baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudent(array $data)
    {
        $result = $this->repository->createStudent($data);
        $this->clearCache();
        return $result;
    }

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudent($id, array $data)
    {
        $result = $this->repository->updateStudent($id, $data);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus data student berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStudent($id)
    {
        try {
            $student = $this->repository->getStudentById($id);
            if (!$student) {
                Log::error("Service layer - Student with ID {$id} not found");
                return false;
            }

            $result = $this->repository->deleteStudent($id);
            $this->clearCache();
            return true;
        } catch (\Exception $e) {
            Log::error("Service layer - Failed to delete student with ID {$id}. Error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Memperbarui status student berdasarkan ID.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateStudentStatus($id, $status)
    {
        $result = $this->repository->updateStudentStatus($id, $status);
        $this->clearCache();
        return $result;
    }

    /**
     * Mengambil statistik student berdasarkan ID.
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentStatistics($studentId)
    {
        // Implementasi khusus untuk mendapatkan statistik siswa
        // seperti jumlah kelas yang diikuti, rata-rata nilai, dll.
        $student = $this->getStudentById($studentId);
        
        if (!$student) {
            return [
                'status' => 'error',
                'message' => 'Student not found',
            ];
        }

        // Contoh statistik yang bisa dikumpulkan
        return [
            'total_classes' => $student->classRooms ? $student->classRooms->count() : 0,
            'attendance_rate' => 0, // Implementasi sesuai kebutuhan
            'average_grade' => 0,   // Implementasi sesuai kebutuhan
            'completed_programs' => 0, // Implementasi sesuai kebutuhan
            'total_certificates' => $student->certificates ? $student->certificates->count() : 0,
        ];
    }

    /**
     * Membersihkan cache terkait student.
     */
    private function clearCache()
    {
        Cache::forget(self::ALL_STUDENTS_CACHE_KEY);
        Cache::forget(self::ACTIVE_STUDENTS_CACHE_KEY);
        Cache::forget(self::INACTIVE_STUDENTS_CACHE_KEY);
        // Catatan: cache berdasarkan ID, nama, dan email masih ada, 
        // tapi akan diperbarui saat diminta lagi
    }
}
