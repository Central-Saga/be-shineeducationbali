<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Http\Requests\SubjectStoreRequest;
use App\Http\Requests\SubjectUpdateRequest;
use App\Services\Contracts\SubjectServiceInterface;

class SubjectController extends Controller
{
    /**
     * @var SubjectServiceInterface $subjectService
     */
    protected $subjectService;

    /**
     * Konstruktor SubjectController.
     */
    public function __construct(SubjectServiceInterface $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = $this->subjectService->getAllSubjects();
        return SubjectResource::collection($subjects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectStoreRequest $request)
    {
        $subject = $this->subjectService->createSubject($request->all());
        if (!$subject) {
            return response()->json(['message' => 'Gagal membuat mata pelajaran'], 400);
        }
        return new SubjectResource($subject);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        if (!$subject) {
            return response()->json(['message' => 'Mata pelajaran tidak ditemukan'], 404);
        }
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectUpdateRequest $request, string $id)
    {
        $subject = $this->subjectService->updateSubject($id, $request->all());
        if (!$subject) {
            return response()->json(['message' => 'Gagal mengubah mata pelajaran'], 400);
        }
        return new SubjectResource($subject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->subjectService->deleteSubject($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus mata pelajaran'], 400);
        }
        return response()->json(null, 204);
    }
}
