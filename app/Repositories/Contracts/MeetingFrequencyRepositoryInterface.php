<?php

namespace App\Repositories\Contracts;

interface MeetingFrequencyRepositoryInterface
{
    /**
     * Mengambil semua frekuensi pertemuan.
     *
     * @return mixed
     */
    public function getAllMeetingFrequencies();

    /**
     * Mengambil frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMeetingFrequencyById($id);

    /**
     * Mengambil frekuensi pertemuan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMeetingFrequencyByName($name);

    /**
     * Membuat frekuensi pertemuan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMeetingFrequency(array $data);

    /**
     * Memperbarui frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateMeetingFrequency($id, array $data);

    /**
     * Menghapus frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteMeetingFrequency($id);
}
