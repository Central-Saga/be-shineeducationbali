<?php

namespace App\Repositories\Contracts;

interface GradeRepositoryInterface
{
    /**
     * Mengambil semua nilai.
     *
     * @return mixed
     */
    public function getAllGrades();

    /**
     * Mengambil nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeById($id);

    /**
     * Mengambil nilai berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getGradesByStudentId($studentId);

    /**
     * Mengambil nilai berdasarkan ID kelas.
     *
     * @param int $classRoomsId
     * @return mixed
     */
    public function getGradesByClassRoomsId($classRoomsId);

    /**
     * Mengambil nilai berdasarkan ID materi.
     *
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByMaterialId($materialId);

    /**
     * Mengambil nilai berdasarkan ID tugas.
     *
     * @param int $assignmentId
     * @return mixed
     */
    public function getGradesByAssignmentId($assignmentId);

    /**
     * Mengambil nilai berdasarkan kategori nilai.
     *
     * @param int $gradeCategoryId
     * @return mixed
     */
    public function getGradesByGradeCategoryId($gradeCategoryId);

    /**
     * Mengambil nilai berdasarkan rentang nilai.
     *
     * @param float $minScore
     * @param float $maxScore
     * @return mixed
     */
    public function getGradesByScoreRange($minScore, $maxScore);

    /**
     * Mengambil nilai berdasarkan rentang tanggal input.
     *
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getGradesByInputDateRange($startDate, $endDate);

    /**
     * Mengambil nilai berdasarkan ID siswa dan ID materi.
     *
     * @param int $studentId
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByStudentIdAndMaterialId($studentId, $materialId);

    /**
     * Mengambil nilai berdasarkan ID kelas dan ID materi.
     *
     * @param int $classRoomId
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByClassRoomIdAndMaterialId($classRoomId, $materialId);

    /**
     * Membuat nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGrade(array $data);

    /**
     * Memperbarui nilai berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateGrade($id, array $data);

    /**
     * Menghapus nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGrade($id);
}
