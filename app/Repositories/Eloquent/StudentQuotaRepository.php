<?php

namespace App\Repositories\Eloquent;

use App\Models\StudentQuota;
use App\Repositories\Contracts\StudentQuotaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class StudentQuotaRepository implements StudentQuotaRepositoryInterface
{
    /**
     * @var StudentQuota
     */
    protected $model;

    /**
     * StudentQuotaRepository constructor.
     *
     * @param StudentQuota $model
     */
    public function __construct(StudentQuota $model)
    {
        $this->model = $model;
    }

    /**
     * Get all student quotas
     * 
     * @return Collection
     */
    public function getAllStudentQuotas()
    {
        return $this->model->all();
    }

    /**
     * Get student quota by id
     * 
     * @param int $id
     * @return StudentQuota|null
     */
    public function getStudentQuotaById($id): ?StudentQuota
    {
        $model = $this->model->find($id);
        return $model instanceof StudentQuota ? $model : null;
    }

    /**
     * Get student quotas by student id
     * 
     * @param int $studentId
     * @return Collection
     */
    public function getQuotasByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)->get();
    }

    /**
     * Get student quotas by program id
     * 
     * @param int $programId
     * @return Collection
     */
    public function getQuotasByProgramId($programId)
    {
        return $this->model->where('program_id', $programId)->get();
    }

    /**
     * Get student quotas for a specific period
     * 
     * @param string $period
     * @return Collection
     */
    public function getQuotasByPeriod($period)
    {
        return $this->model->forPeriod($period)->get();
    }

    /**
     * Get student quotas with remaining sessions
     * 
     * @return Collection
     */
    public function getQuotasWithRemainingSessions()
    {
        return $this->model->withRemainingQuota()->get();
    }

    /**
     * Create student quota
     * 
     * @param array $data
     * @return StudentQuota
     */
    public function createStudentQuota(array $data)
    {
        // Ensure we calculate sessions_remaining if not provided
        if (!isset($data['sessions_remaining'])) {
            $sessionsRemaining = ($data['sessions_paid'] ?? 0) + 
                               ($data['sessions_accumulated'] ?? 0) - 
                               ($data['sessions_used'] ?? 0);
            $data['sessions_remaining'] = $sessionsRemaining;
        }

        return $this->model->create($data);
    }

    /**
     * Update student quota
     * 
     * @param int $id
     * @param array $data
     * @return StudentQuota|null
     */
    public function updateStudentQuota($id, array $data)
    {
        $studentQuota = $this->getStudentQuotaById($id);
        
        if (!$studentQuota) {
            return null;
        }
        
        // Recalculate sessions_remaining if relevant fields are updated
        if (isset($data['sessions_paid']) || isset($data['sessions_used']) || isset($data['sessions_accumulated'])) {
            $sessionsRemaining = 
                (isset($data['sessions_paid']) ? $data['sessions_paid'] : $studentQuota->sessions_paid) +
                (isset($data['sessions_accumulated']) ? $data['sessions_accumulated'] : $studentQuota->sessions_accumulated) -
                (isset($data['sessions_used']) ? $data['sessions_used'] : $studentQuota->sessions_used);

            $data['sessions_remaining'] = $sessionsRemaining;
        }

        $studentQuota->update($data);
        return $studentQuota;
    }

    /**
     * Update sessions used
     * 
     * @param int $id
     * @param int $sessionsUsed
     * @return StudentQuota|null
     */
    public function updateSessionsUsed($id, int $sessionsUsed)
    {
        $studentQuota = $this->getStudentQuotaById($id);
        
        if (!$studentQuota) {
            return null;
        }
        
        $studentQuota->sessions_used = $sessionsUsed;
        $studentQuota->calculateRemaining();
        $studentQuota->save();
        
        return $studentQuota;
    }

    /**
     * Add sessions used and recalculate remaining
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addSessionsUsed($id, int $sessionsToAdd)
    {
        $studentQuota = $this->getStudentQuotaById($id);
        
        if (!$studentQuota) {
            return null;
        }
        
        $studentQuota->useSession($sessionsToAdd);
        $studentQuota->save();
        
        return $studentQuota;
    }

    /**
     * Add sessions paid and recalculate remaining
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addSessionsPaid($id, int $sessionsToAdd)
    {
        $studentQuota = $this->getStudentQuotaById($id);
        
        if (!$studentQuota) {
            return null;
        }
        
        $studentQuota->addPaidSessions($sessionsToAdd);
        $studentQuota->save();
        
        return $studentQuota;
    }

    /**
     * Delete student quota
     * 
     * @param int $id
     * @return bool
     */
    public function deleteStudentQuota($id)
    {
        $studentQuota = $this->getStudentQuotaById($id);
        
        if (!$studentQuota) {
            return false;
        }
        
        return $studentQuota->delete();
    }
}
