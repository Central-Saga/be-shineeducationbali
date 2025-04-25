<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// Tambahkan import kelas yang ada
use App\Models\Student;
use App\Models\ClassType;
use App\Models\Material;
use App\Models\CertificateGrade;

class Grade extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'grade';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'class_id',
        'material_id',
        'assignment_id',
        'grade_category_id',
        'score',
        'input_date',
    ];

    /**
     * Konversi tipe data kolom tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'input_date' => 'date',
        'score' => 'float',
    ];

    /**
     * Relasi dengan siswa
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi dengan kelas
     * Note: class adalah keyword di PHP, jadi kita lebih baik menggunakan nama lain seperti classType
     */
    public function classType(): BelongsTo
    {
        return $this->belongsTo(ClassType::class, 'class_id');
    }

    /**
     * Relasi dengan materi
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Relasi dengan tugas
     * Note: Definisi lengkap akan ditambahkan ketika model Assignment dibuat
     */
    public function assignment(): BelongsTo
    {
        // Gunakan string literal untuk sementara, akan diupdate saat model dibuat
        return $this->belongsTo('App\Models\Assignment');
    }

    /**
     * Relasi dengan kategori nilai
     * Note: Definisi lengkap akan ditambahkan ketika model GradeCategory dibuat
     */
    public function gradeCategory(): BelongsTo
    {
        // Gunakan string literal untuk sementara, akan diupdate saat model dibuat
        return $this->belongsTo('App\Models\GradeCategory', 'grade_category_id');
    }

    /**
     * Relasi dengan nilai sertifikat
     */
    public function certificateGrades(): HasMany
    {
        return $this->hasMany(CertificateGrade::class);
    }
}
