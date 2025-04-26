<?php

namespace App\Repositories\Eloquent;

use App\Models\Grade;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\GradeRepositoryInterface;

class GradeRepository implements GradeRepositoryInterface
{
    protected $model;

    public function __construct(Grade $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua nilai.
     *
     * @return mixed
     */
    public function getAllGrades()
    {
        return $this->model->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])->get();
    }

    /**
     * Mengambil nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeById($id)
    {
        try {
            return $this->model->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Grade with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil nilai berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getGradesByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan ID kelas.
     *
     * @param int $classRoomsId
     * @return mixed
     */
    public function getGradesByClassRoomsId($classRoomsId)
    {
        return $this->model->where('class_rooms_id', $classRoomsId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan ID materi.
     *
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByMaterialId($materialId)
    {
        return $this->model->where('material_id', $materialId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan ID tugas.
     *
     * @param int $assignmentId
     * @return mixed
     */
    public function getGradesByAssignmentId($assignmentId)
    {
        return $this->model->where('assignment_id', $assignmentId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan kategori nilai.
     *
     * @param int $gradeCategoryId
     * @return mixed
     */
    public function getGradesByGradeCategoryId($gradeCategoryId)
    {
        return $this->model->where('grade_category_id', $gradeCategoryId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
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
        return $this->model->whereBetween('score', [$minScore, $maxScore])
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
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
        return $this->model->whereBetween('input_date', [$startDate, $endDate])
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan ID siswa dan ID materi.
     *
     * @param int $studentId
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByStudentIdAndMaterialId($studentId, $materialId)
    {
        return $this->model->where('student_id', $studentId)
            ->where('material_id', $materialId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Mengambil nilai berdasarkan ID kelas dan ID materi.
     *
     * @param int $classRoomId
     * @param int $materialId
     * @return mixed
     */
    public function getGradesByClassRoomIdAndMaterialId($classRoomId, $materialId)
    {
        return $this->model->where('class_rooms_id', $classRoomId)
            ->where('material_id', $materialId)
            ->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])
            ->get();
    }

    /**
     * Membuat nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGrade(array $data)
    {
        try {
            $grade = $this->model->create($data);
            $grade->load(['student', 'classRoom', 'material', 'gradeCategory', 'assignment']);
            return $grade;
        } catch (\Exception $e) {
            Log::error("Failed to create grade: {$e->getMessage()}");
            return null;
        }
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
        $grade = $this->findGrade($id);

        if ($grade) {
            try {
                $grade->update($data);
                $grade->load(['student', 'classRoom', 'material', 'gradeCategory', 'assignment']);
                return $grade;
            } catch (\Exception $e) {
                Log::error("Failed to update grade with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGrade($id)
    {
        $grade = $this->findGrade($id);

        if ($grade) {
            try {
                $grade->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete grade with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findGrade($id)
    {
        try {
            return $this->model->with(['student', 'classRoom', 'material', 'gradeCategory', 'assignment'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Grade with ID {$id} not found.");
            return null;
        }
    }
}
