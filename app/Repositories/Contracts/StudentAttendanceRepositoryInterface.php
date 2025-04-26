<?php

namespace App\Repositories\Contracts;

interface StudentAttendanceRepositoryInterface
{
    /**
     * Mengambil semua data absensi siswa.
     *
     * @return mixed
     */
    public function getAllStudentAttendances();

    /**
     * Mengambil data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentAttendanceById($id);

    /**
     * Mengambil data absensi berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getAttendancesByStudentId($studentId);

    /**
     * Mengambil data absensi berdasarkan ID kelas.
     *
     * @param int $classRoomId
     * @return mixed
     */
    public function getAttendancesByClassRoomId($classRoomId);

    /**
     * Mengambil data absensi berdasarkan ID guru.
     *
     * @param int $teacherId
     * @return mixed
     */
    public function getAttendancesByTeacherId($teacherId);

    /**
     * Mengambil data absensi berdasarkan tanggal.
     *
     * @param string $date
     * @return mixed
     */
    public function getAttendancesByDate($date);

    /**
     * Mengambil data absensi berdasarkan rentang tanggal.
     *
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getAttendancesByDateRange($startDate, $endDate);

    /**
     * Mengambil data absensi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAttendancesByStatus($status);

    /**
     * Membuat data absensi siswa baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudentAttendance(array $data);

    /**
     * Memperbarui data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudentAttendance($id, array $data);

    /**
     * Menghapus data absensi siswa berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteStudentAttendance($id);
}
