<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

enum JobVacancyStatus: string
{
    case Open = 'Open';
    case Closed = 'Closed';
}

class JobVacancy extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_vacancies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'salary',
        'application_deadline',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'application_deadline' => 'date',
        'status' => JobVacancyStatus::class,
    ];

    /**
     * Get the subject that owns the job vacancy.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
