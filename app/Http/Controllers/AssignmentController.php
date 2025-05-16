<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Http\Requests\AssignmentStoreRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Services\Contracts\AssignmentServiceInterface;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentServiceInterface $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $status = $request->query('status');

            // Log untuk debugging
            Log::info('Assignment status query:', ['status' => $status]);

            if ($status === null) {
                $assignments = $this->assignmentService->getAllAssignments();
            } else {
                // Konversi status numerik ke string status yang sesuai
                $statusMap = [
                    '0' => 'Dalam Pengajuan',
                    '1' => 'Terselesaikan',
                    '2' => 'Ditolak',
                    '3' => 'Belum Terselesaikan'
                ];

                if (!isset($statusMap[$status])) {
                    return response()->json([
                        'message' => 'Status tidak valid. Status yang tersedia: 0 (Dalam Pengajuan), 1 (Terselesaikan), 2 (Ditolak), 3 (Belum Terselesaikan)'
                    ], 400);
                }

                // Log untuk debugging
                Log::info('Mapped status:', ['mapped_status' => $statusMap[$status]]);

                // Gunakan repository untuk mengambil data berdasarkan status
                $assignments = $this->assignmentService->getAssignmentByStatus($statusMap[$status]);
            }

            // Log response untuk debugging
            Log::info('Found assignments:', [
                'count' => $assignments->count(),
                'first_item' => $assignments->first()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => AssignmentResource::collection($assignments)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in AssignmentController@index: ' . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssignmentStoreRequest $request)
    {
        try {
            $assignment = $this->assignmentService->createAssignment($request->validated());
            return new AssignmentResource($assignment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $assignment = $this->assignmentService->getAssignmentById($id);
            if (!$assignment) {
                return response()->json([
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }
            return new AssignmentResource($assignment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssignmentUpdateRequest $request, string $id)
    {
        try {
            $assignment = $this->assignmentService->updateAssignment($id, $request->validated());
            if (!$assignment) {
                return response()->json([
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }
            return new AssignmentResource($assignment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->assignmentService->deleteAssignment($id);
            if (!$result) {
                return response()->json([
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'message' => 'Tugas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
