<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Http\Requests\ProgramStoreRequest;
use App\Http\Requests\ProgramUpdateRequest;
use App\Services\Contracts\ProgramServiceInterface;

class ProgramController extends Controller
{
    protected $programService;

    public function __construct(ProgramServiceInterface $programService)
    {
        $this->programService = $programService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $programs = $this->programService->getAllPrograms();
        } elseif ($status == 1) {
            $programs = $this->programService->getActivePrograms();
        } elseif ($status == 0) {
            $programs = $this->programService->getInactivePrograms();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return ProgramResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramStoreRequest $request)
    {
        $program = $this->programService->createProgram($request->all());
        if (!$program) {
            return response()->json(['message' => 'Gagal membuat program'], 400);
        }
        return new ProgramResource($program);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = $this->programService->getProgramById($id);
        if (!$program) {
            return response()->json(['message' => 'Program tidak ditemukan'], 404);
        }
        return new ProgramResource($program);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProgramUpdateRequest $request, string $id)
    {
        $program = $this->programService->updateProgram($id, $request->all());
        if (!$program) {
            return response()->json(['message' => 'Gagal memperbarui program'], 400);
        }
        return new ProgramResource($program);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->programService->deleteProgram($id);
        if (!$result) {
            return response()->json(['message' => 'Gagal menghapus program'], 400);
        }
        return response()->json(['message' => 'Program berhasil dihapus'], 200);
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $program = $this->programService->updateStatus($id, $request->validated());
        if (!$program) {
            return response()->json(['message' => 'Gagal memperbarui status program'], 400);
        }
        return new ProgramResource($program);
    }
}
