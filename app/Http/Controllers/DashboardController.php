<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Single post-login entry point. Resolves the signed-in user's role
 * and forwards to the matching dashboard. Referenced as route('dashboard').
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route(
            $request->user()->isAdmin() ? 'admin.overview' : 'tenant.overview'
        );
    }
}
