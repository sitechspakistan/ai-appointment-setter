<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index(): View
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $tenants = Tenant::query()
            ->withCount([
                'appointments as appts_month' => fn ($q) => $q->whereBetween('appointment_date', [$monthStart, $monthEnd]),
                'appointments as confirmed_month' => fn ($q) => $q->where('status', Appointment::STATUS_CONFIRMED)
                    ->whereBetween('appointment_date', [$monthStart, $monthEnd]),
            ])
            ->orderBy('business_name')
            ->get();

        return view('admin.tenants', compact('tenants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'industry' => ['nullable', 'string', 'max:100'],
            'booking_slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('tenants', 'booking_slug')],
        ]);

        $tempPassword = Str::password(14);

        DB::transaction(function () use ($data, $tempPassword): void {
            $tenant = Tenant::create([
                'business_name' => $data['business_name'],
                'booking_slug' => Str::lower($data['booking_slug']),
                'industry' => $data['industry'] ?? null,
                'status' => Tenant::STATUS_TRIAL,
            ]);

            User::create([
                'name' => ($data['owner_name'] ?? null) ?: Str::before($data['owner_email'], '@'),
                'email' => $data['owner_email'],
                'password' => Hash::make($tempPassword),
                'role' => User::ROLE_TENANT,
                'tenant_id' => $tenant->id,
            ]);
        });

        return redirect()->route('admin.tenants')
            ->with('status', "Tenant “{$data['business_name']}” created. Temporary owner password: {$tempPassword}");
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenant-edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in([Tenant::STATUS_ACTIVE, Tenant::STATUS_TRIAL, Tenant::STATUS_PAUSED])],
            'plan' => ['nullable', 'string', 'max:50'],
            'seats' => ['nullable', 'integer', 'min:1'],
            'monthly_amount' => ['nullable', 'numeric', 'min:0'],
            'booking_slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('tenants', 'booking_slug')->ignore($tenant)],
            'vapi_phone_number_id' => ['nullable', 'string', 'max:255'],
            'vapi_assistant_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_phone_number_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_template_name' => ['nullable', 'string', 'max:255'],
            'confirmation_call_script' => ['nullable', 'string'],
        ]);

        $data['booking_slug'] = Str::lower($data['booking_slug']);
        $tenant->update($data);

        return redirect()->route('admin.tenants')->with('status', 'Tenant updated.');
    }

    public function updateStatus(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([Tenant::STATUS_ACTIVE, Tenant::STATUS_TRIAL, Tenant::STATUS_PAUSED])],
        ]);

        $tenant->update(['status' => $data['status']]);

        return back()->with('status', "“{$tenant->business_name}” is now {$data['status']}.");
    }
}
