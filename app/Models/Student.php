<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * Kolom-kolom yang dapat diisi secara massal (mass assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'user_id',
        'start_date',
        'end_date',
        'registration_date',
        'status',
    ];

    /**
     * Definisikan hubungan dengan model Program.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Definisikan hubungan dengan model User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
    /**
     * Definisikan hubungan dengan model ClassRoom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'student_classrooms');
    }

    /**
     * Definisikan hubungan dengan model Assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    
    /**
     * Get all assets for this student
     * 
     * @return MorphMany
     */
    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    /**
     * Get all certificates for this student
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Definisikan hubungan dengan model StudentQuota.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentQuotas()
    {
        return $this->hasMany(StudentQuota::class);
    }

    /**
     * Definisikan hubungan dengan model StudentAttendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * Definisikan hubungan dengan model Grade.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
