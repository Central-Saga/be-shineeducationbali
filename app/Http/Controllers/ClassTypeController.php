<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassTypeResource;
use App\Http\Requests\ClassTypeStoreRequest;
use App\Http\Requests\ClassTypeUpdateRequest;
use App\Services\Contracts\ClassTypeServiceInterface;

class ClassTypeController extends Controller
{

    /**
     * @var ClassTypeServiceInterface $classTypeService
     */
    protected $classTypeService;

    /**
     * Konstruktor ClassTypeController.
     */
    public function __construct(ClassTypeServiceInterface $classTypeService)
    {
        $this->classTypeService = $classTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classTypes = $this->classTypeService->getAllClassTypes();
        return ClassTypeResource::collection($classTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassTypeStoreRequest $request)
    {
        $classType = $this->classTypeService->createClassType($request->all());
        if (!$classType) {
            return response()->json(['message' => 'Gagal membuat tipe kelas'], 400);
        }
        return new ClassTypeResource($classType);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classType = $this->classTypeService->getClassTypeById($id);
        if (!$classType) {
            return response()->json(['message' => 'Tipe kelas tidak ditemukan'], 404);
        }
        return new ClassTypeResource($classType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassTypeUpdateRequest $request, string $id)
    {
        $classType = $this->classTypeService->updateClassType($id, $request->all());
        if (!$classType) {
            return response()->json(['message' => 'Gagal mengubah tipe kelas'], 400);
        }
        return new ClassTypeResource($classType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->classTypeService->deleteClassType($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus tipe kelas'], 400);
        }
        return response()->json(null, 204);
    }
}
