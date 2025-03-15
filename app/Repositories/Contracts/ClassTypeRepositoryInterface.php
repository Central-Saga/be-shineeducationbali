<?php

namespace App\Repositories\Contracts;

interface ClassTypeRepositoryInterface
{
    /**
     * Mengambil semua class types.
     *
     * @return mixed
     */
    public function getAllClassTypes();

    /**
     * Mengambil class type berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassTypeById($id);

    /**
     * Mengambil class type berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassTypeByName($name);

    /**
     * Membuat class type baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassType(array $data);

    /**
     * Memperbarui class type berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassType($id, array $data);

    /**
     * Menghapus class type berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteClassType($id);
}
