<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'schedule_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    /**
     * Define status constants
     */
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_LEAVE = 'leave';
    
    /**
     * Get the teacher that owns the attendance record.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the schedule that the attendance is for.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Scope a query to only include attendances for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope a query to only include attendances for a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include present attendances.
     */
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * Scope a query to only include absent attendances.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    /**
     * Scope a query to only include late attendances.
     */
    public function scopeLate($query)
    {
        return $query->where('status', self::STATUS_LATE);
    }

    /**
     * Check if the teacher was present.
     */
    public function isPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }

    /**
     * Check if the teacher was absent.
     */
    public function isAbsent(): bool
    {
        return $this->status === self::STATUS_ABSENT;
    }

    /**
     * Check if the teacher was late.
     */
    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }

    /**
     * Check if the teacher was on leave.
     */
    public function isOnLeave(): bool
    {
        return $this->status === self::STATUS_LEAVE;
    }

    /**
     * Calculate duration of attendance in minutes.
     */
    public function getDurationInMinutes()
    {
        if ($this->time_in && $this->time_out) {
            return $this->time_in->diffInMinutes($this->time_out);
        }
        
        return 0;
    }
}
