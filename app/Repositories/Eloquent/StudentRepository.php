<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class StudentRepository implements StudentRepositoryInterface
{
    protected $model;

    /**
     * Konstruktor untuk menginisialisasi model Student.
     *
     * @param Student $model
     */
    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua data student beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getAllStudents()
    {
        return $this->model->with(['program', 'user'])->get();
    }

    /**
     * Mengambil semua data student dengan status Aktif beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getActiveStudents()
    {
        return $this->model->with(['program', 'user'])->where('status', 'Aktif')->get();
    }

    /**
     * Mengambil semua data student dengan status Non Aktif beserta relasi program dan user.
     *
     * @return mixed
     */
    public function getInactiveStudents()
    {
        return $this->model->with(['program', 'user'])->where('status', 'Non Aktif')->get();
    }

    /**
     * Mengambil data student berdasarkan ID beserta relasi program dan user.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentById($id)
    {
        try {
            return $this->model->with(['program', 'user'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Student with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil data student berdasarkan nama user (berdasarkan relasi dengan user).
     *
     * @param string $name
     * @return mixed
     */
    public function getStudentByName($name)
    {
        return $this->model->with(['program', 'user'])
                          ->whereHas('user', function ($query) use ($name) {
                              $query->where('name', 'like', "%{$name}%");
                          })
                          ->first();
    }

    /**
     * Membuat data student baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudent(array $data)
    {
        try {
            $student = $this->model->create($data);
            return $this->model->with(['program', 'user'])->find($student->id);
        } catch (\Exception $e) {
            Log::error("Failed to create student: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudent($id, array $data)
    {
        $student = $this->findStudent($id);

        if ($student) {
            try {
                $student->update($data);
                return $this->model->with(['program', 'user'])->find($student->id);
            } catch (\Exception $e) {
                Log::error("Failed to update student with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus data student berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStudent($id)
    {
        $student = $this->findStudent($id);

        if ($student) {
            try {
                $student->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete student with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan data student berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findStudent($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Student with ID {$id} not found.");
            return null;
        }
    }
}
