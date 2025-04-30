<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Http\Requests\AssetStoreRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Http\Requests\AssetMultipleStoreRequest;
use App\Services\Contracts\AssetServiceInterface;

class AssetController extends Controller
{
    /**
     * @var AssetServiceInterface
     */
    protected $assetService;

    /**
     * Konstruktor AssetController.
     *
     * @param AssetServiceInterface $assetService
     */
    public function __construct(AssetServiceInterface $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Menampilkan semua asset untuk model tertentu.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $modelType = $request->query('model_type');
        $modelId = $request->query('model_id');

        if (!$modelType || !$modelId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter model_type dan model_id diperlukan'
            ], 400);
        }

        $assets = $this->assetService->getAssets($modelType, $modelId);

        return AssetResource::collection($assets)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Menambahkan asset baru ke model.
     *
     * @param AssetStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request dengan file upload:
     * - model_type: "gallery"
     * - model_id: 1
     * - file: [file.jpg]
     * - description: "Deskripsi asset"
     */
    public function store(AssetStoreRequest $request)
    {
        $data = $request->validated();
        $modelType = $data['model_type'];
        $modelId = $data['model_id'];

        $asset = $this->assetService->addAsset($modelType, $modelId, $data);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan asset'
            ], 500);
        }

        return (new AssetResource($asset))
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil ditambahkan',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan asset tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $asset = $this->assetService->getAssetById($id);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return (new AssetResource($asset))->response();
    }

    /**
     * Memperbarui asset tertentu.
     *
     * @param AssetUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request:
     * {
     *   "file_path": "/storage/assets/newfile.pdf",
     *   "description": "Deskripsi asset baru"
     * }
     */
    public function update(AssetUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $asset = $this->assetService->updateAsset($id, $data);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return (new AssetResource($asset))
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil diperbarui',
            ])
            ->response();
    }

    /**
     * Menghapus asset tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $result = $this->assetService->deleteAsset($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Asset berhasil dihapus'
        ]);
    }

    /**
     * Menambahkan multiple asset ke model.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request dengan multiple files upload:
     * - model_type: "gallery"
     * - model_id: 1
     * - files[0]: [file1.jpg]
     * - files[1]: [file2.jpg]
     * - file_descriptions[0]: "Deskripsi gambar 1"
     * - file_descriptions[1]: "Deskripsi gambar 2"
     */
    public function storeMultiple(Request $request)
    {
        $validatedData = $request->validate([
            'model_type' => 'required|string|in:students,teachers,programs,assignments,materials',
            'model_id' => 'required|integer',
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
            'file_descriptions' => 'nullable|array',
            'file_descriptions.*' => 'nullable|string',
        ]);

        $modelType = $validatedData['model_type'];
        $modelId = $validatedData['model_id'];

        $assets = $this->assetService->addMultipleAssets($modelType, $modelId, $validatedData);

        if (empty($assets)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan asset'
            ], 500);
        }

        return AssetResource::collection($assets)
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil ditambahkan',
            ])
            ->response()
            ->setStatusCode(201);
    }
}
