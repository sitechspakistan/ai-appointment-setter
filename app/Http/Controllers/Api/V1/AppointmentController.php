<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAppointmentRequest;
use App\Http\Requests\Api\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Reminder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

/**
 * n8n-facing appointment endpoints. The authenticated caller is a
 * cross-tenant service account, so queries are NOT tenant-scoped —
 * n8n must pass tenant_id / appointment id explicitly.
 */
class AppointmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $appointments = Appointment::query()
            ->with(['service', 'tenant'])
            ->when($request->filled('tenant_id'), fn ($q) => $q->where('tenant_id', $request->integer('tenant_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('appointment_date', $request->date('date')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('appointment_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('appointment_date', '<=', $request->date('to')))
            ->when($request->filled('updated_since'), fn ($q) => $q->where('updated_at', '>=', $request->date('updated_since')))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate($request->integer('per_page', 50));

        return AppointmentResource::collection($appointments);
    }

    public function show(Appointment $appointment): AppointmentResource
    {
        return new AppointmentResource(
            $appointment->load(['service', 'tenant', 'reminders', 'callLogs'])
        );
    }

    public function store(StoreAppointmentRequest $request): AppointmentResource
    {
        $data = $request->validated();

        $service = isset($data['service_id'])
            ? Service::withoutGlobalScopes()->find($data['service_id'])
            : null;

        $appointment = Appointment::withoutGlobalScopes()->create([
            'tenant_id' => $data['tenant_id'],
            'service_id' => $service?->id,
            'service_name' => $service?->name ?? $data['service_name'],
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'],
            'notes' => $data['notes'] ?? null,
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'status' => $data['status'] ?? Appointment::STATUS_PENDING,
            'source' => $data['source'] ?? 'manual',
        ]);

        if ($request->boolean('queue_reminder')) {
            $slot = Carbon::parse($data['appointment_date'].' '.$data['appointment_time']);
            Reminder::withoutGlobalScopes()->create([
                'tenant_id' => $appointment->tenant_id,
                'appointment_id' => $appointment->id,
                'channel' => $data['reminder_channel'] ?? Reminder::CHANNEL_WHATSAPP,
                'status' => Reminder::STATUS_QUEUED,
                'scheduled_for' => $slot->copy()->subHours($data['reminder_hours_before'] ?? 3),
            ]);
        }

        return new AppointmentResource($appointment->load(['service', 'tenant']));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): AppointmentResource
    {
        $data = $request->validated();

        if (($data['status'] ?? null) === Appointment::STATUS_CONFIRMED && ! array_key_exists('confirmed_at', $data)) {
            $data['confirmed_at'] = now();
        }
        if (in_array($data['status'] ?? null, [Appointment::STATUS_PENDING, Appointment::STATUS_DECLINED], true)) {
            $data['confirmed_at'] = null;
        }

        $appointment->update($data);

        return new AppointmentResource($appointment->fresh(['service', 'tenant']));
    }
}
