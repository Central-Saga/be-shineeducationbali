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
        // Map application statuses to numeric values
        $statusMap = [
            'Pending' => '0',
            'Reviewed' => '1',
            'Accepted' => '2',
            'Rejected' => '3'
        ];

        // Map application statuses to text descriptions
        $statusTextMap = [
            'Pending' => 'pending',
            'Reviewed' => 'reviewed',
            'Accepted' => 'accepted',
            'Rejected' => 'rejected'
        ];

        return [
            'id' => $this->id,
            'job_vacancy' => new JobVacancyResource($this->whenLoaded('jobVacancy')),
            'user' => new UserResource($this->whenLoaded('user')),
            'application_date' => $this->application_date->toDateTimeString(), // Format: YYYY-MM-DD HH:MM:SS
            'status' => $statusMap[$this->status] ?? '0',
            'status_text' => $statusTextMap[$this->status] ?? 'pending',
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
