<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassRoomFactory> */
    use HasFactory;

    protected $table = 'class_rooms';

    protected $fillable = [
        'program_id',
        'teacher_id',
        'class_room_name',
        'capacity',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classrooms');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
