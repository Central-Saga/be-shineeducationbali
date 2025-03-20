<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Services\Contracts\StudentServiceInterface;
use Illuminate\Http\Request;

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
     * @param StoreStudentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreStudentRequest $request)
    {
        $student = $this->studentService->createStudent($request->validated());
        if (!$student) {
            return response()->json(['message' => 'Gagal membuat data student'], 400);
        }
        return new StudentResource($student);
    }

    /**
     * Menampilkan data student berdasarkan ID.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $student = $this->studentService->getStudentById($id);
        if (!$student) {
            return response()->json(['message' => 'Data student tidak ditemukan'], 404);
        }
        return new StudentResource($student);
    }

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param UpdateStudentRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $student = $this->studentService->updateStudent($id, $request->validated());
        if (!$student) {
            return response()->json(['message' => 'Gagal mengubah data student'], 400);
        }
        return new StudentResource($student);
    }

    /**
     * Menghapus data student berdasarkan ID.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $deleted = $this->studentService->deleteStudent($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus data student'], 400);
        }
        return response()->json(null, 204);
    }
}
