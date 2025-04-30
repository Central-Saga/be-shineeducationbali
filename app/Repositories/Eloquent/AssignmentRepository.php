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
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
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
        return $this->model->with('classRoom')->where('status', $status)->get();
    }

    /**
     * Mengambil assignment berdasarkan status Belum Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByNotCompleted()
    {
        return $this->model->with('classRoom')->where('status', 'not_completed')->get();
    }

    /**
     * Mengambil assignment berdasarkan status Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByCompleted()
    {
        return $this->model->with('classRoom')->where('status', 'completed')->get();
    }

    /**
     * Mengambil assignment berdasarkan status Ditolak.
     *
     * @return mixed
     */
    public function getAssignmentByRejected()
    {
        return $this->model->with('classRoom')->where('status', 'rejected')->get();
    }

    /**
     * Mengambil assignment berdasarkan status Dalam Pengajuan.
     *
     * @return mixed
     */
    public function getAssignmentByPending()
    {
        return $this->model->with('classRoom')->where('status', 'pending')->get();
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
     * @return mixed
     */
    public function deleteAssignment($id)
    {
        $assignment = $this->findAssignment($id);

        if ($assignment) {
            try {
                $assignment->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete assignment with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
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
