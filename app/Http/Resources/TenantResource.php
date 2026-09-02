<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Tenant configuration for the n8n worker. Falls back to Webefy's
 * shared defaults where a tenant has not been given its own provider IDs.
 */
class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'booking_slug' => $this->booking_slug,
            'status' => $this->status,
            'timezone' => $this->timezone,
            'industry' => $this->industry,
            'booking_url' => $this->bookingUrl(),
            'vapi' => [
                'phone_number_id' => $this->vapi_phone_number_id ?: Setting::get('default_vapi_phone_number_id'),
                'assistant_id' => $this->vapi_assistant_id ?: Setting::get('default_vapi_assistant_id'),
                'uses_default' => empty($this->vapi_assistant_id),
            ],
            'whatsapp' => [
                'phone_number_id' => $this->whatsapp_phone_number_id ?: Setting::get('default_whatsapp_phone_number_id'),
                'template_name' => $this->whatsapp_template_name ?: Setting::get('default_whatsapp_template_name'),
                'uses_default' => empty($this->whatsapp_phone_number_id),
            ],
            'confirmation_call_script' => $this->confirmation_call_script,
            'whatsapp_reminder_message' => $this->whatsapp_reminder_message,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
