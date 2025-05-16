<?php

namespace App\Services\Implementations;

use App\Services\Contracts\AssignmentServiceInterface;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Assignment;

class AssignmentService implements AssignmentServiceInterface
{
    protected $repository;

    const ASSIGNMENTS_ALL_CACHE_KEY = 'assignments_all';
    const ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY = 'assignments_not_completed';
    const ASSIGNMENTS_COMPLETED_CACHE_KEY = 'assignments_completed';
    const ASSIGNMENTS_REJECTED_CACHE_KEY = 'assignments_rejected';
    const ASSIGNMENTS_PENDING_CACHE_KEY = 'assignments_pending';
    const ASSIGNMENTS_BY_STATUS_CACHE_KEY = 'assignments_by_status_';

    public function __construct(AssignmentRepositoryInterface $repository)
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
        // Log untuk debugging
        Log::info('Getting assignments by status:', ['status' => $status]);

        // Validasi status yang diterima
        $validStatuses = [
            Assignment::STATUS_PENDING,
            Assignment::STATUS_COMPLETED,
            Assignment::STATUS_REJECTED,
            Assignment::STATUS_NOT_COMPLETED
        ];

        if (!in_array($status, $validStatuses)) {
            Log::error('Invalid status requested:', [
                'provided_status' => $status,
                'valid_statuses' => $validStatuses
            ]);
            throw new \InvalidArgumentException('Status tidak valid');
        }

        $cacheKey = self::ASSIGNMENTS_BY_STATUS_CACHE_KEY . strtolower(str_replace(' ', '_', $status));
        
        return Cache::remember($cacheKey, 3600, function () use ($status) {
            $assignments = $this->repository->getAssignmentByStatus($status);
            
            // Log untuk debugging
            Log::info('Found assignments:', [
                'status' => $status,
                'count' => $assignments->count(),
                'first_assignment' => $assignments->first()
            ]);
            
            return $assignments;
        });
    }

    /**
     * Mengambil assignment berdasarkan status Belum Terselesaikan.
     *
     * @return mixed
     */
    public function getAssignmentByNotCompleted()
    {
        return Cache::remember(self::ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY, 3600, function () {
            return $this->repository->getAssignmentByStatus(Assignment::STATUS_NOT_COMPLETED);
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
            return $this->repository->getAssignmentByStatus(Assignment::STATUS_COMPLETED);
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
            return $this->repository->getAssignmentByStatus(Assignment::STATUS_REJECTED);
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
            return $this->repository->getAssignmentByStatus(Assignment::STATUS_PENDING);
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
        // Log untuk debugging
        Log::info('Updating assignment:', [
            'id' => $id,
            'data' => $data
        ]);

        $assignment = $this->repository->updateAssignment($id, $data);
        
        if ($assignment) {
            // Clear all related caches when assignment is updated
            $this->clearAssignmentCache();
            
            Log::info('Assignment updated successfully:', [
                'id' => $id,
                'new_status' => $assignment->status
            ]);
        } else {
            Log::error('Failed to update assignment:', [
                'id' => $id
            ]);
        }
        
        return $assignment;
    }

    /**
     * Menghapus assignment berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAssignment($id)
    {
        Log::info('Service: Attempting to delete assignment:', ['id' => $id]);

        // First check if the assignment exists
        $assignment = $this->repository->getAssignmentById($id);
        if (!$assignment) {
            Log::error('Service: Assignment not found for deletion', ['id' => $id]);
            return false;
        }

        Log::info('Service: Found assignment for deletion', [
            'id' => $id,
            'status' => $assignment->status,
            'class_room_id' => $assignment->class_room_id
        ]);
        
        $result = $this->repository->deleteAssignment($id);
        
        if ($result) {
            // Clear all related caches
            $this->clearAssignmentCache(); // Use the existing cache clearing method
            Log::info('Service: Assignment deleted successfully, cache cleared', ['id' => $id]);
        } else {
            Log::error('Service: Failed to delete assignment', [
                'id' => $id,
                'error' => 'Unknown error occurred during deletion'
            ]);
        }
        
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
        
        // Clear status-specific caches
        $statuses = [
            'dalam_pengajuan',
            'terselesaikan',
            'ditolak',
            'belum_terselesaikan'
        ];
        
        foreach ($statuses as $status) {
            Cache::forget(self::ASSIGNMENTS_BY_STATUS_CACHE_KEY . $status);
        }
    }

    /**
     * Clear cache when assignment status changes
     */
    private function clearAssignmentCache()
    {
        Cache::forget(self::ASSIGNMENTS_ALL_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_NOT_COMPLETED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_COMPLETED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_REJECTED_CACHE_KEY);
        Cache::forget(self::ASSIGNMENTS_PENDING_CACHE_KEY);
        
        // Clear status-specific caches
        $statuses = [
            'dalam_pengajuan',
            'terselesaikan',
            'ditolak',
            'belum_terselesaikan'
        ];
        
        foreach ($statuses as $status) {
            Cache::forget(self::ASSIGNMENTS_BY_STATUS_CACHE_KEY . $status);
        }

        Log::info('Assignment caches cleared');
    }
}
