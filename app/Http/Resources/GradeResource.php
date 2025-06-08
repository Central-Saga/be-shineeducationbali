<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'student_id' => $this->student_id,
            'class_rooms_id' => $this->class_rooms_id,
            'material_id' => $this->material_id,
            'assignment_id' => $this->assignment_id,
            'grade_category_id' => $this->grade_category_id,
            'score' => $this->score,
            'input_date' => $this->input_date,
            'student' => new StudentResource($this->whenLoaded('student')),
            'class_room' => new ClassRoomResource($this->whenLoaded('classRoom')),
            'material' => new MaterialResource($this->whenLoaded('material')),
            'assignment' => new AssignmentResource($this->whenLoaded('assignment')),
            'grade_category' => new GradeCategoryResource($this->whenLoaded('gradeCategory')),
            'certificate_grades' => CertificateGradeResource::collection($this->whenLoaded('certificateGrades')),
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
