<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'service_id' => $this->service_id,
            'service_name' => $this->service_name,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'notes' => $this->notes,
            'appointment_date' => optional($this->appointment_date)->toDateString(),
            'appointment_time' => $this->appointment_time
                ? Carbon::parse($this->appointment_time)->format('H:i')
                : null,
            'starts_at' => $this->appointment_date && $this->appointment_time
                ? Carbon::parse($this->appointment_date->toDateString().' '.$this->appointment_time)->toIso8601String()
                : null,
            'status' => $this->status,
            'source' => $this->source,
            'confirmed_at' => $this->confirmed_at?->toIso8601String(),
            'confirmation_method' => $this->confirmation_method,
            'tenant' => $this->whenLoaded('tenant', fn () => new TenantResource($this->tenant)),
            'reminders' => $this->whenLoaded('reminders', fn () => ReminderResource::collection($this->reminders)),
            'call_logs' => $this->whenLoaded('callLogs', fn () => CallLogResource::collection($this->callLogs)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
