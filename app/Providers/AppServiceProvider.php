<?php

namespace App\Providers;

use App\Models\Setting;
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

        // Public booking page / endpoint — generous for humans, closes the door on abuse.
        RateLimiter::for('booking', fn (Request $request) => [
            Limit::perMinute(30)->by($request->ip()),
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
