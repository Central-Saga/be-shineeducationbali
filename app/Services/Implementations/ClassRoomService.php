<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ClassRoomServiceInterface;
use App\Repositories\Contracts\ClassRoomRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ClassRoomService implements ClassRoomServiceInterface
{
    protected $repository;

    const CLASS_ROOMS_ALL_CACHE_KEY = 'class_rooms.all';

    public function __construct(ClassRoomRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Mengambil semua class rooms.
     *
     * @return mixed
     */
    public function getAllClassRooms()
    {
        return Cache::remember(self::CLASS_ROOMS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllClassRooms();
        });
    }

    /**
     * Mengambil class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassRoomById($id)
    {
        return $this->repository->getClassRoomById($id);
    }

    /**
     * Mengambil class room berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassRoomByName($name)
    {
        return $this->repository->getClassRoomByName($name);
    }

    /**
     * Membuat class room baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassRoom(array $data)
    {
        $result = $this->repository->createClassRoom($data);
        $this->clearClassRoomCaches();
        return $result;
    }

    /**
     * Memperbarui class room berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassRoom($id, array $data)
    {
        $result = $this->repository->updateClassRoom($id, $data);
        $this->clearClassRoomCaches();
        return $result;
    }

    /**
     * Menghapus class room berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteClassRoom($id)
    {
        $result = $this->repository->deleteClassRoom($id);
        $this->clearClassRoomCaches();

        return $result;
    }

    /**
     * Menghapus semua cache class room
     *
     * @return void
     */
    public function clearClassRoomCaches()
    {
        Cache::forget(self::CLASS_ROOMS_ALL_CACHE_KEY);
    }
}
