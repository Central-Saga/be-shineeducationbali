<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
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
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder' => $this->account_holder,
            'account_type' => $this->account_type,
            'status' => $this->status === 'Aktif' ? 'active' : 'inactive',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
