<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobVacancyResource extends JsonResource
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
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'title' => $this->title,
            'description' => $this->description,
            'salary' => $this->salary,
            'application_deadline' => $this->application_deadline->toDateString(), // Format tanggal: YYYY-MM-DD
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
