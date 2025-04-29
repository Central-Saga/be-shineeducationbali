<?php

namespace App\Repositories\Eloquent;

use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AssetRepository implements AssetRepositoryInterface
{
    /**
     * @var Asset
     */
    protected $model;

    /**
     * AssetRepository constructor.
     *
     * @param Asset $model
     */
    public function __construct(Asset $model)
    {
        $this->model = $model;
    }

    /**
     * Get all assets
     *
     * @return Collection
     */
    public function getAllAssets()
    {
        return $this->model->all();
    }

    /**
     * Get asset by id
     *
     * @param int $id
     * @return Asset|null
     */
    public function getAssetById($id): ?Asset
    {
        $model = $this->model->find($id);
        return $model instanceof Asset ? $model : null;
    }

    /**
     * Create asset
     *
     * @param array $data
     * @return Asset
     */
    public function createAsset(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update asset
     *
     * @param int $id
     * @param array $data
     * @return Asset
     * @throws \Exception
     */
    public function updateAsset($id, array $data)
    {
        $asset = $this->getAssetById($id);

        if (!$asset) {
            throw new \Exception("Asset with ID {$id} not found");
        }
        
        $asset->update($data);
        return $asset;
    }

    /**
     * Delete asset
     *
     * @param int $id
     * @return bool
     */
    public function deleteAsset($id)
    {
        $asset = $this->getAssetById($id);

        if ($asset) {
            return $asset->delete();
        }

        return false;
    }
}
