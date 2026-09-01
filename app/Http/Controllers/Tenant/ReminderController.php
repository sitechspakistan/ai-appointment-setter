<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Contracts\View\View;

class ReminderController extends Controller
{
    public function index(): View
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $sentThisWeek = Reminder::sent()->whereBetween('sent_at', [$weekStart, $weekEnd]);

        $sentCount = (clone $sentThisWeek)->count();
        $confirmedCount = (clone $sentThisWeek)->where('outcome', 'confirmed')->count();

        $stats = [
            'sent_week' => $sentCount,
            'sent_whatsapp' => (clone $sentThisWeek)->where('channel', Reminder::CHANNEL_WHATSAPP)->count(),
            'sent_voice' => (clone $sentThisWeek)->where('channel', Reminder::CHANNEL_VOICE)->count(),
            'confirmed_week' => $confirmedCount,
            'response_pct' => $sentCount > 0 ? round($confirmedCount / $sentCount * 100) : 0,
            'queued' => Reminder::queued()->count(),
        ];

        $reminders = Reminder::with('appointment')
            ->orderByRaw('COALESCE(sent_at, scheduled_for) DESC')
            ->paginate(20);

        return view('tenant.reminders', compact('stats', 'reminders'));
    }
}
