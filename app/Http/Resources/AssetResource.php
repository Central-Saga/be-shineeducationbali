<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'file_path' => $this->file_path,
            'full_path' => $this->full_path,
            'size_kb' => $this->size_kb,
            'upload_date' => $this->upload_date,
            'description' => $this->description,
            'storage_type' => $this->storage_type,
            'file_extension' => $this->file_extension,
            'is_image' => $this->is_image,
            'is_document' => $this->is_document,
            'assetable_id' => $this->assetable_id,
            'assetable_type' => $this->assetable_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
