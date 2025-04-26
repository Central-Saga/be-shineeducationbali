<?php

namespace App\Repositories\Contracts;

interface GradeCategoryRepositoryInterface
{
    /**
     * Mengambil semua kategori nilai.
     *
     * @return mixed
     */
    public function getAllGradeCategories();

    /**
     * Mengambil kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getGradeCategoryById($id);

    /**
     * Mengambil kategori nilai berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getGradeCategoryByName($name);

    /**
     * Membuat kategori nilai baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createGradeCategory(array $data);

    /**
     * Memperbarui kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateGradeCategory($id, array $data);

    /**
     * Menghapus kategori nilai berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGradeCategory($id);
}
