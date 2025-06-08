<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveStoreRequest;
use App\Http\Requests\LeaveUpdateRequest;
use App\Http\Resources\LeaveResource;
use App\Services\Contracts\LeaveServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Instance dari LeaveServiceInterface.
     *
     * @var LeaveServiceInterface
     */
    protected $leaveService;

    /**
     * Konstruktor untuk menginisialisasi service.
     *
     * @param LeaveServiceInterface $leaveService
     */
    public function __construct(LeaveServiceInterface $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Menampilkan daftar data leave berdasarkan status atau semua data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua leave
            $leaves = $this->leaveService->getAllLeaves();
        } elseif (is_numeric($status)) {
            // Convert string number to integer
            $statusCode = (int) $status;
            if ($statusCode >= 0 && $statusCode <= 2) {
                $leaves = $this->leaveService->getLeaveByStatus($statusCode);
            } else {
                return response()->json([
                    'message' => 'Parameter status tidak valid. Gunakan: 0 (ditolak), 1 (disetujui), atau 2 (menunggu konfirmasi)'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Parameter status harus berupa angka. Gunakan: 0 (ditolak), 1 (disetujui), atau 2 (menunggu konfirmasi)'
            ], 400);
        }

        if (!$leaves || $leaves->isEmpty()) {
            return response()->json([
                'message' => 'Data leave tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar data leave berhasil diambil',
            'data' => LeaveResource::collection($leaves)
        ], 200);
    }

    /**
     * Menyimpan data leave baru.
     *
     * @param LeaveStoreRequest $request
     * @return JsonResponse
     */
    public function store(LeaveStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $leave = $this->leaveService->createLeave($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data leave berhasil dibuat.',
            'data' => new LeaveResource($leave),
        ], 201);
    }

    /**
     * Menampilkan detail data leave berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $leave = $this->leaveService->getLeaveById($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail data leave berhasil diambil.',
                'data' => new LeaveResource($leave),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data leave tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Memperbarui data leave berdasarkan ID.
     *
     * @param LeaveUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LeaveUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $leave = $this->leaveService->updateLeave($id, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data leave berhasil diperbarui.',
                'data' => new LeaveResource($leave),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data leave tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Menghapus data leave berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->leaveService->deleteLeave($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data leave berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data leave tidak ditemukan.',
            ], 404);
        }
    }
}
