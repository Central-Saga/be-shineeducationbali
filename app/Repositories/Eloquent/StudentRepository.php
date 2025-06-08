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
        Log::info("Attempting to delete student with ID: {$id}");
        
        $student = $this->findStudent($id);
        if (!$student) {
            Log::error("Cannot delete student: Student with ID {$id} not found");
            return false;
        }

        $studentName = $student->user ? $student->user->name : 'N/A';
        Log::info("Found student to delete:", [
            'student_id' => $student->id,
            'name' => $studentName,
            'related_data' => [
                'quotas' => $student->studentQuotas()->count(),
                'attendances' => $student->studentAttendances()->count(),
                'assignments' => $student->assignments()->count(),
                'grades' => $student->grades()->count(),
                'classrooms' => $student->classRooms()->count(),
                'assets' => $student->assets()->count()
            ]
        ]);

        \DB::beginTransaction();
        try {
            // Delete related data in a specific order to avoid foreign key constraints
            
            // 1. First delete all assets (morphMany relationship)
            try {
                $student->assets()->delete();
                Log::info("Deleted assets for student {$studentName}");
            } catch (\Exception $e) {
                Log::warning("Error deleting student assets: " . $e->getMessage());
            }

            // 2. Delete all student quotas
            try {
                $student->studentQuotas()->delete();
                Log::info("Deleted quotas for student {$studentName}");
            } catch (\Exception $e) {
                Log::warning("Error deleting student quotas: " . $e->getMessage());
            }

            // 3. Delete student attendances
            try {
                $student->studentAttendances()->delete();
                Log::info("Deleted attendances for student {$studentName}");
            } catch (\Exception $e) {
                Log::warning("Error deleting student attendances: " . $e->getMessage());
            }

            // 4. Delete assignments and related data
            try {
                // Delete grades first
                $student->grades()->delete();
                Log::info("Deleted grades for student {$studentName}");
                
                // Then delete assignments
                $student->assignments()->delete();
                Log::info("Deleted assignments for student {$studentName}");
            } catch (\Exception $e) {
                Log::warning("Error deleting student assignments/grades: " . $e->getMessage());
                // Continue with deletion process even if this fails
            }

            // 5. Detach from classrooms (handles pivot table)
            try {
                $student->classRooms()->detach();
                Log::info("Detached student {$studentName} from all classrooms");
            } catch (\Exception $e) {
                Log::warning("Error detaching student from classrooms: " . $e->getMessage());
            }
            
            // 6. Finally, delete the student record
            if ($student->delete()) {
                // 7. If deletion is successful, commit transaction
                \DB::commit();
                Log::info("Successfully deleted student {$studentName} with ID: {$id}");
                return true;
            } 

            // If we get here, deletion failed
            \DB::rollBack();
            Log::error("Failed to delete student {$studentName} with ID {$id}: Delete operation returned false");
            return false;

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error("Failed to delete student {$studentName} with ID {$id}. Error: " . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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

    /**
     * Mengambil data student berdasarkan email user.
     *
     * @param string $email
     * @return mixed
     */
    public function getStudentByEmail($email)
    {
        return $this->model->with(['program', 'user'])
                          ->whereHas('user', function ($query) use ($email) {
                              $query->where('email', $email);
                          })
                          ->first();
    }

    /**
     * Memperbarui status student berdasarkan ID.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateStudentStatus($id, $status)
    {
        $student = $this->findStudent($id);

        if ($student) {
            try {
                $student->update(['status' => $status]);
                return $this->model->with(['program', 'user'])->find($student->id);
            } catch (\Exception $e) {
                Log::error("Failed to update status for student with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }
}
