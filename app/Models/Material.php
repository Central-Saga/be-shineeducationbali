<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
