<?php

namespace App\Services\Contracts;

interface ScheduleServiceInterface
{
    /**
     * Mengambil semua schedules.
     *
     * @return mixed
     */
    public function getAllSchedules();

    /**
     * Mengambil schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getScheduleById($id);

    /**
     * Mengambil schedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getScheduleByName($name);

    /**
     * Membuat schedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data);

    /**
     * Memperbarui schedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSchedule($id, array $data);

    /**
     * Menghapus schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSchedule($id);
}
