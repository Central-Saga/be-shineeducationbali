<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentAttendanceStoreRequest;
use App\Http\Requests\StudentAttendanceUpdateRequest;
use App\Http\Resources\StudentAttendanceResource;
use App\Services\Contracts\StudentAttendanceServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    protected $studentAttendanceService;

    public function __construct(StudentAttendanceServiceInterface $studentAttendanceService)
    {
        $this->studentAttendanceService = $studentAttendanceService;
    }

    /**
     * Menampilkan daftar semua kehadiran siswa.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAllStudentAttendances();
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menyimpan data kehadiran siswa baru.
     *
     * @param StudentAttendanceStoreRequest $request
     * @return JsonResponse
     */
    public function store(StudentAttendanceStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $attendance = $this->studentAttendanceService->createStudentAttendance($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil dibuat',
            'data' => new StudentAttendanceResource($attendance),
        ], 201);
    }

    /**
     * Menampilkan detail kehadiran siswa berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $attendance = $this->studentAttendanceService->getStudentAttendanceById($id);
        return response()->json([
            'status' => 'success',
            'data' => new StudentAttendanceResource($attendance),
        ], 200);
    }

    /**
     * Memperbarui data kehadiran siswa berdasarkan ID.
     *
     * @param StudentAttendanceUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StudentAttendanceUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $attendance = $this->studentAttendanceService->updateStudentAttendance($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil diperbarui',
            'data' => new StudentAttendanceResource($attendance),
        ], 200);
    }

    /**
     * Menghapus data kehadiran siswa berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->studentAttendanceService->deleteStudentAttendance($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil dihapus',
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan siswa.
     *
     * @param int $studentId
     * @return JsonResponse
     */
    public function getByStudent(int $studentId): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByStudentId($studentId);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan kelas.
     *
     * @param int $classRoomId
     * @return JsonResponse
     */
    public function getByClassRoom(int $classRoomId): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByClassRoomId($classRoomId);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan guru.
     *
     * @param int $teacherId
     * @return JsonResponse
     */
    public function getByTeacher(int $teacherId): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByTeacherId($teacherId);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan tanggal.
     *
     * @param string $date Format: yyyy-mm-dd
     * @return JsonResponse
     */
    public function getByDate(string $date): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByDate($date);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan rentang tanggal.
     *
     * @param string $startDate Format: yyyy-mm-dd
     * @param string $endDate Format: yyyy-mm-dd
     * @return JsonResponse
     */
    public function getByDateRange(string $startDate, string $endDate): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByDateRange($startDate, $endDate);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Menampilkan data kehadiran berdasarkan status.
     *
     * @param string $status ('present' atau 'absent')
     * @return JsonResponse
     */
    public function getByStatus(string $status): JsonResponse
    {
        $attendances = $this->studentAttendanceService->getAttendancesByStatus($status);
        return response()->json([
            'status' => 'success',
            'data' => StudentAttendanceResource::collection($attendances),
        ], 200);
    }

    /**
     * Mendapatkan ringkasan kehadiran siswa (total hadir dan tidak hadir).
     *
     * @param int $studentId
     * @return JsonResponse
     */
    public function getStudentAttendanceSummary(int $studentId): JsonResponse
    {
        $summary = $this->studentAttendanceService->getStudentAttendanceSummary($studentId);
        return response()->json([
            'status' => 'success',
            'data' => $summary,
        ], 200);
    }
}
