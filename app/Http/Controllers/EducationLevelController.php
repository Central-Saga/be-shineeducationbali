<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EducationLevelResource;
use App\Http\Requests\EducationLevelStoreRequest;
use App\Http\Requests\EducationLevelUpdateRequest;
use App\Services\Contracts\EducationLevelServiceInterface;

class EducationLevelController extends Controller
{
    /**
     * @var EducationLevelServiceInterface $educationLevelService
     */
    protected $educationLevelService;

    /**
     * Konstruktor EducationLevelController.
     */
    public function __construct(EducationLevelServiceInterface $educationLevelService)
    {
        $this->educationLevelService = $educationLevelService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $educationLevels = $this->educationLevelService->getAllEducationLevels();
        return EducationLevelResource::collection($educationLevels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EducationLevelStoreRequest $request)
    {
        $educationLevel = $this->educationLevelService->createEducationLevel($request->all());
        if (!$educationLevel) {
            return response()->json(['message' => 'Gagal membuat level pendidikan'], 400);
        }
        return new EducationLevelResource($educationLevel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $educationLevel = $this->educationLevelService->getEducationLevelById($id);
        if (!$educationLevel) {
            return response()->json(['message' => 'Level pendidikan tidak ditemukan'], 404);
        }
        return new EducationLevelResource($educationLevel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EducationLevelUpdateRequest $request, string $id)
    {
        $educationLevel = $this->educationLevelService->updateEducationLevel($id, $request->all());
        if (!$educationLevel) {
            return response()->json(['message' => 'Gagal mengubah level pendidikan'], 400);
        }
        return new EducationLevelResource($educationLevel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->educationLevelService->deleteEducationLevel($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus level pendidikan'], 400);
        }
        return response()->json(['message' => 'Berhasil menghapus level pendidikan'], 200);
    }
}
