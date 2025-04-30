<?php

namespace App\Services\Implementations;

use App\Models\Asset;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Services\Contracts\AssetServiceInterface;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AssetService implements AssetServiceInterface
{
    /**
     * @var AssetRepositoryInterface
     */
    protected $repository;

    /**
     * Model mapping
     */
    protected $modelMapping = [
        'material' => Material::class,
        'assignment' => Assignment::class,
        'certificate' => Certificate::class,
        'student' => Student::class,
        'teacher' => Teacher::class,
        'classroom' => ClassRoom::class,
    ];

    /**
     * AssetService constructor.
     *
     * @param AssetRepositoryInterface $repository
     */
    public function __construct(AssetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all assets for a specific model
     *
     * @param string $modelType
     * @param int $modelId
     * @return Collection
     */
    public function getAssets($modelType, $modelId)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            return \collect([]);
        }

        return $model->assets;
    }

    /**
     * Get asset by id
     *
     * @param int $id
     * @return Asset
     */
    public function getAssetById($id)
    {
        return $this->repository->getAssetById($id);
    }

    /**
     * Add asset to a model
     *
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return Asset
     * @throws \Exception
     */
    public function addAsset($modelType, $modelId, array $data)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            throw new \Exception("Model of type {$modelType} with ID {$modelId} not found");
        }

        // Proses file upload
        if (isset($data['file'])) {
            $file = $data['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs($modelType, $fileName, 'public');
            
            // Get file size in KB
            $sizeInBytes = $file->getSize();
            $sizeInKB = ceil($sizeInBytes / 1024);

            // Buat asset baru dengan file fisik
            $assetData = [
                'assetable_id' => $model->id,
                'assetable_type' => get_class($model),
                'file_path' => $filePath,
                'size_kb' => $sizeInKB,
                'upload_date' => now(),
                'description' => $data['description'] ?? null,
                'storage_type' => $data['storage_type'] ?? 'local',
            ];

            $asset = $this->repository->createAsset($assetData);
            Log::info('Created asset from uploaded file', ['asset_id' => $asset->id]);
            return $asset;
        }
        // Process external files
        elseif (isset($data['file_path'])) {
            $assetData = [
                'assetable_id' => $model->id,
                'assetable_type' => get_class($model),
                'file_path' => $data['file_path'],
                'size_kb' => $data['size_kb'] ?? 0,
                'upload_date' => $data['upload_date'] ?? now(),
                'description' => $data['description'] ?? null,
                'storage_type' => $data['storage_type'] ?? 's3',
            ];

            $asset = $this->repository->createAsset($assetData);
            Log::info('Created asset from external path', ['asset_id' => $asset->id]);
            return $asset;
        }

        throw new \Exception("No file or file_path provided for asset creation");
    }

    /**
     * Add multiple assets to a model
     *
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return array
     */
    public function addMultipleAssets($modelType, $modelId, array $data)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            return [];
        }

        $createdAssets = [];

        // Proses multiple files upload
        if (isset($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $index => $file) {
                $assetData = [
                    'file' => $file,
                    'description' => $data['file_descriptions'][$index] ?? null,
                    'storage_type' => $data['storage_types'][$index] ?? 'local'
                ];

                $asset = $this->addAsset($modelType, $modelId, $assetData);
                if ($asset) {
                    $createdAssets[] = $asset;
                }
            }
        }

        // Proses multiple external paths
        if (isset($data['file_paths']) && is_array($data['file_paths'])) {
            foreach ($data['file_paths'] as $index => $filePath) {
                $assetData = [
                    'file_path' => $filePath,
                    'size_kb' => $data['sizes_kb'][$index] ?? 0,
                    'upload_date' => $data['upload_dates'][$index] ?? now(),
                    'description' => $data['descriptions'][$index] ?? null,
                    'storage_type' => $data['storage_types'][$index] ?? 's3',
                ];

                $asset = $this->addAsset($modelType, $modelId, $assetData);
                if ($asset) {
                    $createdAssets[] = $asset;
                }
            }
        }

        return $createdAssets;
    }

    /**
     * Update asset
     *
     * @param int $assetId
     * @param array $data
     * @return Asset
     */
    public function updateAsset($assetId, array $data)
    {
        $asset = $this->getAssetById($assetId);

        if (!$asset) {
            throw new \Exception("Asset with ID {$assetId} not found");
        }

        $updateData = [];

        if (isset($data['file_path'])) {
            $updateData['file_path'] = $data['file_path'];
        }

        if (isset($data['size_kb'])) {
            $updateData['size_kb'] = $data['size_kb'];
        }

        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }

        if (isset($data['storage_type']) && in_array($data['storage_type'], ['local', 'cloud', 's3', 'google_drive'])) {
            $updateData['storage_type'] = $data['storage_type'];
        }

        if (isset($data['upload_date'])) {
            $updateData['upload_date'] = $data['upload_date'];
        }

        if (!empty($updateData)) {
            $updatedAsset = $this->repository->updateAsset($assetId, $updateData);
            return $updatedAsset !== null ? $updatedAsset : $asset;
        }

        return $asset;
    }

    /**
     * Delete asset
     *
     * @param int $assetId
     * @return bool
     */
    public function deleteAsset($assetId)
    {
        $asset = $this->getAssetById($assetId);

        if (!$asset) {
            return false;
        }

        // Only delete the file from local storage
        if ($asset->storage_type === 'local' && Storage::disk('public')->exists($asset->file_path)) {
            Storage::disk('public')->delete($asset->file_path);
        }

        // Delete asset record
        return $this->repository->deleteAsset($assetId);
    }

    /**
     * Get model instance by type and id
     *
     * @param string $modelType
     * @param int $modelId
     * @return Model|null
     */
    public function getModelInstance($modelType, $modelId)
    {
        $modelClass = $this->getModelClass($modelType);

        if (!$modelClass) {
            return null;
        }

        return $modelClass::find($modelId);
    }

    /**
     * Get model class by type
     *
     * @param string $modelType
     * @return string|null
     */
    protected function getModelClass($modelType)
    {
        return $this->modelMapping[strtolower($modelType)] ?? null;
    }
}
