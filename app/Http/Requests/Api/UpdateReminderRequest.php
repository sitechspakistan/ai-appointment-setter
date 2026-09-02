<?php

namespace App\Http\Requests\Api;

use App\Models\Reminder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', Rule::in([Reminder::STATUS_QUEUED, Reminder::STATUS_SENT, Reminder::STATUS_FAILED])],
            'sent_at' => ['nullable', 'date'],
            'outcome' => ['nullable', Rule::in(['confirmed', 'declined', 'no_reply'])],
            'provider_message_id' => ['nullable', 'string', 'max:255'],
            'scheduled_for' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
