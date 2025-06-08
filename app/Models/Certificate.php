<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    /** @use HasFactory<\Database\Factories\CertificateFactory> */
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'student_id',
        'program_id',
        'issue_date'
    ];

    protected $casts = [
        'issue_date' => 'date'
    ];

    /**
     * Get the student that owns the certificate
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the program associated with the certificate
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the certificate grades for this certificate
     */
    public function certificateGrades()
    {
        return $this->hasMany(CertificateGrade::class);
    }
}
