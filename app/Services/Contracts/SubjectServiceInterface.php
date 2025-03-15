<?php

namespace App\Services\Contracts;

interface SubjectServiceInterface
{
    /**
     * Mengambil semua mata pelajaran.
     *
     * @return mixed
     */
    public function getAllSubjects();

    /**
     * Mengambil mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSubjectById($id);

    /**
     * Mengambil mata pelajaran berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSubjectByName($name);

    /**
     * Membuat mata pelajaran baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSubject(array $data);

    /**
     * Memperbarui mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSubject($id, array $data);

    /**
     * Menghapus mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSubject($id);
}
