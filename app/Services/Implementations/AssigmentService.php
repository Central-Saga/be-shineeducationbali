<?php

namespace App\Services\Implementations;

use App\Services\Contracts\AssigmentServiceInterface;
use App\Repositories\Contracts\AssigmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AssigmentService implements AssigmentServiceInterface
{
    protected $repository;

    const ASSIGNMENTS_ALL_CACHE_KEY = 'assignments_all';
    const ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY = 'assignments_not_completed';
    const ASSIGNMENTS_COMPLETED_CACHE_KEY = 'assignments_completed';
    const ASSIGNMENTS_REJECTED_CACHE_KEY = 'assignments_rejected';
    const ASSIGNMENTS_PENDING_CACHE_KEY = 'assignments_pending';

    public function __construct(AssigmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua assignments.
     *
     * @return mixed
     */
    public function getAllAssignments()
    {
        return Cache::remember(self::ASSIGNMENTS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllAssignments();
        });
    }

    /**
     * Mengambil assignment berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getAssignmentById($id)
    {
        return $this->repository->getAssignmentById($id);
    }

    /**
     * Mengambil assignment berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getAssignmentByName($name)
    {
        return $this->repository->getAssignmentByName($name);
    }

    /**
     * Mengambil assignment berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAssignmentByStatus($status)
    {
        return $this->repository->getAssignmentByStatus($status);
    }

    /**
     * Mengambil assignment berdasarkan status Belum Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByNotCompleted()
    {
        return Cache::remember(self::ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY, 3600, function () {
            return $this->repository->getAssignmentByNotCompleted();
        });
    }

    /**
     * Mengambil assignment berdasarkan status Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByCompleted()
    {
        return Cache::remember(self::ASSIGNMENTS_COMPLETED_CACHE_KEY, 3600, function () {
            return $this->repository->getAssignmentByCompleted();
        });
    }

    /**
     * Mengambil assignment berdasarkan status Ditolak.
     *
     * @return mixed
     */
    public function getAssignmentByRejected()
    {
        return Cache::remember(self::ASSIGNMENTS_REJECTED_CACHE_KEY, 3600, function () {
            return $this->repository->getAssignmentByRejected();
        });
    }

    /**
     * Mengambil assignment berdasarkan status Dalam Pengajuan.
     *
     * @return mixed
     */
    public function getAssignmentByPending()
    {
        return Cache::remember(self::ASSIGNMENTS_PENDING_CACHE_KEY, 3600, function () {
            return $this->repository->getAssignmentByPending();
        });
    }

    /**
     * Membuat assignment baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createAssignment(array $data)
    {
        $result = $this->repository->createAssignment($data);
        $this->clearAssignmentCaches();
        return $result;
    }

    /**
     * Memperbarui assignment berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAssignment($id, array $data)
    {
        $result = $this->repository->updateAssignment($id, $data);
        $this->clearAssignmentCaches();
        return $result;
    }

    /**
     * Menghapus assignment berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAssignment($id)
    {
        $result = $this->repository->deleteAssignment($id);
        $this->clearAssignmentCaches();

        return $result;
    }

    /**
     * Menghapus semua cache assignment
     *
     * @return void
     */
    public function clearAssignmentCaches()
    {
        Cache::forget(self::ASSIGNMENTS_ALL_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_COMPLETED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_REJECTED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_PENDING_CACHE_KEY);
    }
}
