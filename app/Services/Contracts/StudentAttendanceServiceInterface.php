<?php

namespace App\Services\Contracts;

interface StudentAttendanceServiceInterface
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
     * Mengambil data absensi berdasarkan tanggal tertentu.
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
     * Mengambil data absensi berdasarkan status (hadir/tidak hadir).
     *
     * @param string $status
     * @return mixed
     */
    public function getAttendancesByStatus($status);

    /**
     * Mendapatkan ringkasan kehadiran siswa (total hadir dan tidak hadir).
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentAttendanceSummary($studentId);

    /**
     * Membuat data absensi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudentAttendance(array $data);

    /**
     * Memperbarui data absensi berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudentAttendance($id, array $data);

    /**
     * Menghapus data absensi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteStudentAttendance($id);
}
