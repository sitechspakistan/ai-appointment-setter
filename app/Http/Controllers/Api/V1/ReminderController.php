<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReminderRequest;
use App\Http\Requests\Api\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Models\Appointment;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReminderController extends Controller
{
    /**
     * Reminders that are queued and due to send now — the endpoint n8n polls.
     * GET /api/v1/reminders/due?tenant_id=&channel=&limit=
     */
    public function due(Request $request): AnonymousResourceCollection
    {
        $reminders = Reminder::query()
            ->with(['appointment.tenant', 'appointment.service'])
            ->queued()
            ->where(function ($q) {
                $q->whereNull('scheduled_for')->orWhere('scheduled_for', '<=', now());
            })
            ->when($request->filled('tenant_id'), fn ($q) => $q->where('tenant_id', $request->integer('tenant_id')))
            ->when($request->filled('channel'), fn ($q) => $q->where('channel', $request->string('channel')))
            ->orderBy('scheduled_for')
            ->limit($request->integer('limit', 100))
            ->get();

        return ReminderResource::collection($reminders);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $reminders = Reminder::query()
            ->with(['appointment'])
            ->when($request->filled('tenant_id'), fn ($q) => $q->where('tenant_id', $request->integer('tenant_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('appointment_id'), fn ($q) => $q->where('appointment_id', $request->integer('appointment_id')))
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 50));

        return ReminderResource::collection($reminders);
    }

    public function show(Reminder $reminder): ReminderResource
    {
        return new ReminderResource($reminder->load('appointment.tenant'));
    }

    public function store(StoreReminderRequest $request): ReminderResource
    {
        $data = $request->validated();
        $appointment = Appointment::withoutGlobalScopes()->findOrFail($data['appointment_id']);

        $reminder = Reminder::withoutGlobalScopes()->create([
            'tenant_id' => $appointment->tenant_id,
            'appointment_id' => $appointment->id,
            'channel' => $data['channel'],
            'status' => $data['status'] ?? Reminder::STATUS_QUEUED,
            'scheduled_for' => $data['scheduled_for'] ?? now(),
        ]);

        return new ReminderResource($reminder);
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder): ReminderResource
    {
        $data = $request->validated();

        if (($data['status'] ?? null) === Reminder::STATUS_SENT && ! array_key_exists('sent_at', $data)) {
            $data['sent_at'] = now();
        }

        $reminder->update($data);

        return new ReminderResource($reminder->fresh('appointment'));
    }
}
