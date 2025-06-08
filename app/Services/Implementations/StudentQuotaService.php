<?php

namespace App\Services\Implementations;

use App\Models\StudentQuota;
use App\Repositories\Contracts\StudentQuotaRepositoryInterface;
use App\Services\Contracts\StudentQuotaServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StudentQuotaService implements StudentQuotaServiceInterface
{
    /**
     * @var StudentQuotaRepositoryInterface
     */
    protected $studentQuotaRepository;

    /**
     * StudentQuotaService constructor.
     *
     * @param StudentQuotaRepositoryInterface $studentQuotaRepository
     */
    public function __construct(StudentQuotaRepositoryInterface $studentQuotaRepository)
    {
        $this->studentQuotaRepository = $studentQuotaRepository;
    }

    /**
     * Get all student quotas
     * 
     * @return Collection
     */
    public function getAllStudentQuotas()
    {
        return $this->studentQuotaRepository->getAllStudentQuotas();
    }

    /**
     * Get student quota by id
     * 
     * @param int $id
     * @return StudentQuota|null
     */
    public function getStudentQuotaById($id)
    {
        return $this->studentQuotaRepository->getStudentQuotaById($id);
    }

    /**
     * Get student quotas by student id
     * 
     * @param int $studentId
     * @return Collection
     */
    public function getQuotasByStudentId($studentId)
    {
        return $this->studentQuotaRepository->getQuotasByStudentId($studentId);
    }

    /**
     * Get student quotas by program id
     * 
     * @param int $programId
     * @return Collection
     */
    public function getQuotasByProgramId($programId)
    {
        return $this->studentQuotaRepository->getQuotasByProgramId($programId);
    }

    /**
     * Get active student quota for student and program
     * 
     * @param int $studentId
     * @param int $programId
     * @return StudentQuota|null
     */
    public function getActiveQuotaForStudentAndProgram($studentId, $programId)
    {
        $currentPeriod = Carbon::now()->startOfMonth()->format('Y-m-d');
        
        // Try to get the current period's quota first
        $quotas = StudentQuota::where('student_id', $studentId)
                             ->where('program_id', $programId)
                             ->where('period', $currentPeriod)
                             ->get();
        
        if ($quotas->isNotEmpty()) {
            return $quotas->first();
        }
        
        // If no quota for current period, check if there's any with remaining sessions
        $quotas = StudentQuota::where('student_id', $studentId)
                             ->where('program_id', $programId)
                             ->where('sessions_remaining', '>', 0)
                             ->orderBy('period', 'desc')
                             ->get();
        
        if ($quotas->isNotEmpty()) {
            return $quotas->first();
        }
        
        return null;
    }

    /**
     * Get student quotas for current period
     * 
     * @return Collection
     */
    public function getCurrentPeriodQuotas()
    {
        $currentPeriod = Carbon::now()->startOfMonth()->format('Y-m-d');
        return $this->studentQuotaRepository->getQuotasByPeriod($currentPeriod);
    }

    /**
     * Create student quota
     * 
     * @param array $data
     * @return StudentQuota
     */
    public function createStudentQuota(array $data)
    {
        // Ensure period is formatted correctly if provided, otherwise use current month
        if (!isset($data['period'])) {
            $data['period'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        } elseif (!$data['period'] instanceof Carbon) {
            try {
                $data['period'] = Carbon::parse($data['period'])->startOfMonth()->format('Y-m-d');
            } catch (\Exception $e) {
                $data['period'] = Carbon::now()->startOfMonth()->format('Y-m-d');
            }
        }
        
        return $this->studentQuotaRepository->createStudentQuota($data);
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
        // Format period if provided
        if (isset($data['period']) && !$data['period'] instanceof Carbon) {
            try {
                $data['period'] = Carbon::parse($data['period'])->startOfMonth()->format('Y-m-d');
            } catch (\Exception $e) {
                unset($data['period']);
            }
        }
        
        return $this->studentQuotaRepository->updateStudentQuota($id, $data);
    }

    /**
     * Use session from student quota
     * 
     * @param int $studentId
     * @param int $programId
     * @param int $sessionsToUse
     * @return StudentQuota|null
     */
    public function useSessionFromQuota($studentId, $programId, $sessionsToUse = 1)
    {
        $quota = $this->getActiveQuotaForStudentAndProgram($studentId, $programId);
        
        if (!$quota) {
            return null;
        }
        
        if ($quota->sessions_remaining < $sessionsToUse) {
            throw new \Exception("Insufficient quota. Only {$quota->sessions_remaining} sessions remaining.");
        }
        
        // Menggunakan getKey() untuk mendapatkan ID primary key dari model, lebih aman daripada mengakses properti id langsung
        return $this->studentQuotaRepository->addSessionsUsed($quota->getKey(), $sessionsToUse);
    }

    /**
     * Add paid sessions to student quota
     * 
     * @param int $id
     * @param int $sessionsToAdd
     * @return StudentQuota|null
     */
    public function addPaidSessions($id, $sessionsToAdd)
    {
        return $this->studentQuotaRepository->addSessionsPaid($id, $sessionsToAdd);
    }

    /**
     * Get quota usage summary for a student
     * 
     * @param int $studentId
     * @return array
     */
    public function getStudentQuotaSummary($studentId)
    {
        $quotas = $this->getQuotasByStudentId($studentId);
        
        $totalPaid = 0;
        $totalUsed = 0;
        $totalRemaining = 0;
        $programSummaries = [];
        
        foreach ($quotas as $quota) {
            $totalPaid += $quota->sessions_paid;
            $totalUsed += $quota->sessions_used;
            $totalRemaining += $quota->sessions_remaining;
            
            $programId = $quota->program_id;
            $programName = $quota->program ? $quota->program->program_name : "Program #$programId";
            
            if (!isset($programSummaries[$programId])) {
                $programSummaries[$programId] = [
                    'program_id' => $programId,
                    'program_name' => $programName,
                    'total_paid' => 0,
                    'total_used' => 0,
                    'total_remaining' => 0,
                    'current_period' => null,
                    'current_period_quota' => null,
                ];
            }
            
            $programSummaries[$programId]['total_paid'] += $quota->sessions_paid;
            $programSummaries[$programId]['total_used'] += $quota->sessions_used;
            $programSummaries[$programId]['total_remaining'] += $quota->sessions_remaining;
            
            // Track current period data
            $currentPeriod = Carbon::now()->startOfMonth()->format('Y-m-d');
            if ($quota->period == $currentPeriod) {
                $programSummaries[$programId]['current_period'] = $currentPeriod;
                $programSummaries[$programId]['current_period_quota'] = $quota->toArray();
            }
        }
        
        return [
            'student_id' => $studentId,
            'total_paid_sessions' => $totalPaid,
            'total_used_sessions' => $totalUsed,
            'total_remaining_sessions' => $totalRemaining,
            'usage_percentage' => $totalPaid > 0 ? round(($totalUsed / $totalPaid) * 100, 2) : 0,
            'program_summaries' => array_values($programSummaries),
        ];
    }

    /**
     * Initialize or renew student quota for a new period
     * 
     * @param int $studentId
     * @param int $programId
     * @param int $paidSessions
     * @param int $accumulatedFromPrevious
     * @return StudentQuota
     */
    public function initializeOrRenewStudentQuota($studentId, $programId, $paidSessions = 0, $accumulatedFromPrevious = 0)
    {
        $currentPeriod = Carbon::now()->startOfMonth()->format('Y-m-d');
        
        // Check if quota already exists for current period
        $existingQuota = StudentQuota::where('student_id', $studentId)
                                    ->where('program_id', $programId)
                                    ->where('period', $currentPeriod)
                                    ->first();
        
        if ($existingQuota) {
            // Update existing quota
            $existingQuota->sessions_paid += $paidSessions;
            $existingQuota->sessions_accumulated += $accumulatedFromPrevious;
            $existingQuota->calculateRemaining();
            $existingQuota->save();
            
            return $existingQuota;
        } else {
            // Create new quota for current period
            return $this->createStudentQuota([
                'student_id' => $studentId,
                'program_id' => $programId,
                'period' => $currentPeriod,
                'sessions_paid' => $paidSessions,
                'sessions_used' => 0,
                'sessions_accumulated' => $accumulatedFromPrevious,
                'sessions_remaining' => $paidSessions + $accumulatedFromPrevious
            ]);
        }
    }

    /**
     * Delete student quota
     * 
     * @param int $id
     * @return bool
     */
    public function deleteStudentQuota($id)
    {
        return $this->studentQuotaRepository->deleteStudentQuota($id);
    }
}
