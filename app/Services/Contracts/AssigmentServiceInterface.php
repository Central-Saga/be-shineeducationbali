<?php

namespace App\Services\Contracts;

interface AssigmentServiceInterface
{
    /**
     * Mengambil semua assignments.
     *
     * @return mixed
     */
    public function getAllAssignments();

    /**
     * Mengambil assignment berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getAssignmentById($id);

    /**
     * Mengambil assignment berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getAssignmentByName($name);

    /**
     * Membuat assignment baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createAssignment(array $data);

    /**
     * Memperbarui assignment berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAssignment($id, array $data);

    /**
     * Menghapus assignment berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteAssignment($id);
}
