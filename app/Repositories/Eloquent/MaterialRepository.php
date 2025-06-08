<?php

namespace App\Repositories\Eloquent;

use App\Models\Material;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\MaterialRepositoryInterface;

class MaterialRepository implements MaterialRepositoryInterface
{
    protected $model;

    public function __construct(Material $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua bahan ajar.
     *
     * @return mixed
     */
    public function getAllMaterials()
    {
        return $this->model->with('program')->get();
    }

    /**
     * Mengambil bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMaterialById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->with('program')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Material with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil bahan ajar berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMaterialByName($name)
    {
        return $this->model->where('name', $name)->with('program')->first();
    }

    /**
     * Membuat bahan ajar baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMaterial(array $data)
    {
        try {
            $material = $this->model->create($data);
            $material->load('program');
            return $material;
        } catch (\Exception $e) {
            Log::error("Failed to create material: {$e->getMessage()}");
            return null;
        }
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
        $material = $this->findMaterial($id);

        if ($material) {
            try {
                $material->update($data);
                $material->load('program');
                return $material;
            } catch (\Exception $e) {
                Log::error("Failed to update material with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteMaterial($id)
    {
        $material = $this->findMaterial($id);

        if ($material) {
            try {
                $material->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete material with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findMaterial($id)
    {
        try {
            return $this->model->with('program')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Material with ID {$id} not found.");
            return null;
        }
    }
}
