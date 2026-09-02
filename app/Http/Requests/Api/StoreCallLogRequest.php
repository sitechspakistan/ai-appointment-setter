<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCallLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'integer', Rule::exists('appointments', 'id')],
            'vapi_call_id' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['queued', 'ringing', 'in_progress', 'completed', 'failed', 'no_answer'])],
            'outcome' => ['nullable', Rule::in(['confirmed', 'reschedule', 'declined', 'no_response'])],
            'recording_url' => ['nullable', 'url', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
            'sync_appointment' => ['nullable', 'boolean'],
        ];
    }
}
