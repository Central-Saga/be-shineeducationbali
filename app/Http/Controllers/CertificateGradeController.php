<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateGradeStoreRequest;
use App\Http\Requests\CertificateGradeUpdateRequest;
use App\Http\Resources\CertificateGradeResource;
use App\Services\Contracts\CertificateGradeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateGradeController extends Controller
{
    protected $certificateGradeService;

    public function __construct(CertificateGradeServiceInterface $certificateGradeService)
    {
        $this->certificateGradeService = $certificateGradeService;
    }

    /**
     * Menampilkan daftar semua certificate grade.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $certificateGrades = $this->certificateGradeService->getAllCertificateGrades();
        return response()->json([
            'status' => 'success',
            'data' => CertificateGradeResource::collection($certificateGrades),
        ], 200);
    }

    /**
     * Menyimpan certificate grade baru.
     *
     * @param CertificateGradeStoreRequest $request
     * @return JsonResponse
     */
    public function store(CertificateGradeStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $certificateGrade = $this->certificateGradeService->createCertificateGrade($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate Grade berhasil dibuat',
            'data' => new CertificateGradeResource($certificateGrade),
        ], 201);
    }

    /**
     * Menampilkan detail certificate grade berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $certificateGrade = $this->certificateGradeService->getCertificateGradeById($id);
        return response()->json([
            'status' => 'success',
            'data' => new CertificateGradeResource($certificateGrade),
        ], 200);
    }

    /**
     * Memperbarui certificate grade berdasarkan ID.
     *
     * @param CertificateGradeUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CertificateGradeUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $certificateGrade = $this->certificateGradeService->updateCertificateGrade($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate Grade berhasil diperbarui',
            'data' => new CertificateGradeResource($certificateGrade),
        ], 200);
    }

    /**
     * Menghapus certificate grade berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->certificateGradeService->deleteCertificateGrade($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate Grade berhasil dihapus',
        ], 200);
    }

    /**
     * Menampilkan certificate grades berdasarkan certificate ID.
     *
     * @param int $certificateId
     * @return JsonResponse
     */
    public function getByCertificateId(int $certificateId): JsonResponse
    {
        $certificateGrades = $this->certificateGradeService->getCertificateGradesByCertificateId($certificateId);
        return response()->json([
            'status' => 'success',
            'data' => CertificateGradeResource::collection($certificateGrades),
        ], 200);
    }

    /**
     * Menampilkan certificate grades berdasarkan grade ID.
     *
     * @param int $gradeId
     * @return JsonResponse
     */
    public function getByGradeId(int $gradeId): JsonResponse
    {
        $certificateGrades = $this->certificateGradeService->getCertificateGradesByGradeId($gradeId);
        return response()->json([
            'status' => 'success',
            'data' => CertificateGradeResource::collection($certificateGrades),
        ], 200);
    }
}
