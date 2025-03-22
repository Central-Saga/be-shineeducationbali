<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'class_room_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
