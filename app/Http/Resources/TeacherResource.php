<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'subject' => new SubjectResource($this->whenLoaded('subject')), // Include subject relationship
            'user' => new UserResource($this->whenLoaded('user')), // Include user relationship
            'employee_type' => $this->employee_type,
            'monthly_salary' => $this->monthly_salary,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}