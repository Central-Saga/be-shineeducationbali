<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Assignment extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentFactory> */
    use HasFactory;
    
    // Definisi status yang valid sesuai dengan database
    const STATUS_PENDING = 'Dalam Pengajuan';
    const STATUS_COMPLETED = 'Terselesaikan';
    const STATUS_REJECTED = 'Ditolak';
    const STATUS_NOT_COMPLETED = 'Belum Terselesaikan';

    protected $table = 'assignments';

    protected $fillable = [
        'student_id',
        'class_room_id',
        'teacher_id',
        'material_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    /**
     * Handle model events 
     */
    protected static function boot()
    {
        parent::boot();

        // Handle validation when saving
        static::saving(function ($assignment) {
            $validStatuses = [
                self::STATUS_PENDING,
                self::STATUS_COMPLETED,
                self::STATUS_REJECTED,
                self::STATUS_NOT_COMPLETED
            ];
            
            if (!in_array($assignment->status, $validStatuses)) {
                Log::error('Invalid assignment status:', [
                    'provided_status' => $assignment->status,
                    'valid_statuses' => $validStatuses
                ]);
                throw new \InvalidArgumentException(sprintf(
                    'Status tidak valid. Status yang tersedia: %s',
                    implode(', ', $validStatuses)
                ));
            }
        });

        // Handle cleanup when deleting
        static::deleting(function ($assignment) {
            Log::info('Model: Preparing to delete Assignment and related data', ['id' => $assignment->id]);

            try {
                DB::beginTransaction();
                
                // Delete associated assets
                $assetCount = $assignment->assets()->count();
                if ($assetCount > 0) {
                    $assignment->assets()->delete();
                    Log::info('Model: Deleted Assignment assets', [
                        'assignment_id' => $assignment->id,
                        'assets_count' => $assetCount
                    ]);
                }

                // Delete associated grades
                $gradeCount = $assignment->grades()->count();
                if ($gradeCount > 0) {
                    $assignment->grades()->delete();
                    Log::info('Model: Deleted Assignment grades', [
                        'assignment_id' => $assignment->id,
                        'grades_count' => $gradeCount
                    ]);
                }

                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Model: Failed to delete Assignment related data', [
                    'id' => $assignment->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e; // Re-throw to be handled by the controller
            }
        });
    }

    // Accessor untuk memudahkan pengecekan status
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isNotCompleted()
    {
        return $this->status === self::STATUS_NOT_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    
    /**
     * Get all assets for this assignment
     * 
     * @return MorphMany
     */
    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    /**
     * Get all grades for this assignment
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
