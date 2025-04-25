<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;
use App\Http\Requests\ClassRoomStoreRequest;
use App\Http\Requests\ClassRoomUpdateRequest;
use App\Services\Contracts\ClassRoomServiceInterface;

class ClassRoomController extends Controller
{
    protected $classRoomService;

    public function __construct(ClassRoomServiceInterface $classRoomService)
    {
        $this->classRoomService = $classRoomService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $status = $request->query('status');

            if ($status === null) {
                $classRooms = $this->classRoomService->getAllClassRooms();
            } elseif ($status == 1) {
                $classRooms = $this->classRoomService->getActiveClassRooms();
            } elseif ($status == 0) {
                $classRooms = $this->classRoomService->getInactiveClassRooms();
            } else {
                return response()->json([
                    'message' => 'Parameter status tidak valid'
                ], 400);
            }

            return ClassRoomResource::collection($classRooms);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassRoomStoreRequest $request)
    {
        try {
            $classRoom = $this->classRoomService->createClassRoom($request->validated());
            return new ClassRoomResource($classRoom);
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
            $classRoom = $this->classRoomService->getClassRoomById($id);
            if (!$classRoom) {
                return response()->json([
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }
            return new ClassRoomResource($classRoom);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassRoomUpdateRequest $request, string $id)
    {
        try {
            $classRoom = $this->classRoomService->updateClassRoom($id, $request->validated());
            if (!$classRoom) {
                return response()->json([
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }
            return new ClassRoomResource($classRoom);
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
            $result = $this->classRoomService->deleteClassRoom($id);
            if (!$result) {
                return response()->json([
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'message' => 'Kelas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Aktif,Non Aktif'
            ]);

            $classRoom = $this->classRoomService->updateClassRoom($id, $request->validated());
            if (!$classRoom) {
                return response()->json([
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }
            return new ClassRoomResource($classRoom);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Menambahkan siswa ke classroom
     */
    public function attachStudent(Request $request, string $id)
    {
        try {
            $classRoom = $this->classRoomService->attachStudentToClassRoom($id, $request->student_id);
            return new ClassRoomResource($classRoom);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Menghapus siswa dari classroom
     */
    public function detachStudent(Request $request, string $id)
    {
        try {
            $classRoom = $this->classRoomService->detachStudentFromClassRoom($id, $request->student_id);
            return new ClassRoomResource($classRoom);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Mengambil daftar siswa dalam classroom
     */
    public function getStudents(string $id)
    {
        try {
            $students = $this->classRoomService->getStudentsInClassRoom($id);
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
