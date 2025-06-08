<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MaterialResource;
use App\Http\Requests\MaterialStoreRequest;
use App\Http\Requests\MaterialUpdateRequest;
use App\Services\Contracts\MaterialServiceInterface;

class MaterialController extends Controller
{

    protected $materialService;

    public function __construct(MaterialServiceInterface $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $materials = $this->materialService->getAllMaterials();
        } elseif ($status == 1) {
            $materials = $this->materialService->getActiveMaterials();
        } elseif ($status == 0) {
            $materials = $this->materialService->getInactiveMaterials();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return MaterialResource::collection($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaterialStoreRequest $request)
    {
        $material = $this->materialService->createMaterial($request->all());
        if (!$material) {
            return response()->json(['message' => 'Gagal membuat material'], 400);
        }
        return new MaterialResource($material);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = $this->materialService->getMaterialById($id);
        if (!$material) {
            return response()->json(['message' => 'Material tidak ditemukan'], 404);
        }
        return new MaterialResource($material);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MaterialUpdateRequest $request, string $id)
    {
        $material = $this->materialService->updateMaterial($id, $request->all());
        if (!$material) {
            return response()->json(['message' => 'Gagal memperbarui material'], 400);
        }
        return new MaterialResource($material);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->materialService->deleteMaterial($id);
        if (!$result) {
            return response()->json(['message' => 'Gagal menghapus material'], 400);
        }
        return response()->json(['message' => 'Material berhasil dihapus'], 200);
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $material = $this->materialService->updateMaterialStatus($id, $request->validated());
        if (!$material) {
            return response()->json(['message' => 'Gagal memperbarui status material'], 400);
        }
        return new MaterialResource($material);
    }
}
