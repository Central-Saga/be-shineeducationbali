<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentQuotaResource extends JsonResource
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
            'program_id' => $this->program_id,
            'period' => $this->period,
            'sessions_paid' => $this->sessions_paid,
            'sessions_used' => $this->sessions_used,
            'sessions_remaining' => $this->sessions_remaining,
            'sessions_accumulated' => $this->sessions_accumulated,
            'student' => new StudentResource($this->whenLoaded('student')),
            'program' => new ProgramResource($this->whenLoaded('program')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
