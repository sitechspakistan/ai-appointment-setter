<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCallLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vapi_call_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['queued', 'ringing', 'in_progress', 'completed', 'failed', 'no_answer'])],
            'outcome' => ['sometimes', 'nullable', Rule::in(['confirmed', 'reschedule', 'declined', 'no_response'])],
            'recording_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'duration_seconds' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'started_at' => ['sometimes', 'nullable', 'date'],
            'ended_at' => ['sometimes', 'nullable', 'date'],
            'sync_appointment' => ['nullable', 'boolean'],
        ];
    }
}
