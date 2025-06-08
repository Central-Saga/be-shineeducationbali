<?php

namespace App\Services\Contracts;

interface StudentServiceInterface
{
    /**
     * Mengambil semua data student.
     *
     * @return mixed
     */
    public function getAllStudents();

    /**
     * Mengambil data student berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentById($id);

    /**
     * Mengambil data student berdasarkan nama user.
     *
     * @param string $name
     * @return mixed
     */
    public function getStudentByName($name);

    /**
     * Mengambil semua data student dengan status Aktif.
     *
     * @return mixed
     */
    public function getActiveStudents();

    /**
     * Mengambil semua data student dengan status Non Aktif.
     *
     * @return mixed
     */
    public function getInactiveStudents();

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
