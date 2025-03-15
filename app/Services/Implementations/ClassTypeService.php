<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ClassTypeServiceInterface;
use App\Repositories\Contracts\ClassTypeRepositoryInterface;

class ClassTypeService implements ClassTypeServiceInterface
{
    protected $repository;

    const CLASS_TYPES_ALL_CACHE_KEY = 'class_types.all';

    public function __construct(ClassTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua permissions.
     *
     * @return mixed
     */
    public function getAllClassTypes()
    {
        return Cache::remember(self::CLASS_TYPES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllClassTypes();
        });
    }

    /**
     * Mengambil tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassTypeById($id)
    {
        return $this->repository->getClassTypeById($id);
    }

    /**
     * Mengambil tipe kelas berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassTypeByName($name)
    {
        return $this->repository->getClassTypeName($name);
    }

    /**
     * Membuat tipe kelas baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassType(array $data)
    {
        $result = $this->repository->createClassType($data);
        $this->clearClassTypeCaches();
        return $result;
    }

    /**
     * Memperbarui tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassType($id, array $data)
    {
        $result = $this->repository->updateClassType($id, $data);
        $this->clearClassTypeCaches();
        return $result;
    }

    /**
     * Menghapus tipe kelas berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteClassType($id)
    {
        $result = $this->repository->deleteClassType($id);
        $this->clearClassTypeCaches();

        return $result;
    }

    /**
     * Menghapus semua cache tipe kelas
     *
     * @return void
     */
    public function clearClassTypeCaches()
    {
        Cache::forget(self::CLASS_TYPES_ALL_CACHE_KEY);
    }
}
