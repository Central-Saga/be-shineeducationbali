<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'subject_name',
    ];

    public function teacher(){
        return $this->hasMany(Teacher::class, 'subject_id', 'id');
    }
}
