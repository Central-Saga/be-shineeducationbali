<?php

namespace App\Repositories\Eloquent;

use App\Models\Assignment;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    protected $model;

    public function __construct(Assignment $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua assignments.
     *
     * @return mixed
     */
    public function getAllAssignments()
    {
        return $this->model->with('classRoom')->get();
    }

    /**
     * Mengambil assignment berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getAssignmentById($id)
    {
        try {
            return $this->model->with('classRoom')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Assignment with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil assignment berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getAssignmentByName($name)
    {
        return $this->model->with('classRoom')->where('name', $name)->first();
    }

    /**
     * Mengambil assignment berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAssignmentByStatus($status)
    {
        // Log sebelum query
        Log::info('Getting assignments with status:', ['status' => $status]);
        
        $assignments = $this->model->with('classRoom')->where('status', $status)->get();
        
        // Log hasil query
        Log::info('Query result:', [
            'status' => $status,
            'count' => $assignments->count(),
            'sql' => $this->model->with('classRoom')->where('status', $status)->toSql(),
            'bindings' => $this->model->with('classRoom')->where('status', $status)->getBindings()
        ]);
        
        return $assignments;
    }

    /**
     * Mengambil assignment berdasarkan status Belum Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByNotCompleted()
    {
        return $this->model->with('classRoom')->where('status', Assignment::STATUS_NOT_COMPLETED)->get();
    }

    /**
     * Mengambil assignment berdasarkan status Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByCompleted()
    {
        return $this->model->with('classRoom')->where('status', Assignment::STATUS_COMPLETED)->get();
    }

    /**
     * Mengambil assignment berdasarkan status Ditolak.
     *
     * @return mixed
     */
    public function getAssignmentByRejected()
    {
        return $this->model->with('classRoom')->where('status', Assignment::STATUS_REJECTED)->get();
    }

    /**
     * Mengambil assignment berdasarkan status Dalam Pengajuan.
     *
     * @return mixed
     */
    public function getAssignmentByPending()
    {
        return $this->model->with('classRoom')->where('status', Assignment::STATUS_PENDING)->get();
    }

    /**
     * Membuat assignment baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createAssignment(array $data)
    {
        try {
            $assignment = $this->model->create($data);
            return $assignment->load('classRoom');
        } catch (\Exception $e) {
            Log::error("Failed to create assignment: {$e->getMessage()}");
            return null;
        }
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
        $assignment = $this->findAssignment($id);

        if ($assignment) {
            try {
                $assignment->update($data);
                return $assignment->load('classRoom');
            } catch (\Exception $e) {
                Log::error("Failed to update assignment with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus assignment berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAssignment($id)
    {
        Log::info('Repository: Attempting to delete assignment:', ['id' => $id]);
        
        try {
            \DB::beginTransaction();
            
            $assignment = $this->model->with([
                'student',
                'classRoom',
                'teacher',
                'material',
                'assets',
                'grades'
            ])->find($id);
            
            if (!$assignment) {
                Log::error('Repository: Assignment not found for deletion', ['id' => $id]);
                return false;
            }

            // Log assignment details and related data before deletion
            Log::info('Repository: Found assignment for deletion', [
                'id' => $id,
                'status' => $assignment->status,
                'student_id' => $assignment->student_id,
                'class_room_id' => $assignment->class_room_id,
                'teacher_id' => $assignment->teacher_id,
                'material_id' => $assignment->material_id,
                'has_assets' => $assignment->assets()->count() > 0,
                'has_grades' => $assignment->grades()->count() > 0
            ]);

            // Delete related data first
            try {
                // Delete associated assets if any
                if ($assignment->assets()->count() > 0) {
                    $assignment->assets()->delete();
                    Log::info('Repository: Deleted assignment assets', ['id' => $id]);
                }

                // Delete associated grades if any
                if ($assignment->grades()->count() > 0) {
                    $assignment->grades()->delete();
                    Log::info('Repository: Deleted assignment grades', ['id' => $id]);
                }

                // Now delete the assignment
                $deleted = $assignment->delete();
                
                if ($deleted) {
                    \DB::commit();
                    Log::info('Repository: Assignment and related data successfully deleted', ['id' => $id]);
                    return true;
                } else {
                    \DB::rollBack();
                    Log::error('Repository: Failed to delete assignment - delete() returned false', ['id' => $id]);
                    return false;
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                Log::error('Repository: Error deleting assignment or related data:', [
                    'id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Repository: Error in delete process:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Helper method untuk menemukan assignment berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findAssignment($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Assignment with ID {$id} not found.");
            return null;
        }
    }
}
