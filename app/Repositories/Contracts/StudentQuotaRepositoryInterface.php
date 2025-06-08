<?php

namespace App\Repositories\Contracts;

use App\Models\StudentQuota;
use Illuminate\Database\Eloquent\Collection;

interface StudentQuotaRepositoryInterface
{
    /**
     * Get all student quotas
     * 
     * @return Collection
     */
    public function getAllStudentQuotas();

    /**
     * Get student quota by id
     * 
     * @param int $id
     * @return StudentQuota|null
     */
    public function getStudentQuotaById($id);

    /**
     * Get student quotas by student id
     * 
     * @param int $studentId
     * @return Collection
     */
    public function getQuotasByStudentId($studentId);

    /**
     * Get student quotas by program id
     * 
     * @param int $programId
     * @return Collection
     */
    public function getQuotasByProgramId($programId);

    /**
     * Get student quotas for a specific period
     * 
     * @param string $period
     * @return Collection
     */
    public function getQuotasByPeriod($period);

    /**
     * Get student quotas with remaining sessions
     * 
     * @return Collection
     */
    public function getQuotasWithRemainingSessions();

    /**
     * Create student quota
     * 
     * @param array $data
     * @return StudentQuota
     */
    public function createStudentQuota(array $data);

    /**
     * Update student quota
     * 
     * @param int $id
     * @param array $data
     * @return StudentQuota|null
     */
    public function updateStudentQuota($id, array $data);

    /**
     * Update sessions used
     * 
     * @param int $id
     * @param int $sessionsUsed
     * @return StudentQuota|null
     */
    public function updateSessionsUsed($id, int $sessionsUsed);

    /**
     * Add sessions used and recalculate remaining
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addSessionsUsed($id, int $sessionsToAdd);

    /**
     * Add sessions paid and recalculate remaining
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addSessionsPaid($id, int $sessionsToAdd);

    /**
     * Delete student quota
     * 
     * @param int $id
     * @return bool
     */
    public function deleteStudentQuota($id);
}
