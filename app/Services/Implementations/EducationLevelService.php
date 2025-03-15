<?php

namespace App\Services\Implementations;

use App\Services\Contracts\EducationLevelServiceInterface;
use App\Repositories\Contracts\EducationLevelRepositoryInterface;

class EducationLevelService implements EducationLevelServiceInterface
{
    protected $repository;

    const EDUCATION_LEVELS_ALL_CACHE_KEY = 'education_levels.all';

    public function __construct(EducationLevelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua level pendidikan.
     *
     * @return mixed
     */
    public function getAllEducationLevels()
    {
        return Cache::remember(self::EDUCATION_LEVELS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllEducationLevels();
        });
    }

    /**
     * Mengambil level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEducationLevelById($id)
    {
        return $this->repository->getEducationLevelById($id);
    }

    /**
     * Mengambil level pendidikan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEducationLevelByName($name)
    {
        return $this->repository->getEducationLevelByName($name);
    }

    /**
     * Membuat level pendidikan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEducationLevel(array $data)
    {
        $result = $this->repository->createEducationLevel($data);
        $this->clearEducationLevelCaches();
        return $result;
    }

    /**
     * Memperbarui level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEducationLevel($id, array $data)
    {
        $result = $this->repository->updateEducationLevel($id, $data);
        $this->clearEducationLevelCaches();
        return $result;
    }

    /**
     * Menghapus level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEducationLevel($id)
    {
        $result = $this->repository->deleteEducationLevel($id);
        $this->clearEducationLevelCaches();

        return $result;
    }

    /**
     * Menghapus semua cache level pendidikan
     *
     * @return void
     */
    public function clearEducationLevelCaches()
    {
        Cache::forget(self::EDUCATION_LEVELS_ALL_CACHE_KEY);
    }
}
