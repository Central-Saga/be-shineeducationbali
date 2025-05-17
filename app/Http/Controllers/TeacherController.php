<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Services\Contracts\TeacherServiceInterface;

class TeacherController extends Controller
{
    /**
     * @var TeacherServiceInterface $teacherService
     */
    protected $teacherService;

    /**
     * Konstruktor TeacherController.
     */
    public function __construct(TeacherServiceInterface $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $teachers = $this->teacherService->getAllTeachers();
        } elseif ($status == 1) {
            $teachers = $this->teacherService->getActiveTeachers();
        } elseif ($status == 0) {
            $teachers = $this->teacherService->getInactiveTeachers();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return TeacherResource::collection($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherStoreRequest $request)
    {
        $teacher = $this->teacherService->createTeacher($request->all());
        if (!$teacher) {
            return response()->json(['message' => 'Gagal membuat data guru'], 400);
        }
        return new TeacherResource($teacher);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = $this->teacherService->getTeacherById($id);
        if (!$teacher) {
            return response()->json(['message' => 'Data guru tidak ditemukan'], 404);
        }
        return new TeacherResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherUpdateRequest $request, string $id)
    {
        $teacher = $this->teacherService->updateTeacher($id, $request->all());
        if (!$teacher) {
            return response()->json(['message' => 'Gagal mengubah data guru'], 400);
        }
        return new TeacherResource($teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // 1. Check if teacher exists and load related data
            $teacher = $this->teacherService->getTeacherById($id);
            if (!$teacher) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data guru tidak ditemukan',
                    'error' => 'Teacher not found with ID ' . $id
                ], 404);
            }

            // Load related data for the response
            $teacherData = [
                'id' => $id,
                'name' => $teacher->user->name ?? 'Unknown',
                'subject' => $teacher->subject->name ?? null,
                'status' => $teacher->status,
                'deleted_at' => now()->toISOString()
            ];

            // 2. Attempt to delete the teacher
            $deleted = $this->teacherService->deleteTeacher($id);
            if (!$deleted) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data guru',
                    'error' => 'Failed to delete teacher with ID ' . $id,
                    'details' => 'Teacher could not be deleted. They may have related records that need to be handled first.'
                ], 400);
            }

            // 3. Return success response with teacher details
            return response()->json([
                'status' => 'success',
                'message' => 'Data guru berhasil dihapus',
                'data' => $teacherData,
                'details' => [
                    'message' => 'The teacher has been successfully deleted from the system. All associated records have been removed.',
                    'timestamp' => now()->toISOString()
                ]
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to delete teacher:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data guru',
                'error' => $e->getMessage(),
                'details' => 'An unexpected error occurred while attempting to delete the teacher. Please try again or contact support if the problem persists.'
            ], 500);
        }
    }
}