<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Services\Contracts\StudentServiceInterface;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * @var StudentServiceInterface $studentService
     */
    protected $studentService;

    /**
     * Konstruktor StudentController.
     */
    public function __construct(StudentServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Menampilkan semua data student dengan opsi filter berdasarkan status.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $students = $this->studentService->getAllStudents();
        } elseif ($status == 1) {
            $students = $this->studentService->getActiveStudents();
        } elseif ($status == 0) {
            $students = $this->studentService->getInactiveStudents();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return StudentResource::collection($students);
    }

    /**
     * Menyimpan data student baru.
     *
     * @param StudentStoreRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function store(StudentStoreRequest $request)
    {
        try {
            $student = $this->studentService->createStudent($request->validated());
            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat data student'
                ], 400);
            }
            return new StudentResource($student);
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat data student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan data student berdasarkan ID.
     *
     * @param string $id
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            $student = $this->studentService->getStudentById($id);
            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data student tidak ditemukan'
                ], 404);
            }
            return new StudentResource($student);
        } catch (\Exception $e) {
            \Log::error('Error retrieving student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data student'
            ], 500);
        }
    }

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param StudentUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function update(StudentUpdateRequest $request, string $id)
    {
        try {
            $student = $this->studentService->updateStudent($id, $request->validated());
            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengubah data student'
                ], 400);
            }
            return new StudentResource($student);
        } catch (\Exception $e) {
            \Log::error('Error updating student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengubah data student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified student from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $student = $this->studentService->getStudentById($id);
            if (!$student) {
                Log::warning("Attempt to delete non-existent student with ID: {$id}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            $studentName = $student->user ? $student->user->name : 'N/A';
            Log::info("Attempting to delete student", [
                'student_id' => $id,
                'student_name' => $studentName
            ]);

            $deleted = $this->studentService->deleteStudent($id);

            if ($deleted) {
                Log::info("Successfully deleted student", [
                    'student_id' => $id,
                    'student_name' => $studentName
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data siswa berhasil dihapus'
                ]);
            } 

            Log::error("Failed to delete student", [
                'student_id' => $id,
                'student_name' => $studentName,
                'reason' => 'Delete operation returned false'
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data siswa. Silakan coba lagi atau hubungi administrator.'
            ], 500);

        } catch (\Exception $e) {
            Log::error("Exception occurred while deleting student", [
                'student_id' => $id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Berikan pesan error yang lebih spesifik berdasarkan jenis exception
            $errorMessage = 'Gagal menghapus data siswa. ';
            if ($e instanceof \Illuminate\Database\QueryException) {
                if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                    $errorMessage .= 'Siswa masih memiliki data terkait yang tidak dapat dihapus.';
                } else {
                    $errorMessage .= 'Terjadi kesalahan pada database.';
                }
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }

            return response()->json([
                'status' => 'error',
                'message' => $errorMessage
            ], 500);
        }
    }
}
