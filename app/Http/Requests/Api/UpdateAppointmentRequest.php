<?php

namespace App\Http\Requests\Api;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', Rule::in([
                Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED, Appointment::STATUS_DECLINED,
                Appointment::STATUS_COMPLETED, Appointment::STATUS_NO_SHOW,
            ])],
            'confirmation_method' => ['nullable', Rule::in(['whatsapp', 'voice', 'manual'])],
            'confirmed_at' => ['nullable', 'date'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'appointment_date' => ['sometimes', 'required', 'date'],
            'appointment_time' => ['sometimes', 'required', 'date_format:H:i'],
        ];
    }
}
