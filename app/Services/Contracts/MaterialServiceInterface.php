<?php

namespace App\Services\Contracts;

interface MaterialServiceInterface
{
    /**
     * Mengambil semua bahan ajar.
     *
     * @return mixed
     */
    public function getAllMaterials();

    /**
     * Mengambil bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMaterialById($id);

    /**
     * Mengambil bahan ajar berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMaterialByName($name);

    /**
     * Membuat bahan ajar baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMaterial(array $data);

    /**
     * Memperbarui bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateMaterial($id, array $data);

    /**
     * Menghapus bahan ajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteMaterial($id);
}
