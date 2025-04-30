<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
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
            'job_vacancy' => new JobVacancyResource($this->whenLoaded('jobVacancy')),
            'user' => new UserResource($this->whenLoaded('user')),
            'application_date' => $this->application_date->toDateTimeString(), // Format: YYYY-MM-DD HH:MM:SS
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
