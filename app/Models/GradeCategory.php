<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeCategory extends Model
{
    use HasFactory;

    protected $table = 'grade_categories';

    protected $fillable = [
        'program_id',
        'category_name',
        'description',
    ];
    /**
     * Define the relationship with the Program model (One-to-Many, inverse).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
