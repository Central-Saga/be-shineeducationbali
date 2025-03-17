<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ProgramServiceInterface;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class ProgramService implements ProgramServiceInterface
{
    protected $repository;

    const PROGRAMS_ALL_CACHE_KEY = 'programs_all';

    public function __construct(ProgramRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua program.
     *
     * @return mixed
     */
    public function getAllPrograms()
    {
        return Cache::remember(self::PROGRAMS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllPrograms();
        });
    }

    /**
     * Mengambil program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getProgramById($id)
    {
        return $this->repository->getProgramById($id);
    }

    /**
     * Mengambil program berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getProgramByName($name)
    {
        return $this->repository->getProgramByName($name);
    }

    /**
     * Membuat program baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createProgram(array $data)
    {
        $result = $this->repository->createProgram($data);
        $this->clearProgramCaches();
        return $result;
    }

    /**
     * Memperbarui program berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateProgram($id, array $data)
    {
        $result = $this->repository->updateProgram($id, $data);
        $this->clearProgramCaches();
        return $result;
    }

    /**
     * Menghapus program berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProgram($id)
    {
        $result = $this->repository->deleteProgram($id);
        $this->clearProgramCaches();

        return $result;
    }

    /**
     * Menghapus semua cache program
     *
     * @return void
     */
    public function clearProgramCaches()
    {
        Cache::forget(self::PROGRAMS_ALL_CACHE_KEY);
    }
}
