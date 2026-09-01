<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
| Static scaffold only — controllers/DB come later. The login screen is
| still served by Breeze's route (resources/views/auth/login.blade.php).
*/

Route::get('/', fn () => redirect()->route('login'));

// Public booking page — anonymous customers. Rate limiting is added when wired.
Route::get('/book/{slug?}', fn (?string $slug = 'sarahshvac') => view('booking', ['slug' => $slug]))
    ->name('booking');

/*
|--------------------------------------------------------------------------
| Super Admin (Webefy)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.overview')->name('overview');
    Route::view('/tenants', 'admin.tenants')->name('tenants');
    Route::view('/billing', 'admin.billing')->name('billing');
    Route::view('/settings', 'admin.settings')->name('settings');
});

/*
|--------------------------------------------------------------------------
| Tenant Admin (one business)
|--------------------------------------------------------------------------
*/
Route::prefix('tenant')->name('tenant.')->group(function () {
    Route::view('/', 'tenant.overview')->name('overview');
    Route::view('/appointments', 'tenant.appointments')->name('appointments');
    Route::view('/reminders', 'tenant.reminders')->name('reminders');
    Route::view('/booking-settings', 'tenant.booking-settings')->name('booking-settings');
    Route::view('/embed', 'tenant.embed')->name('embed');
});

/*
|--------------------------------------------------------------------------
| Breeze account routes (kept for later auth wiring)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => redirect()->route('admin.overview'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
