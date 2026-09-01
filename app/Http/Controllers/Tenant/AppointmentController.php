<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('status', 'all');

        if (! in_array($filter, ['all', ...Appointment::FILTERABLE], true)) {
            $filter = 'all';
        }

        $appointments = Appointment::with('service')
            ->status($filter)
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(20)
            ->withQueryString();

        return view('tenant.appointments', compact('appointments', 'filter'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['confirm', 'cancel', 'rebook'])],
        ]);

        match ($data['action']) {
            'confirm' => $appointment->update([
                'status' => Appointment::STATUS_CONFIRMED,
                'confirmed_at' => now(),
                'confirmation_method' => 'manual',
            ]),
            'cancel' => $appointment->update([
                'status' => Appointment::STATUS_DECLINED,
                'confirmed_at' => null,
            ]),
            'rebook' => $appointment->update([
                'status' => Appointment::STATUS_PENDING,
                'confirmed_at' => null,
            ]),
        };

        return back()->with('status', "Appointment for {$appointment->customer_name} updated.");
    }
}
