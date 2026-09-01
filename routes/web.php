<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

// Public booking page — anonymous customers, rate limited.
Route::middleware('throttle:booking')->group(function () {
    Route::get('/book/{tenant:booking_slug}', [BookingController::class, 'show'])->name('booking');
    Route::post('/book/{tenant:booking_slug}', [BookingController::class, 'store'])->name('booking.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated portal
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Role-aware landing — sends the user to the right dashboard.
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---------- Super Admin (Webefy) ---------- */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('overview');

        Route::get('/tenants', [Admin\TenantController::class, 'index'])->name('tenants');
        Route::post('/tenants', [Admin\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}/edit', [Admin\TenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [Admin\TenantController::class, 'update'])->name('tenants.update');
        Route::patch('/tenants/{tenant}/status', [Admin\TenantController::class, 'updateStatus'])->name('tenants.status');

        Route::get('/billing', [Admin\BillingController::class, 'index'])->name('billing');

        Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });

    /* ---------- Tenant Admin (one business) ---------- */
    Route::middleware('role:tenant')->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/', [Tenant\DashboardController::class, 'index'])->name('overview');

        Route::get('/appointments', [Tenant\AppointmentController::class, 'index'])->name('appointments');
        Route::patch('/appointments/{appointment}', [Tenant\AppointmentController::class, 'update'])->name('appointments.update');

        Route::get('/reminders', [Tenant\ReminderController::class, 'index'])->name('reminders');

        Route::get('/booking-settings', [Tenant\BookingSettingController::class, 'edit'])->name('booking-settings');
        Route::put('/booking-settings', [Tenant\BookingSettingController::class, 'update'])->name('booking-settings.update');

        Route::post('/services', [Tenant\ServiceController::class, 'store'])->name('services.store');
        Route::delete('/services/{service}', [Tenant\ServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('/embed', [Tenant\EmbedController::class, 'show'])->name('embed');
    });
});

require __DIR__.'/auth.php';
