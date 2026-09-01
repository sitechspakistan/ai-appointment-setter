<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:120',
                Rule::unique('services', 'name')->where('tenant_id', $tenantId),
            ],
            'icon' => ['nullable', 'string', 'max:16'],
        ]);

        Service::create([
            'name' => $data['name'],
            'icon' => $data['icon'] ?? null,
            'sort_order' => (int) Service::max('sort_order') + 1,
            'is_active' => true,
        ]);

        return back()->with('status', "Added “{$data['name']}”.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('status', "Removed “{$service->name}”.");
    }
}
