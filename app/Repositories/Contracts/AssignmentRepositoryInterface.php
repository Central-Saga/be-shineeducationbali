<?php

namespace App\Repositories\Contracts;

interface AssignmentRepositoryInterface
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
     * Mengambil assignment berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAssignmentByStatus($status);

    /**
     * Mengambil assignment berdasarkan status Belum Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByNotCompleted();

    /**
     * Mengambil assignment berdasarkan status Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByCompleted();

    /**
     * Mengambil assignment berdasarkan status Ditolak.
     *
     * @return mixed
     */
    public function getAssignmentByRejected();

    /**
     * Mengambil assignment berdasarkan status Dalam Pengajuan.
     *
     * @return mixed
     */
    public function getAssignmentByPending();

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
