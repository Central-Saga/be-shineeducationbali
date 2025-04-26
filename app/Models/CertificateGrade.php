<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use App\Models\Certificate; // belum ada model ini
use App\Models\Grade;

class CertificateGrade extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'certificate_grade';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificate_id',
        'grade_id',
    ];

    /**
     * Relasi dengan sertifikat
     * Note: Definisi lengkap akan ditambahkan ketika model Certificate dibuat
     */
    public function certificate(): BelongsTo
    {
        // Gunakan string literal untuk sementara, akan diupdate saat model dibuat
        return $this->belongsTo('App\Models\Certificate');
    }

    /**
     * Relasi dengan nilai
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
}
