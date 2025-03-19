<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface
{
    /**
     * Mengambil semua data student beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getAllStudents();

    /**
     * Mengambil semua data student dengan status Aktif beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getActiveStudents();

    /**
     * Mengambil semua data student dengan status Non Aktif beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getInactiveStudents();

    /**
     * Mengambil data student berdasarkan ID beserta relasi program dan user.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentById($id);

    /**
     * Mengambil data student berdasarkan nama user (berdasarkan relasi dengan user).
     *
     * @param string $name
     * @return mixed
     */
    public function getStudentByName($name);

    /**
     * Membuat data student baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudent(array $data);

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudent($id, array $data);

    /**
     * Menghapus data student berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStudent($id);
}
