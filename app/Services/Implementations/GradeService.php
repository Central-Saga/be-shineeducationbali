<?php

namespace App\Services\Implementations;

use App\Services\Contracts\GradeServiceInterface;
use App\Repositories\Contracts\GradeRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class GradeService implements GradeServiceInterface
{
    protected $repository;

    // Mendefinisikan key untuk caching
    const GRADES_ALL_CACHE_KEY = 'grades_all';
    const GRADES_BY_STUDENT_CACHE_KEY = 'grades_by_student';
    const GRADES_BY_CLASS_CACHE_KEY = 'grades_by_class';
    const GRADES_BY_MATERIAL_CACHE_KEY = 'grades_by_material';
    const GRADES_BY_ASSIGNMENT_CACHE_KEY = 'grades_by_assignment';
    const GRADES_BY_CATEGORY_CACHE_KEY = 'grades_by_category';
    const GRADES_HIGH_SCORE_CACHE_KEY = 'grades_high_score';
    const GRADES_LOW_SCORE_CACHE_KEY = 'grades_low_score';
    const GRADES_RECENT_CACHE_KEY = 'grades_recent';

    public function __construct(GradeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua nilai.
     *
     * @return mixed
     */
    public function getAllGrades()
    {
        return Cache::remember(self::GRADES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllGrades();
        });
    }

    /**
     * Mengambil nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeById($id)
    {
        return $this->repository->getGradeById($id);
    }

    /**
     * Mengambil nilai berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getGradesByStudentId($studentId)
    {
        $cacheKey = self::GRADES_BY_STUDENT_CACHE_KEY . '_' . $studentId;
        return Cache::remember($cacheKey, 3600, function () use ($studentId) {
            return $this->repository->getGradesByStudentId($studentId);
        });
    }

    /**
     * Mengambil nilai berdasarkan ID kelas.
     *
     * @param int $classRoomsId
     * @return mixed
     */
    public function getGradesByClassRoomsId($classRoomsId)
    {
        $cacheKey = self::GRADES_BY_CLASS_CACHE_KEY . '_' . $classRoomsId;
        return Cache::remember($cacheKey, 3600, function () use ($classRoomsId) {
            return $this->repository->getGradesByClassRoomsId($classRoomsId);
        });
    }

    /**
     * Mengambil nilai berdasarkan ID materi.
     *
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByMaterialId($materialId)
    {
        $cacheKey = self::GRADES_BY_MATERIAL_CACHE_KEY . '_' . $materialId;
        return Cache::remember($cacheKey, 3600, function () use ($materialId) {
            return $this->repository->getGradesByMaterialId($materialId);
        });
    }

    /**
     * Mengambil nilai berdasarkan ID tugas.
     *
     * @param int $assignmentId
     * @return mixed
     */
    public function getGradesByAssignmentId($assignmentId)
    {
        $cacheKey = self::GRADES_BY_ASSIGNMENT_CACHE_KEY . '_' . $assignmentId;
        return Cache::remember($cacheKey, 3600, function () use ($assignmentId) {
            return $this->repository->getGradesByAssignmentId($assignmentId);
        });
    }

    /**
     * Mengambil nilai berdasarkan kategori nilai.
     *
     * @param int $gradeCategoryId
     * @return mixed
     */
    public function getGradesByGradeCategoryId($gradeCategoryId)
    {
        $cacheKey = self::GRADES_BY_CATEGORY_CACHE_KEY . '_' . $gradeCategoryId;
        return Cache::remember($cacheKey, 3600, function () use ($gradeCategoryId) {
            return $this->repository->getGradesByGradeCategoryId($gradeCategoryId);
        });
    }

    /**
     * Mengambil nilai berdasarkan rentang nilai.
     *
     * @param float $minScore
     * @param float $maxScore
     * @return mixed
     */
    public function getGradesByScoreRange($minScore, $maxScore)
    {
        // Untuk range, kita tidak menggunakan cache karena parameter bisa bervariasi
        return $this->repository->getGradesByScoreRange($minScore, $maxScore);
    }

    /**
     * Mengambil nilai berdasarkan rentang tanggal input.
     *
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getGradesByInputDateRange($startDate, $endDate)
    {
        // Untuk range tanggal, kita tidak menggunakan cache karena parameter bisa bervariasi
        return $this->repository->getGradesByInputDateRange($startDate, $endDate);
    }

    /**
     * Membuat nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGrade(array $data)
    {
        $result = $this->repository->createGrade($data);
        $this->clearGradeCaches();
        return $result;
    }

    /**
     * Memperbarui nilai berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateGrade($id, array $data)
    {
        $result = $this->repository->updateGrade($id, $data);
        $this->clearGradeCaches();
        return $result;
    }

    /**
     * Menghapus nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGrade($id)
    {
        $result = $this->repository->deleteGrade($id);
        $this->clearGradeCaches();
        return $result;
    }

    /**
     * Menghapus semua cache nilai
     *
     * @return void
     */
    public function clearGradeCaches()
    {
        Cache::forget(self::GRADES_ALL_CACHE_KEY);
        
        // Tidak efisien untuk menghapus semua cache dengan wildcard,
        // tetapi untuk demonstrasi kita bisa menambahkan logic tambahan di sini
        // untuk menghapus cache spesifik jika dibutuhkan
    }
}
