<?php

namespace App\Http\Requests\Api;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'integer', Rule::exists('tenants', 'id')],
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')->where('tenant_id', $this->input('tenant_id'))],
            'service_name' => ['required_without:service_id', 'nullable', 'string', 'max:120'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'status' => ['nullable', Rule::in([
                Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED, Appointment::STATUS_DECLINED,
                Appointment::STATUS_COMPLETED, Appointment::STATUS_NO_SHOW,
            ])],
            'source' => ['nullable', Rule::in(['web', 'embed', 'phone', 'manual'])],
            'queue_reminder' => ['nullable', 'boolean'],
            'reminder_channel' => ['nullable', Rule::in(['whatsapp', 'voice'])],
            'reminder_hours_before' => ['nullable', 'integer', 'min:0', 'max:168'],
        ];
    }
}
