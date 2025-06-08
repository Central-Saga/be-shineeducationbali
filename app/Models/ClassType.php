<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    /** @use HasFactory<\Database\Factories\ClassTypeFactory> */
    use HasFactory;

    protected $table = 'class_types';

    protected $fillable = [
        'class_type_name',
        'student_count',
    ];
}
