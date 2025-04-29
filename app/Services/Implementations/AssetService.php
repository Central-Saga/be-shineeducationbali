<?php

namespace App\Services\Implementations;

use App\Models\Asset;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Program;
use App\Models\Assignment;
use App\Models\Material;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Services\Contracts\AssetServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetService implements AssetServiceInterface
{
    /**
     * @var AssetRepositoryInterface
     */
    protected $assetRepository;

    /**
     * Model mapping
     */
    protected $modelMapping = [
        'students' => Student::class,
        'teachers' => Teacher::class,
        'programs' => Program::class,
        'assignments' => Assignment::class,
        'materials' => Material::class,
    ];

    /**
     * AssetService constructor.
     *
     * @param AssetRepositoryInterface $assetRepository
     */
    public function __construct(AssetRepositoryInterface $assetRepository)
    {
        $this->assetRepository = $assetRepository;
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
            return collect();
        }
        
        return $model->assets;
    }

    /**
     * Get asset by id
     * 
     * @param int $id
     * @return Asset|null
     */
    public function getAssetById($id)
    {
        return $this->assetRepository->getAssetById($id);
    }

    /**
     * Add asset to a model
     * 
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return Asset
     */
    public function addAsset($modelType, $modelId, array $data)
    {
        $model = $this->getModelInstance($modelType, $modelId);
        
        if (!$model) {
            throw new \Exception("Model {$modelType} with ID {$modelId} not found");
        }
        
        // Handle file upload if exists
        if (isset($data['file'])) {
            $file = $data['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assets/' . $modelType . '/' . $modelId, $fileName, 'public');
            
            $data['file_name'] = $fileName;
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
            
            unset($data['file']);
        }
        
        // Set model relation
        $data['assetable_type'] = 'App\\Models\\' . Str::studly(Str::singular($modelType));
        $data['assetable_id'] = $modelId;
        
        return $this->assetRepository->createAsset($data);
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
        $result = [];
        
        foreach ($data as $assetData) {
            $result[] = $this->addAsset($modelType, $modelId, $assetData);
        }
        
        return $result;
    }

    /**
     * Update asset
     * 
     * @param int $assetId
     * @param array $data
     * @return Asset|null
     */
    public function updateAsset($assetId, array $data)
    {
        $asset = $this->getAssetById($assetId);
        
        if (!$asset) {
            return null;
        }
        
        // Handle file update if exists
        if (isset($data['file'])) {
            // Delete old file if exists
            if ($asset->file_path) {
                Storage::disk('public')->delete($asset->file_path);
            }
            
            // Store new file
            $file = $data['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Get model from asset
            $modelType = Str::plural(Str::snake(class_basename($asset->assetable_type)));
            $modelId = $asset->assetable_id;
            
            $filePath = $file->storeAs('assets/' . $modelType . '/' . $modelId, $fileName, 'public');
            
            $data['file_name'] = $fileName;
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
            
            unset($data['file']);
        }
        
        return $this->assetRepository->updateAsset($assetId, $data);
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
        
        // Delete file from storage if exists
        if ($asset->file_path) {
            Storage::disk('public')->delete($asset->file_path);
        }
        
        return $this->assetRepository->deleteAsset($assetId);
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
        $model = $this->getModelClass($modelType);
        
        if (!$model) {
            return null;
        }
        
        return $model::find($modelId);
    }
    
    /**
     * Get model class name by type
     *
     * @param string $modelType
     * @return string|null
     */
    protected function getModelClass($modelType)
    {
        $type = strtolower($modelType);
        
        if (isset($this->modelMapping[$type])) {
            return $this->modelMapping[$type];
        }
        
        // Try to determine model class name from type string
        $modelClass = 'App\\Models\\' . Str::studly(Str::singular($type));
        
        return class_exists($modelClass) ? $modelClass : null;
    }
}
