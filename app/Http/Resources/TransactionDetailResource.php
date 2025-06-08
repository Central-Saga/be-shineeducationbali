<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'program' => new ProgramResource($this->whenLoaded('program')),
            'leave' => new LeaveResource($this->whenLoaded('leave')),
            'desc' => $this->desc,
            'amount' => $this->amount,
            'type' => $this->type,
            'session_count' => $this->session_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
