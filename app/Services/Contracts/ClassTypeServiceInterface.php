<?php

namespace App\Services\Contracts;

interface ClassTypeServiceInterface
{
    /**
     * Mengambil semua tipe kelas.
     *
     * @return mixed
     */
    public function getAllClassTypes();

    /**
     * Mengambil tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassTypeById($id);

    /**
     * Mengambil tipe kelas berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassTypeByName($name);

    /**
     * Membuat tipe kelas baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassType(array $data);

    /**
     * Memperbarui tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassType($id, array $data);

    /**
     * Menghapus tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteClassType($id);
}
