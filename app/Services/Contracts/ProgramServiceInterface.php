<?php

namespace App\Services\Contracts;

interface ProgramServiceInterface
{
    /**
     * Mengambil semua program.
     *
     * @return mixed
     */
    public function getAllPrograms();

    /**
     * Mengambil program yang aktif.
     *
     * @return mixed
     */
    public function getActivePrograms();

    /**
     * Mengambil program yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactivePrograms();

    /**
     * Mengambil program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getProgramById($id);

    /**
     * Mengambil program berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getProgramByName($name);

    /**
     * Membuat program baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createProgram(array $data);

    /**
     * Memperbarui program berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateProgram($id, array $data);

    /**
     * Menghapus program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteProgram($id);
}
