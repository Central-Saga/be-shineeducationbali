<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeCategoryStoreRequest;
use App\Http\Requests\GradeCategoryUpdateRequest;
use App\Http\Resources\GradeCategoryResource;
use App\Services\Interfaces\GradeCategoryServiceInterface;
use Illuminate\Http\JsonResponse;

class GradeCategoryController extends Controller
{
    protected $gradeCategoryService;

    public function __construct(GradeCategoryServiceInterface $gradeCategoryService)
    {
        $this->gradeCategoryService = $gradeCategoryService;
    }

    /**
     * Display a listing of the grade categories.
     */
    public function index(): JsonResponse
    {
        $gradeCategories = $this->gradeCategoryService->getAllGradeCategories();
        return response()->json([
            'data' => GradeCategoryResource::collection($gradeCategories),
        ], 200);
    }

    /**
     * Store a newly created grade category in storage.
     */
    public function store(GradeCategoryStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $gradeCategory = $this->gradeCategoryService->createGradeCategory($data);
        return response()->json([
            'message' => 'Grade category created successfully.',
            'data' => new GradeCategoryResource($gradeCategory),
        ], 201);
    }

    /**
     * Display the specified grade category.
     */
    public function show(int $id): JsonResponse
    {
        $gradeCategory = $this->gradeCategoryService->getGradeCategoryById($id);
        return response()->json([
            'data' => new GradeCategoryResource($gradeCategory),
        ], 200);
    }

    /**
     * Update the specified grade category in storage.
     */
    public function update(GradeCategoryUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $gradeCategory = $this->gradeCategoryService->updateGradeCategory($id, $data);
        return response()->json([
            'message' => 'Grade category updated successfully.',
            'data' => new GradeCategoryResource($gradeCategory),
        ], 200);
    }

    /**
     * Remove the specified grade category from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->gradeCategoryService->deleteGradeCategory($id);
        return response()->json([
            'message' => 'Grade category deleted successfully.',
        ], 200);
    }
}
