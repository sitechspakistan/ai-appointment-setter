<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Setting;
use App\Observers\AppointmentObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Appointment::observe(AppointmentObserver::class);

        // Public booking page / endpoint — generous for humans, closes the door on abuse.
        RateLimiter::for('booking', fn (Request $request) => [
            Limit::perMinute(30)->by($request->ip()),
        ]);

        // n8n API — keyed per token so one busy tenant can't starve the rest.
        RateLimiter::for('api', fn (Request $request) => [
            Limit::perMinute(300)->by(
                $request->user()?->currentAccessToken()?->id
                    ? 'tok:'.$request->user()->currentAccessToken()->id
                    : 'ip:'.$request->ip()
            ),
        ]);

        // Feed the portal (screens + chrome partials) with shared data.
        View::composer(['layouts.portal', 'admin.*', 'tenant.*', 'includes.admin_sidebar', 'includes.admin_header', 'includes.tenant_sidebar', 'includes.tenant_header'], function ($view): void {
            $user = auth()->user();

            $view->with([
                'authUser' => $user,
                'currentTenant' => $user?->isTenant() ? $user->tenant : null,
                'agency' => Setting::map(),
            ]);
        });
    }
}
