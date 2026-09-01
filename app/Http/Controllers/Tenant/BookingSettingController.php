<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingSettingController extends Controller
{
    /** Monday-first ordering for the business-hours list. */
    private const DAY_ORDER = [1, 2, 3, 4, 5, 6, 0];

    public function edit(): View
    {
        $tenant = auth()->user()->tenant;

        $this->ensureHoursExist($tenant->id);

        return view('tenant.booking-settings', [
            'tenant' => $tenant,
            'services' => $tenant->services()->ordered()->get(),
            'hours' => $tenant->businessHours()
                ->orderByRaw('FIELD(day_of_week, '.implode(',', self::DAY_ORDER).')')
                ->get(),
            'variables' => ['name', 'service', 'date', 'time', 'business'],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'whatsapp_reminder_message' => ['nullable', 'string', 'max:2000'],
            'hours' => ['array'],
            'hours.*.is_closed' => ['nullable', 'boolean'],
            'hours.*.opens_at' => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at' => ['nullable', 'date_format:H:i'],
        ]);

        $tenant->update([
            'business_name' => $data['business_name'],
            'whatsapp_reminder_message' => $data['whatsapp_reminder_message'] ?? null,
        ]);

        foreach ($data['hours'] ?? [] as $day => $row) {
            $closed = (bool) ($row['is_closed'] ?? false);

            BusinessHour::updateOrCreate(
                ['tenant_id' => $tenant->id, 'day_of_week' => (int) $day],
                [
                    'is_closed' => $closed,
                    'opens_at' => $closed ? null : ($row['opens_at'] ?? null),
                    'closes_at' => $closed ? null : ($row['closes_at'] ?? null),
                ],
            );
        }

        return redirect()->route('tenant.booking-settings')->with('status', 'Booking page settings saved.');
    }

    private function ensureHoursExist(int $tenantId): void
    {
        $existing = BusinessHour::where('tenant_id', $tenantId)->pluck('day_of_week')->all();

        foreach (self::DAY_ORDER as $day) {
            if (! in_array($day, $existing, true)) {
                BusinessHour::create([
                    'tenant_id' => $tenantId,
                    'day_of_week' => $day,
                    'is_closed' => in_array($day, [0, 6], true),
                    'opens_at' => in_array($day, [0, 6], true) ? null : '09:00',
                    'closes_at' => in_array($day, [0, 6], true) ? null : '17:00',
                ]);
            }
        }
    }
}
