<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassRoomResource extends JsonResource
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
            'program_id' => $this->program_id,
            'teacher_id' => $this->teacher_id,
            'class_room_name' => $this->class_room_name,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Relasi yang bisa di-load sesuai kebutuhan
            'program' => new ProgramResource($this->whenLoaded('program')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'materials' => MaterialResource::collection($this->whenLoaded('materials')),
            'assignments' => AssignmentResource::collection($this->whenLoaded('assignments')),
        ];
    }
}
