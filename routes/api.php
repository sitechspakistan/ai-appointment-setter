<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\CallLogController;
use App\Http\Controllers\Api\V1\ReminderController;
use App\Http\Controllers\Api\V1\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| n8n automation API (v1)
|--------------------------------------------------------------------------
| Machine-to-machine. Auth: Sanctum bearer token issued to the "n8n"
| service account (`php artisan n8n:token`). Send it as:
|     Authorization: Bearer <token>
|     Accept: application/json
| Not tenant-scoped — the caller passes tenant_id / resource ids.
*/

Route::middleware(['auth:sanctum', 'throttle:api'])
    ->prefix('v1')
    ->group(function () {
        // Auth probe — returns the identity behind the token.
        Route::get('/whoami', fn (Request $request) => [
            'authenticated' => true,
            'account' => $request->user()->only('id', 'name', 'email', 'role'),
            'token' => $request->user()->currentAccessToken()?->name,
            'abilities' => $request->user()->currentAccessToken()?->abilities ?? [],
        ])->name('api.whoami');

        // Tenant configuration (read-only)
        Route::get('/tenants', [TenantController::class, 'index'])->name('api.tenants.index');
        Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('api.tenants.show');

        // Appointments
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('api.appointments.index');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('api.appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('api.appointments.show');
        Route::match(['put', 'patch'], '/appointments/{appointment}', [AppointmentController::class, 'update'])->name('api.appointments.update');

        // Reminders
        Route::get('/reminders/due', [ReminderController::class, 'due'])->name('api.reminders.due');
        Route::get('/reminders', [ReminderController::class, 'index'])->name('api.reminders.index');
        Route::post('/reminders', [ReminderController::class, 'store'])->name('api.reminders.store');
        Route::get('/reminders/{reminder}', [ReminderController::class, 'show'])->name('api.reminders.show');
        Route::match(['put', 'patch'], '/reminders/{reminder}', [ReminderController::class, 'update'])->name('api.reminders.update');

        // Vapi call logs
        Route::post('/call-logs', [CallLogController::class, 'store'])->name('api.call-logs.store');
        Route::get('/call-logs/{callLog}', [CallLogController::class, 'show'])->name('api.call-logs.show');
        Route::match(['put', 'patch'], '/call-logs/{callLog}', [CallLogController::class, 'update'])->name('api.call-logs.update');
    });
