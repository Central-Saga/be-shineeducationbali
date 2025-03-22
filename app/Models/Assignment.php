<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentFactory> */
    use HasFactory;

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
}
