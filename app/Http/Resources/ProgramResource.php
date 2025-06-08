<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'education_level' => $this->whenLoaded('educationLevel'),
            'subject' => $this->whenLoaded('subject'),
            'class_type' => $this->whenLoaded('classType'),
            'meeting_frequency' => $this->whenLoaded('meetingFrequency'),
            'program_name' => $this->program_name,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            'freelance_rate_per_session' => $this->freelance_rate_per_session,
            'min_parttime_sessions' => $this->min_parttime_sessions,
            'overtime_rate_per_session' => $this->overtime_rate_per_session,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
