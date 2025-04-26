<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'subject_id',
        'user_id',
        'employee_type',
        'monthly_salary',
        'status',

    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }
}
