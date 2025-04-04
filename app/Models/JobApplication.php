<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

enum JobApplicationStatus: string
{
    case Pending = 'Pending';
    case Reviewed = 'Reviewed';
    case Accepted = 'Accepted';
    case Rejected = 'Rejected';
}

class JobApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vacancy_id',
        'user_id',
        'application_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'application_date' => 'datetime',
        'status' => JobApplicationStatus::class,
    ];

    /**
     * Get the job vacancy that owns the job application.
     */
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'vacancy_id');
    }

    /**
     * Get the user that owns the job application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
