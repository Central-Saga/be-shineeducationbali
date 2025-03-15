<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\SubjectRepositoryInterface;

class SubjectRepository implements SubjectRepositoryInterface
{
    protected $model;

    public function __construct(Subject $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua mata pelajaran.
     *
     * @return mixed
     */
    public function getAllSubjects()
    {
        return $this->model->all();
    }

    /**
     * Mengambil mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSubjectById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Subject with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil mata pelajaran berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSubjectByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Membuat mata pelajaran baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSubject(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create subject: {$e->getMessage()}");
            return null;
        }
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
        $subject = $this->findSubject($id);

        if ($subject) {
            try {
                $subject->update($data);
                return $subject;
            } catch (\Exception $e) {
                Log::error("Failed to update subject with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSubject($id)
    {
        $subject = $this->findSubject($id);

        if ($subject) {
            try {
                $subject->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete subject with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan mata pelajaran berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findSubject($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Subject with ID {$id} not found.");
            return null;
        }
    }
}
