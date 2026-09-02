<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'appointment_id' => $this->appointment_id,
            'vapi_call_id' => $this->vapi_call_id,
            'status' => $this->status,
            'outcome' => $this->outcome,
            'recording_url' => $this->recording_url,
            'duration_seconds' => $this->duration_seconds,
            'started_at' => $this->started_at?->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
