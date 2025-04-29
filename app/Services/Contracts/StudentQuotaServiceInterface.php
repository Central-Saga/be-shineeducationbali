<?php

namespace App\Services\Contracts;

use App\Models\StudentQuota;
use Illuminate\Database\Eloquent\Collection;

interface StudentQuotaServiceInterface
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
     * Get active student quota for student and program
     * 
     * @param int $studentId
     * @param int $programId
     * @return StudentQuota|null
     */
    public function getActiveQuotaForStudentAndProgram($studentId, $programId);

    /**
     * Get student quotas for current period
     * 
     * @return Collection
     */
    public function getCurrentPeriodQuotas();

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
     * Use session from student quota
     * 
     * @param int $studentId
     * @param int $programId
     * @param int $sessionsToUse
     * @return StudentQuota|null
     */
    public function useSessionFromQuota($studentId, $programId, $sessionsToUse = 1);

    /**
     * Add paid sessions to student quota
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addPaidSessions($id, $sessionsToAdd);

    /**
     * Get quota usage summary for a student
     * 
     * @param int $studentId
     * @return array
     */
    public function getStudentQuotaSummary($studentId);

    /**
     * Initialize or renew student quota for a new period
     * 
     * @param int $studentId
     * @param int $programId
     * @param int $paidSessions
     * @param int $accumulatedFromPrevious
     * @return StudentQuota
     */
    public function initializeOrRenewStudentQuota($studentId, $programId, $paidSessions = 0, $accumulatedFromPrevious = 0);

    /**
     * Delete student quota
     * 
     * @param int $id
     * @return bool
     */
    public function deleteStudentQuota($id);
}
