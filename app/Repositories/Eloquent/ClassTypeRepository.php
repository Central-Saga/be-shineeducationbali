<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassType;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ClassTypeRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassTypeRepository implements ClassTypeRepositoryInterface
{
    protected $model;

    public function __construct(ClassType $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua class types.
     *
     * @return mixed
     */
    public function getAllClassTypes()
    {
        return $this->model->all();
    }

    /**
     * Mengambil class type berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassTypeById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Class type with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil class type berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassTypeByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Membuat class type baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassType(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create class type: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui class type berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassType($id, array $data)
    {
        $classType = $this->findClassType($id);

        if ($classType) {
            try {
                $classType->update($data);
                return $classType;
            } catch (\Exception $e) {
                Log::error("Failed to update class type with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus class type berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteClassType($id)
    {
        $classType = $this->findClassType($id);

        if ($classType) {
            try {
                $classType->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete class type with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan class type berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findClassType($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Class type with ID {$id} not found.");
            return null;
        }
    }
}
