<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'content',
    ];

    /**
     * Get the student that owns the testimonial.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
