<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private const TEXT_KEYS = ['agency_name', 'booking_domain', 'support_inbox'];

    private const URL_KEYS = ['n8n_booking_webhook_url'];

    private const TOGGLE_KEYS = [
        'default_whatsapp_reminders',
        'default_ai_confirmation_calls',
        'default_auto_suspend_past_due',
        'default_weekly_owner_digest',
    ];

    public function edit(): View
    {
        return view('admin.settings', [
            'settings' => Setting::map(),
            'textKeys' => self::TEXT_KEYS,
            'urlKeys' => self::URL_KEYS,
            'toggleKeys' => self::TOGGLE_KEYS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'booking_domain' => ['required', 'string', 'max:255'],
            'support_inbox' => ['required', 'email', 'max:255'],
            'n8n_booking_webhook_url' => ['nullable', 'url', 'max:2048'],
        ]);

        foreach ([...self::TEXT_KEYS, ...self::URL_KEYS] as $key) {
            Setting::put($key, $data[$key] ?? '');
        }

        foreach (self::TOGGLE_KEYS as $key) {
            Setting::put($key, $request->boolean($key) ? '1' : '0');
        }

        return redirect()->route('admin.settings')->with('status', 'Settings saved.');
    }
}
