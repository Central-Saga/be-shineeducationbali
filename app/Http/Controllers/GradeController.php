<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeStoreRequest;
use App\Http\Requests\GradeUpdateRequest;
use App\Http\Resources\GradeResource;
use App\Services\Contracts\GradeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeServiceInterface $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Menampilkan daftar semua nilai.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $grades = $this->gradeService->getAllGrades();
        return response()->json([
            'status' => 'success',
            'data' => GradeResource::collection($grades),
        ], 200);
    }

    /**
     * Menyimpan nilai baru.
     *
     * @param GradeStoreRequest $request
     * @return JsonResponse
     */
    public function store(GradeStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $grade = $this->gradeService->createGrade($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil dibuat',
            'data' => new GradeResource($grade),
        ], 201);
    }

    /**
     * Menampilkan detail nilai berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $grade = $this->gradeService->getGradeById($id);
        return response()->json([
            'status' => 'success',
            'data' => new GradeResource($grade),
        ], 200);
    }

    /**
     * Memperbarui nilai berdasarkan ID.
     *
     * @param GradeUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(GradeUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $grade = $this->gradeService->updateGrade($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil diperbarui',
            'data' => new GradeResource($grade),
        ], 200);
    }

    /**
     * Menghapus nilai berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->gradeService->deleteGrade($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil dihapus',
        ], 200);
    }

    /**
     * Mendapatkan nilai berdasarkan siswa.
     *
     * @param int $studentId
     * @return JsonResponse
     */
    public function getByStudent(int $studentId): JsonResponse
    {
        $grades = $this->gradeService->getGradesByStudentId($studentId);
        return response()->json([
            'status' => 'success',
            'data' => GradeResource::collection($grades),
        ], 200);
    }

    /**
     * Mendapatkan nilai berdasarkan kelas.
     *
     * @param int $classRoomId
     * @return JsonResponse
     */
    public function getByClassRoom(int $classRoomId): JsonResponse
    {
        $grades = $this->gradeService->getGradesByClassRoomsId($classRoomId);
        return response()->json([
            'status' => 'success',
            'data' => GradeResource::collection($grades),
        ], 200);
    }

    /**
     * Mendapatkan nilai berdasarkan kategori nilai.
     *
     * @param int $gradeCategoryId
     * @return JsonResponse
     */
    public function getByGradeCategory(int $gradeCategoryId): JsonResponse
    {
        $grades = $this->gradeService->getGradesByGradeCategoryId($gradeCategoryId);
        return response()->json([
            'status' => 'success',
            'data' => GradeResource::collection($grades),
        ], 200);
    }

    /**
     * Mendapatkan rata-rata nilai siswa pada mata pelajaran tertentu.
     *
     * @param int $studentId
     * @param int $materialId
     * @return JsonResponse
     */
    public function getAverageByStudentAndMaterial(int $studentId, int $materialId): JsonResponse
    {
        $average = $this->gradeService->getAverageGradeByStudentAndMaterial($studentId, $materialId);
        return response()->json([
            'status' => 'success',
            'data' => [
                'student_id' => $studentId,
                'material_id' => $materialId,
                'average_score' => $average,
            ],
        ], 200);
    }

    /**
     * Mendapatkan rata-rata nilai kelas pada mata pelajaran tertentu.
     *
     * @param int $classRoomId
     * @param int $materialId
     * @return JsonResponse
     */
    public function getAverageByClassRoomAndMaterial(int $classRoomId, int $materialId): JsonResponse
    {
        $average = $this->gradeService->getAverageGradeByClassRoomAndMaterial($classRoomId, $materialId);
        return response()->json([
            'status' => 'success',
            'data' => [
                'class_room_id' => $classRoomId,
                'material_id' => $materialId,
                'average_score' => $average,
            ],
        ], 200);
    }
}
