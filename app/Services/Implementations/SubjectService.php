<?php

namespace App\Services\Implementations;

use App\Services\Contracts\SubjectServiceInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class SubjectService implements SubjectServiceInterface
{
    protected $repository;

    const SUBJECTS_ALL_CACHE_KEY = 'subjects.all';

    public function __construct(SubjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua mata pelajaran.
     *
     * @return mixed
     */
    public function getAllSubjects()
    {
        return Cache::remember(self::SUBJECTS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllSubjects();
        });
    }

    /**
     * Mengambil mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSubjectById($id)
    {
        return $this->repository->getSubjectById($id);
    }

    /**
     * Mengambil mata pelajaran berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSubjectByName($name)
    {
        return $this->repository->getSubjectByName($name);
    }

    /**
     * Membuat mata pelajaran baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSubject(array $data)
    {
        $result = $this->repository->createSubject($data);
        $this->clearSubjectCaches();
        return $result;
    }

    /**
     * Memperbarui mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSubject($id, array $data)
    {
        $result = $this->repository->updateSubject($id, $data);
        $this->clearSubjectCaches();
        return $result;
    }

    /**
     * Menghapus mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteSubject($id)
    {
        $result = $this->repository->deleteSubject($id);
        $this->clearSubjectCaches();

        return $result;
    }

    /**
     * Menghapus semua cache mata pelajaran
     *
     * @return void
     */
    public function clearMeetingFrequencyCaches()
    {
        Cache::forget(self::SUBJECTS_ALL_CACHE_KEY);
    }
}
