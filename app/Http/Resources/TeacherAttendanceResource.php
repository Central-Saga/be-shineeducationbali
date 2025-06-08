<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'class_rooms_id' => $this->class_rooms_id,
            'attendance_date' => $this->attendance_date,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'status' => $this->status,
            'duration_minutes' => $this->getDurationInMinutes(),
            'is_present' => $this->isPresent(),
            'is_absent' => $this->isAbsent(),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'class_room' => new ClassRoomResource($this->whenLoaded('classRoom')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
