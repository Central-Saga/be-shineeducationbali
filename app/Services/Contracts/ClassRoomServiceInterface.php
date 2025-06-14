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
     * Mengambil class room yang aktif.
     *
     * @return mixed
     */
    public function getActiveClassRooms();

    /**
     * Mengambil class room yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveClassRooms();

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
    
    /**
     * Menghapus semua cache class room
     *
     * @return void
     */
    public function clearClassRoomCaches();
    
    /**
     * Menambahkan siswa ke dalam classroom
     *
     * @param int $classRoomId
     * @param int $studentId
     * @return mixed
     */
    public function attachStudentToClassRoom($classRoomId, $studentId);
    
    /**
     * Menghapus siswa dari classroom
     *
     * @param int $classRoomId
     * @param int $studentId
     * @return mixed
     */
    public function detachStudentFromClassRoom($classRoomId, $studentId);
    
    /**
     * Mengambil daftar siswa dalam classroom
     *
     * @param int $classRoomId
     * @return mixed
     */
    public function getStudentsInClassRoom($classRoomId);
}
