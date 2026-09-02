<?php

namespace App\Http\Requests\Api;

use App\Models\Reminder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'integer', Rule::exists('appointments', 'id')],
            'channel' => ['required', Rule::in([Reminder::CHANNEL_WHATSAPP, Reminder::CHANNEL_VOICE])],
            'scheduled_for' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in([Reminder::STATUS_QUEUED, Reminder::STATUS_SENT, Reminder::STATUS_FAILED])],
        ];
    }
}
