<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryInterface
{
        /**
     * Mengambil semua Teachers.
     *
     * @return mixed
     */
    public function getAllTeachers();

    /**
     * Mengambil Teacher berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTeacherById($id);

    /**
     * Mengambil Teacher berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTeacherByName($name);

    /**
     * Membuat Teacher baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTeacher(array $data);

    /**
     * Memperbarui Teacher berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTeacher($id, array $data);

    /**
     * Menghapus Teacher berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTeacher($id);
}
