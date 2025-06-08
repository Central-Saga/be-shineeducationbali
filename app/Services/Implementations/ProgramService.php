<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\ProgramServiceInterface;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class ProgramService implements ProgramServiceInterface
{
    protected $repository;

    const PROGRAMS_ALL_CACHE_KEY = 'programs_all';
    const PROGRAMS_ACTIVE_CACHE_KEY = 'programs_active';
    const PROGRAMS_INACTIVE_CACHE_KEY = 'programs_inactive';

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
     * Mengambil program yang aktif.
     *
     * @return mixed
     */
    public function getActivePrograms()
    {
        return Cache::remember(self::PROGRAMS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getProgramsByStatus('Aktif');
        });
    }

    /**
     * Mengambil program yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactivePrograms()
    {
        return Cache::remember(self::PROGRAMS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getProgramsByStatus('Non Aktif');
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
        $program = $this->repository->createProgram($data);
        if ($program) {
            $this->clearCache();
        }
        return $program;
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
        $program = $this->repository->updateProgram($id, $data);
        if ($program) {
            $this->clearCache();
        }
        return $program;
    }

    /**
     * Menghapus program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteProgram($id)
    {
        $result = $this->repository->deleteProgram($id);
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    /**
     * Memperbarui status program berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus($id, array $data)
    {
        $program = $this->repository->updateProgram($id, $data);
        if ($program) {
            $this->clearCache();
        }
        return $program;
    }

    /**
     * Membersihkan cache.
     *
     * @return void
     */
    protected function clearCache()
    {
        Cache::forget(self::PROGRAMS_ALL_CACHE_KEY);
        Cache::forget(self::PROGRAMS_ACTIVE_CACHE_KEY);
        Cache::forget(self::PROGRAMS_INACTIVE_CACHE_KEY);
    }
}
