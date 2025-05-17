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
        'class_rooms_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    /**
     * Define status constants
     */
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 0;
    
    /**
     * Get the teacher that owns the attendance record.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the class that the attendance is for.
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_rooms_id');
    }

    /**
     * Scope a query to only include attendances for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
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
     * Calculate duration of attendance in minutes.
     */
    public function getDurationInMinutes()
    {
        // Pastikan check_in dan check_out ada sebelum menghitung durasi
        if ($this->check_in && $this->check_out) {
            // Jika masih string, konversi ke Carbon
            $checkIn = $this->check_in;
            $checkOut = $this->check_out;
            
            // Pastikan keduanya adalah objek Carbon
            if (!($checkIn instanceof \Carbon\Carbon)) {
                $checkIn = \Carbon\Carbon::parse($checkIn);
            }
            
            if (!($checkOut instanceof \Carbon\Carbon)) {
                $checkOut = \Carbon\Carbon::parse($checkOut);
            }
            
            return $checkIn->diffInMinutes($checkOut);
        }
        
        return 0;
    }

    /**
     * Get the attendance status attribute.
     * 
     * @param  string  $value
     * @return int
     */
    public function getStatusAttribute($value)
    {
        // If we have a string value, convert it to numeric
        if (is_string($value)) {
            return strtolower($value) === 'present' ? self::STATUS_PRESENT : self::STATUS_ABSENT;
        }
        return (int) $value;
    }

    /**
     * Set the attendance status attribute.
     * 
     * @param  mixed  $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        // If we receive a string value, convert it to numeric
        if (is_string($value)) {
            $this->attributes['status'] = strtolower($value) === 'present' ? self::STATUS_PRESENT : self::STATUS_ABSENT;
        } else {
            $this->attributes['status'] = $value;
        }
    }
}
