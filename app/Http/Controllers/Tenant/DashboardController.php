<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reminder;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenant = auth()->user()->tenant;

        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $range = [$weekStart->toDateString(), $weekEnd->toDateString()];

        $apptsThisWeek = Appointment::whereBetween('appointment_date', $range)->count();
        $confirmedThisWeek = Appointment::whereBetween('appointment_date', $range)
            ->where('status', Appointment::STATUS_CONFIRMED)->count();

        $stats = [
            'appts_week' => $apptsThisWeek,
            'confirmed_week' => $confirmedThisWeek,
            'confirmed_pct' => $apptsThisWeek > 0 ? round($confirmedThisWeek / $apptsThisWeek * 100) : 0,
            'pending_week' => Appointment::whereBetween('appointment_date', $range)
                ->where('status', Appointment::STATUS_PENDING)->count(),
            'reminders_week' => Reminder::sent()->whereBetween('sent_at', [$weekStart, $weekEnd])->count(),
        ];

        // Appointments per day for the last 7 calendar days (chart).
        $counts = Appointment::selectRaw('appointment_date, COUNT(*) as total')
            ->whereBetween('appointment_date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->groupBy('appointment_date')
            ->pluck('total', 'appointment_date');

        $week = collect(range(6, 0))->map(function ($daysAgo) use ($counts) {
            $date = now()->subDays($daysAgo);

            return [
                'day' => $date->format('D'),
                'count' => (int) ($counts[$date->toDateString()] ?? 0),
            ];
        })->all();

        $upcoming = Appointment::with('service')->upcoming()->limit(6)->get();

        return view('tenant.overview', [
            'tenant' => $tenant,
            'stats' => $stats,
            'week' => $week,
            'weekMax' => max(1, collect($week)->max('count')),
            'upcoming' => $upcoming,
            'nextUp' => $upcoming->first(),
        ]);
    }
}
