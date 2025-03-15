<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingFrequency extends Model
{
    /** @use HasFactory<\Database\Factories\MeetingFrequencyFactory> */
    use HasFactory;

    protected $table = 'meeting_frequencies';

    protected $fillable = [
        'frequency_name',
    ];
}
