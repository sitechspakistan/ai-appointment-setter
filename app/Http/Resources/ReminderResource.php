<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'appointment_id' => $this->appointment_id,
            'channel' => $this->channel,
            'status' => $this->status,
            'scheduled_for' => $this->scheduled_for?->toIso8601String(),
            'sent_at' => $this->sent_at?->toIso8601String(),
            'outcome' => $this->outcome,
            'provider_message_id' => $this->provider_message_id,
            'appointment' => $this->whenLoaded('appointment', fn () => new AppointmentResource($this->appointment)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
