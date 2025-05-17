<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TeacherServiceInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;

class TeacherService implements TeacherServiceInterface
{
    protected $repository;

    const TEACHERS_ALL_CACHE_KEY = 'teachers.all';
    const TEACHERS_ACTIVE_CACHE_KEY = 'teachers.active';
    const TEACHERS_INACTIVE_CACHE_KEY = 'teachers.inactive';

    public function __construct(TeacherRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua permissions.
     *
     * @return mixed
     */
    public function getAllTeachers()
    {
        return Cache::remember(self::TEACHERS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllTeachers();
        });
    }

    /**
     * Mengambil tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTeacherById($id)
    {
        return $this->repository->getTeacherById($id);
    }

    /**
     * Mengambil tipe kelas berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTeacherByName($name)
    {
        return $this->repository->getTeacherByName($name);
    }

    /**
     * Membuat tipe kelas baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTeacher(array $data)
    {
        $result = $this->repository->createTeacher($data);
        $this->clearTeacherCaches();
        return $result;
    }

    /**
     * Memperbarui tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTeacher($id, array $data)
    {
        $result = $this->repository->updateTeacher($id, $data);
        $this->clearTeacherCaches();
        return $result;
    }

    /**
     * Menghapus tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTeacher($id)
    {
        $result = $this->repository->deleteTeacher($id);
        $this->clearTeacherCaches();

        return $result;
    }

    /**
     * Mengambil semua pengajar dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveTeachers()
    {
        return Cache::remember(self::TEACHERS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getAllTeachers()->where('status', 'Aktif');
        });
    }

    /**
     * Mengambil semua pengajar dengan status non-aktif.
     *
     * @return mixed
     */
    public function getInactiveTeachers()
    {
        return Cache::remember(self::TEACHERS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getAllTeachers()->where('status', 'Non Aktif');
        });
    }

    /**
     * Menghapus semua cache tipe kelas
     *
     * @return void
     */
    public function clearTeacherCaches()
    {
        Cache::forget(self::TEACHERS_ALL_CACHE_KEY);
        Cache::forget(self::TEACHERS_ACTIVE_CACHE_KEY);
        Cache::forget(self::TEACHERS_INACTIVE_CACHE_KEY);
    }
}
