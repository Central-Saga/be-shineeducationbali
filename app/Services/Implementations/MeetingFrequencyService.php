<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\MeetingFrequencyServiceInterface;
use App\Repositories\Contracts\MeetingFrequencyRepositoryInterface;

class MeetingFrequencyService implements MeetingFrequencyServiceInterface
{
    protected $repository;

    const MEETING_FREQUENCIES_ALL_CACHE_KEY = 'meeting_frequencies.all';

    public function __construct(MeetingFrequencyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua frekuensi pertemuan.
     *
     * @return mixed
     */
    public function getAllMeetingFrequencies()
    {
        return Cache::remember(self::MEETING_FREQUENCIES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllMeetingFrequencies();
        });
    }

    /**
     * Mengambil frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMeetingFrequencyById($id)
    {
        return $this->repository->getMeetingFrequencyById($id);
    }

    /**
     * Mengambil frekuensi pertemuan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMeetingFrequencyByName($name)
    {
        return $this->repository->getMeetingFrequencyByName($name);
    }

    /**
     * Membuat frekuensi pertemuan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMeetingFrequency(array $data)
    {
        $result = $this->repository->createMeetingFrequency($data);
        $this->clearMeetingFrequencyCaches();
        return $result;
    }

    /**
     * Memperbarui tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateMeetingFrequency($id, array $data)
    {
        $result = $this->repository->updateMeetingFrequency($id, $data);
        $this->clearMeetingFrequencyCaches();
        return $result;
    }

    /**
     * Menghapus frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteMeetingFrequency($id)
    {
        $result = $this->repository->deleteMeetingFrequency($id);
        $this->clearMeetingFrequencyCaches();

        return $result;
    }

    /**
     * Menghapus semua cache frekuensi pertemuan
     *
     * @return void
     */
    public function clearMeetingFrequencyCaches()
    {
        Cache::forget(self::MEETING_FREQUENCIES_ALL_CACHE_KEY);
    }
}
