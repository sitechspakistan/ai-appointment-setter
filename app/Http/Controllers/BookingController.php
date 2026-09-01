<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * Public, unauthenticated booking page (path-based, e.g. /book/sarahshvac).
 * Rate limited at the route. Everything here is scoped explicitly by the
 * resolved tenant — the global TenantScope does not apply to guests.
 */
class BookingController extends Controller
{
    public function show(Tenant $tenant): View
    {
        abort_if($tenant->isPaused(), 404);

        return view('booking', [
            'tenant' => $tenant,
            'services' => $tenant->services()->active()->ordered()->get(),
            'slots' => $this->slots(),
            'slotDate' => now()->addDay(),
        ]);
    }

    public function store(Request $request, Tenant $tenant): RedirectResponse
    {
        abort_if($tenant->isPaused(), 404);

        $validated = $request->validate([
            'service_id' => ['nullable', Rule::exists('services', 'id')->where('tenant_id', $tenant->id)],
            'service_name' => ['required_without:service_id', 'nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
        ]);

        $service = isset($validated['service_id'])
            ? Service::where('tenant_id', $tenant->id)->find($validated['service_id'])
            : null;

        $appointment = Appointment::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'service_id' => $service?->id,
            'service_name' => $service?->name ?? $validated['service_name'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'],
            'notes' => $validated['notes'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => Appointment::STATUS_PENDING,
            'source' => $request->boolean('embed') ? 'embed' : 'web',
        ]);

        // Queue the day-before WhatsApp reminder (n8n picks queued rows up).
        $slot = Carbon::parse($validated['appointment_date'].' '.$validated['appointment_time']);
        Reminder::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'appointment_id' => $appointment->id,
            'channel' => Reminder::CHANNEL_WHATSAPP,
            'status' => Reminder::STATUS_QUEUED,
            'scheduled_for' => $slot->copy()->subHours(3),
        ]);

        return redirect()
            ->route('booking', $tenant->booking_slug)
            ->with('booked', [
                'service' => $appointment->service_name,
                'when' => $slot->format('D, M j').' · '.$slot->format('g:i A'),
                'name' => $appointment->customer_name,
            ]);
    }

    /** Static demo slot grid until real availability is wired. */
    private function slots(): array
    {
        return [
            ['label' => '9:00 AM', 'value' => '09:00', 'taken' => true],
            ['label' => '10:30 AM', 'value' => '10:30', 'taken' => false],
            ['label' => '12:00 PM', 'value' => '12:00', 'taken' => true],
            ['label' => '2:00 PM', 'value' => '14:00', 'taken' => false],
            ['label' => '3:30 PM', 'value' => '15:30', 'taken' => false],
            ['label' => '5:00 PM', 'value' => '17:00', 'taken' => false],
        ];
    }
}
