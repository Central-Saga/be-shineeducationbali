<?php

namespace App\Services\Contracts;

interface TeacherServiceInterface
{
        /**
     * Mengambil semua pengajar.
     *
     * @return mixed
     */
    public function getAllTeachers();

    /**
     * Mengambil pengajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTeacherById($id);

    /**
     * Mengambil pengajar berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTeacherByName($name);

    /**
     * Membuat pengajar baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTeacher(array $data);

    /**
     * Memperbarui pengajar berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTeacher($id, array $data);

    /**
     * Menghapus pengajar berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTeacher($id);
}
