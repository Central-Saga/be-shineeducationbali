<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ScheduleServiceInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ScheduleService implements ScheduleServiceInterface
{
    protected $repository;

    const SCHEDULES_ALL_CACHE_KEY = 'schedules.all';

    public function __construct(ScheduleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua schedules.
     *
     * @return mixed
     */
    public function getAllSchedules()
    {
        return Cache::remember(self::SCHEDULES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllSchedules();
        });
    }

    /**
     * Mengambil schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getScheduleById($id)
    {
        return $this->repository->getScheduleById($id);
    }

    /**
     * Mengambil schedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getScheduleByName($name)
    {
        return $this->repository->getScheduleByName($name);
    }

    /**
     * Membuat schedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data)
    {
        $result = $this->repository->createSchedule($data);
        $this->clearScheduleCaches();
        return $result;
    }

    /**
     * Memperbarui schedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSchedule($id, array $data)
    {
        $result = $this->repository->updateSchedule($id, $data);
        $this->clearScheduleCaches();
        return $result;
    }

    /**
     * Menghapus schedule berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteSchedule($id)
    {
        $result = $this->repository->deleteSchedule($id);
        $this->clearScheduleCaches();

        return $result;
    }

    /**
     * Menghapus semua cache schedule
     *
     * @return void
     */
    public function clearScheduleCaches()
    {
        Cache::forget(self::SCHEDULES_ALL_CACHE_KEY);
    }
}
