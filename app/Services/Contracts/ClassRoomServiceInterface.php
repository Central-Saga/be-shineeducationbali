<?php

namespace App\Services\Contracts;

interface ClassRoomServiceInterface
{
    /**
     * Mengambil semua class rooms.
     *
     * @return mixed
     */
    public function getAllClassRooms();

    /**
     * Mengambil class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassRoomById($id);

    /**
     * Mengambil class room berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassRoomByName($name);

    /**
     * Mengambil class room berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getClassRoomByStatus($status);

    /**
     * Mengambil class room berdasarkan status Aktif.
     *
     * @return mixed
     */
    public function getClassRoomByActive();

    /**
     * Mengambil class room berdasarkan status Tidak Aktif.
     *
     * @return mixed
     */
    public function getClassRoomByInactive();


    /**
     * Membuat class room baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassRoom(array $data);

    /**
     * Memperbarui class room berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassRoom($id, array $data);

    /**
     * Menghapus class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteClassRoom($id);
}
