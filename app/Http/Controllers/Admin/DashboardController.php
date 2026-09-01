<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reminder;
use App\Models\Tenant;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $apptsThisMonth = Appointment::whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])->count();
        $confirmedThisMonth = Appointment::whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->where('status', Appointment::STATUS_CONFIRMED)->count();

        $stats = [
            'total_tenants' => Tenant::count(),
            'new_tenants_this_month' => Tenant::where('created_at', '>=', $monthStart)->count(),
            'appts_this_month' => $apptsThisMonth,
            'confirmation_rate' => $apptsThisMonth > 0 ? round($confirmedThisMonth / $apptsThisMonth * 100) : 0,
            'reminders_today' => Reminder::sent()->whereDate('sent_at', today())->count(),
            'reminder_tenants_today' => Reminder::sent()->whereDate('sent_at', today())->distinct('tenant_id')->count('tenant_id'),
        ];

        $tenants = Tenant::query()
            ->withCount([
                'appointments as appts_month' => fn ($q) => $q->whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()]),
                'appointments as confirmed_month' => fn ($q) => $q->where('status', Appointment::STATUS_CONFIRMED)
                    ->whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()]),
            ])
            ->orderBy('business_name')
            ->paginate(7);

        return view('admin.overview', compact('stats', 'tenants'));
    }
}
