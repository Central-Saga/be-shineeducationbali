<?php

namespace App\Repositories\Contracts;

interface ProgramRepositoryInterface
{
    /**
     * Mengambil semua program.
     *
     * @return mixed
     */
    public function getAllPrograms();

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
     * Mengambil program berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getProgramsByStatus($status);

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
