<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\GradeCategoryRepositoryInterface;
use App\Services\Contracts\GradeCategoryServiceInterface;
use Illuminate\Support\Facades\Cache;

class GradeCategoryService implements GradeCategoryServiceInterface
{
    protected $repository;

    // Cache keys
    const ALL_GRADE_CATEGORIES_CACHE_KEY = 'all_grade_categories';
    const GRADE_CATEGORY_BY_ID_CACHE_KEY = 'grade_category_by_id_';
    const GRADE_CATEGORY_BY_NAME_CACHE_KEY = 'grade_category_by_name_';

    public function __construct(GradeCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua kategori nilai.
     *
     * @return mixed
     */
    public function getAllGradeCategories()
    {
        return Cache::remember(self::ALL_GRADE_CATEGORIES_CACHE_KEY, 3600, function () {
            return $this->repository->getAllGradeCategories();
        });
    }

    /**
     * Mengambil kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeCategoryById($id)
    {
        return Cache::remember(self::GRADE_CATEGORY_BY_ID_CACHE_KEY . $id, 3600, function () use ($id) {
            return $this->repository->getGradeCategoryById($id);
        });
    }

    /**
     * Mengambil kategori nilai berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getGradeCategoryByName($name)
    {
        return Cache::remember(self::GRADE_CATEGORY_BY_NAME_CACHE_KEY . $name, 3600, function () use ($name) {
            return $this->repository->getGradeCategoryByName($name);
        });
    }

    /**
     * Membuat kategori nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGradeCategory(array $data)
    {
        $result = $this->repository->createGradeCategory($data);
        $this->clearCache();
        return $result;
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
        $result = $this->repository->updateGradeCategory($id, $data);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGradeCategory($id)
    {
        $result = $this->repository->deleteGradeCategory($id);
        $this->clearCache();
        return $result;
    }

    /**
     * Membersihkan cache terkait kategori nilai.
     */
    private function clearCache()
    {
        Cache::forget(self::ALL_GRADE_CATEGORIES_CACHE_KEY);
        // Catatan: cache berdasarkan ID dan nama masih ada, tapi akan diperbarui saat diminta lagi
    }
}
