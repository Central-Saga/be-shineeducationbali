<?php

namespace App\Repositories\Eloquent;

use App\Models\GradeCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\GradeCategoryRepositoryInterface;

class GradeCategoryRepository implements GradeCategoryRepositoryInterface
{
    protected $model;

    public function __construct(GradeCategory $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua kategori nilai.
     *
     * @return mixed
     */
    public function getAllGradeCategories()
    {
        return $this->model->all();
    }

    /**
     * Mengambil kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeCategoryById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Grade Category with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil kategori nilai berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getGradeCategoryByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Membuat kategori nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGradeCategory(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create grade category: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateGradeCategory($id, array $data)
    {
        try {
            $gradeCategory = $this->getGradeCategoryById($id);
            if ($gradeCategory) {
                $gradeCategory->update($data);
                return $gradeCategory;
            }
            return null;
        } catch (\Exception $e) {
            Log::error("Failed to update grade category with ID {$id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Menghapus kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGradeCategory($id)
    {
        try {
            $gradeCategory = $this->getGradeCategoryById($id);
            if ($gradeCategory) {
                $gradeCategory->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Failed to delete grade category with ID {$id}: {$e->getMessage()}");
            return false;
        }
    }
}
