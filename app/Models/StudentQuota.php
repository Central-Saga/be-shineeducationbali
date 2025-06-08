<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuota extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'program_id',
        'period',
        'sessions_paid',
        'sessions_used',
        'sessions_remaining',
        'sessions_accumulated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'period' => 'date',
        'sessions_paid' => 'integer',
        'sessions_used' => 'integer',
        'sessions_remaining' => 'integer',
        'sessions_accumulated' => 'integer',
    ];

    /**
     * Get the student that owns the quota.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the program that the quota belongs to.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Calculate and update sessions remaining.
     * 
     * @return self
     */
    public function calculateRemaining(): self
    {
        $this->sessions_remaining = $this->sessions_paid + $this->sessions_accumulated - $this->sessions_used;
        return $this;
    }

    /**
     * Use a session from the quota.
     * 
     * @param int $count Number of sessions to use
     * @return self
     */
    public function useSession(int $count = 1): self
    {
        $this->sessions_used += $count;
        $this->calculateRemaining();
        return $this;
    }

    /**
     * Add paid sessions to the quota.
     * 
     * @param int $count Number of sessions to add
     * @return self
     */
    public function addPaidSessions(int $count): self
    {
        $this->sessions_paid += $count;
        $this->calculateRemaining();
        return $this;
    }

    /**
     * Add accumulated sessions to the quota.
     * 
     * @param int $count Number of sessions to accumulate
     * @return self
     */
    public function addAccumulatedSessions(int $count): self
    {
        $this->sessions_accumulated += $count;
        $this->calculateRemaining();
        return $this;
    }

    /**
     * Scope a query to only include quotas for a specific period.
     */
    public function scopeForPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope a query to only include quotas with remaining sessions.
     */
    public function scopeWithRemainingQuota($query)
    {
        return $query->where('sessions_remaining', '>', 0);
    }
}
