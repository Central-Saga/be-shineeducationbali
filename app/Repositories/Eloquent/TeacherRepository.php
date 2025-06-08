<?php

namespace App\Repositories\Eloquent;

use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TeacherRepository implements TeacherRepositoryInterface
{
    protected $model;

    public function __construct(Teacher $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua data teacher beserta relasi subject dan user.
     *
     * @return mixed
     */
    public function getAllTeachers()
    {
        return $this->model->with(['subject', 'user'])->get();
    }

    /**
     * Mengambil data teacher berdasarkan ID beserta relasi subject dan user.
     *
     * @param int $id
     * @return mixed
     */
    public function getTeacherById($id)
    {
        try {
            return $this->model->with(['subject', 'user'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Teacher with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil data teacher berdasarkan nama user (berdasarkan relasi dengan user).
     *
     * @param string $name
     * @return mixed
     */
    public function getTeacherByName($name)
    {
        return $this->model->with(['subject', 'user'])
                          ->whereHas('user', function ($query) use ($name) {
                              $query->where('name', 'like', "%{$name}%");
                          })
                          ->first();
    }

    /**
     * Membuat data teacher baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTeacher(array $data)
    {
        try {
            $teacher = $this->model->create($data);
            return $this->model->with(['subject', 'user'])->find($teacher->id);
        } catch (\Exception $e) {
            Log::error("Failed to create teacher: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui data teacher berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTeacher($id, array $data)
    {
        $teacher = $this->findTeacher($id);

        if ($teacher) {
            try {
                $teacher->update($data);
                return $this->model->with(['subject', 'user'])->find($teacher->id);
            } catch (\Exception $e) {
                Log::error("Failed to update teacher with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus data teacher berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTeacher($id)
    {
        $teacher = $this->findTeacher($id);

        if ($teacher) {
            try {
                $teacher->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete teacher with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan data teacher berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findTeacher($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Teacher with ID {$id} not found.");
            return null;
        }
    }
}