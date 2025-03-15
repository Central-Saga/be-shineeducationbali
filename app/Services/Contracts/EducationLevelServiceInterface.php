<?php

namespace App\Services\Contracts;

interface EducationLevelServiceInterface
{
    /**
     * Mengambil semua level pendidikan.
     *
     * @return mixed
     */
    public function getAllEducationLevels();

    /**
     * Mengambil level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEducationLevelById($id);

    /**
     * Mengambil level pendidikan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEducationLevelByName($name);

    /**
     * Membuat level pendidikan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEducationLevel(array $data);

    /**
     * Memperbarui level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEducationLevel($id, array $data);

    /**
     * Menghapus level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteEducationLevel($id);
}
