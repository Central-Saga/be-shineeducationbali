<?php

namespace App\Http\Resources;

use App\Models\JobApplicationStatus;
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
        // Convert enum status to text description
        $statusText = match($this->status) {
            JobApplicationStatus::Pending => 'pending',
            JobApplicationStatus::Reviewed => 'reviewed',
            JobApplicationStatus::Accepted => 'accepted',
            JobApplicationStatus::Rejected => 'rejected',
            default => 'pending'
        };

        return [
            'id' => $this->id,
            'job_vacancy' => new JobVacancyResource($this->whenLoaded('jobVacancy')),
            'user' => new UserResource($this->whenLoaded('user')),
            'application_date' => $this->application_date->toDateString(),
            'status' => $statusText,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
