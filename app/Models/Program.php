<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramFactory> */
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'education_level_id',
        'subject_id',
        'class_type_id',
        'meeting_frequency_id',
        'program_name',
        'description',
        'price',
        'sku',
        'freelance_rate_per_session',
        'min_parttime_sessions',
        'overtime_rate_per_session',
        'status',
    ];

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function meetingFrequency()
    {
        return $this->belongsTo(MeetingFrequency::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
    public function student()
    {
        return $this->hasMany(Material::class);
    }


    public function gradeCategories()
    {
        return $this->hasMany(GradeCategory::class, 'program_id');
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);

    }
}
