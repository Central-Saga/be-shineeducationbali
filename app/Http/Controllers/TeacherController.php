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
    public function index()
    {
        $teachers = $this->teacherService->getAllTeachers();
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
        $deleted = $this->teacherService->deleteTeacher($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus data guru'], 400);
        }
        return response()->json(null, 204);
    }
}