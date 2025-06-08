<?php

namespace App\Repositories\Contracts;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

interface AssetRepositoryInterface
{
    /**
     * Get all assets
     * @return Collection
     */
    public function getAllAssets();

    /**
     * Get asset by id
     * @param int $id
     * @return Asset|null
     */
    public function getAssetById($id): ?Asset;

    /**
     * Create asset
     * @param array $data
     * @return Asset
     */
    public function createAsset(array $data);

    /**
     * Update asset
     * @param int $id
     * @param array $data
     * @return Asset|null
     */
    public function updateAsset($id, array $data);

    /**
     * Delete asset
     * @param int $id
     * @return bool
     */
    public function deleteAsset($id);
}
