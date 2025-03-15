<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EducationLevelRepositoryInterface;

class EducationLevelRepository implements EducationLevelRepositoryInterface
{
    protected $model;

    public function __construct(EducationLevel $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua level pendidikan.
     *
     * @return mixed
     */
    public function getAllEducationLevels()
    {
        return $this->model->all();
    }

    /**
     * Mengambil level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEducationLevelById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Education level with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil level pendidikan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEducationLevelByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Membuat level pendidikan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEducationLevel(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create education level: {$e->getMessage()}");
            return null;
        }
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
        $educationLevel = $this->findEducationLevel($id);

        if ($educationLevel) {
            try {
                $educationLevel->update($data);
                return $educationLevel;
            } catch (\Exception $e) {
                Log::error("Failed to update education level with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteEducationLevel($id)
    {
        $educationLevel = $this->findEducationLevel($id);

        if ($educationLevel) {
            try {
                $educationLevel->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete education level with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan level pendidikan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findEducationLevel($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Education level with ID {$id} not found.");
            return null;
        }
    }
}
