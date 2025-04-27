<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateStoreRequest;
use App\Http\Requests\CertificateUpdateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\Contracts\CertificateServiceInterface;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateServiceInterface $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Menampilkan daftar semua certificate.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $certificates = $this->certificateService->getAllCertificates();
        return response()->json([
            'status' => 'success',
            'data' => CertificateResource::collection($certificates),
        ], 200);
    }

    /**
     * Menyimpan certificate baru.
     *
     * @param CertificateStoreRequest $request
     * @return JsonResponse
     */
    public function store(CertificateStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $certificate = $this->certificateService->createCertificate($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate berhasil dibuat',
            'data' => new CertificateResource($certificate),
        ], 201);
    }

    /**
     * Menampilkan detail certificate berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $certificate = $this->certificateService->getCertificateById($id);
        return response()->json([
            'status' => 'success',
            'data' => new CertificateResource($certificate),
        ], 200);
    }

    /**
     * Memperbarui certificate berdasarkan ID.
     *
     * @param CertificateUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CertificateUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $certificate = $this->certificateService->updateCertificate($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate berhasil diperbarui',
            'data' => new CertificateResource($certificate),
        ], 200);
    }

    /**
     * Menghapus certificate berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->certificateService->deleteCertificate($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Certificate berhasil dihapus',
        ], 200);
    }
}
