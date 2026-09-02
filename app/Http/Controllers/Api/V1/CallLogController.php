<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCallLogRequest;
use App\Http\Requests\Api\UpdateCallLogRequest;
use App\Http\Resources\CallLogResource;
use App\Models\Appointment;
use App\Models\CallLog;

/**
 * Vapi AI confirmation-call logs. When a call's outcome is "confirmed" or
 * "declined" the linked appointment is updated to match (unless the caller
 * opts out with sync_appointment=false).
 */
class CallLogController extends Controller
{
    private const OUTCOME_TO_STATUS = [
        'confirmed' => Appointment::STATUS_CONFIRMED,
        'declined' => Appointment::STATUS_DECLINED,
    ];

    public function show(CallLog $callLog): CallLogResource
    {
        return new CallLogResource($callLog);
    }

    public function store(StoreCallLogRequest $request): CallLogResource
    {
        $data = $request->validated();
        $appointment = Appointment::withoutGlobalScopes()->findOrFail($data['appointment_id']);

        $callLog = CallLog::withoutGlobalScopes()->create([
            'tenant_id' => $appointment->tenant_id,
            'appointment_id' => $appointment->id,
            'vapi_call_id' => $data['vapi_call_id'] ?? null,
            'status' => $data['status'] ?? 'queued',
            'outcome' => $data['outcome'] ?? null,
            'recording_url' => $data['recording_url'] ?? null,
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'ended_at' => $data['ended_at'] ?? null,
        ]);

        $this->syncAppointment($callLog, $appointment, $request->boolean('sync_appointment', true));

        return new CallLogResource($callLog);
    }

    public function update(UpdateCallLogRequest $request, CallLog $callLog): CallLogResource
    {
        $callLog->update($request->validated());

        $this->syncAppointment(
            $callLog,
            Appointment::withoutGlobalScopes()->find($callLog->appointment_id),
            $request->boolean('sync_appointment', true),
        );

        return new CallLogResource($callLog->fresh());
    }

    private function syncAppointment(CallLog $callLog, ?Appointment $appointment, bool $enabled): void
    {
        if (! $enabled || ! $appointment) {
            return;
        }

        $status = self::OUTCOME_TO_STATUS[$callLog->outcome] ?? null;

        if ($status && $appointment->status !== $status) {
            $appointment->update([
                'status' => $status,
                'confirmed_at' => $status === Appointment::STATUS_CONFIRMED ? now() : null,
                'confirmation_method' => $status === Appointment::STATUS_CONFIRMED ? 'voice' : $appointment->confirmation_method,
            ]);
        }
    }
}
