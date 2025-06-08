<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\MaterialServiceInterface;
use App\Repositories\Contracts\MaterialRepositoryInterface;

class MaterialService implements MaterialServiceInterface
{
    protected $repository;

    const MATERIALS_ALL_CACHE_KEY = 'materials_all';
    const MATERIALS_ACTIVE_CACHE_KEY = 'materials_active';
    const MATERIALS_INACTIVE_CACHE_KEY = 'materials_inactive';

    public function __construct(MaterialRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua bahan ajar.
     *
     * @return mixed
     */
    public function getAllMaterials()
    {
        return Cache::remember(self::MATERIALS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllMaterials();
        });
    }

    /**
     * Mengambil bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMaterialById($id)
    {
        return $this->repository->getMaterialById($id);
    }

    /**
     * Mengambil bahan ajar berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMaterialByName($name)
    {
        return $this->repository->getMaterialByName($name);
    }

    /**
     * Membuat bahan ajar baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMaterial(array $data)
    {
        $result = $this->repository->createMaterial($data);
        $this->clearMaterialCaches();
        return $result;
    }

    /**
     * Memperbarui bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateMaterial($id, array $data)
    {
        $result = $this->repository->updateMaterial($id, $data);
        $this->clearMaterialCaches();
        return $result;
    }

    /**
     * Menghapus bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteMaterial($id)
    {
        $result = $this->repository->deleteMaterial($id);
        $this->clearMaterialCaches();

        return $result;
    }

    /**
     * Mengambil semua bahan ajar dengan status Aktif.
     *
     * @return mixed
     */
    public function getActiveMaterials()
    {
        return Cache::remember(self::MATERIALS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getAllMaterials()->where('status', 'Aktif');
        });
    }

    /**
     * Mengambil semua bahan ajar dengan status Non Aktif.
     *
     * @return mixed
     */
    public function getInactiveMaterials()
    {
        return Cache::remember(self::MATERIALS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getAllMaterials()->where('status', 'Non Aktif');
        });
    }

    /**
     * Menghapus semua cache bahan ajar
     *
     * @return void
     */
    protected function clearMaterialCaches()
    {
        Cache::forget(self::MATERIALS_ALL_CACHE_KEY);
        Cache::forget(self::MATERIALS_ACTIVE_CACHE_KEY);
        Cache::forget(self::MATERIALS_INACTIVE_CACHE_KEY);
    }
}
