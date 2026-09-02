<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TenantController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $tenants = Tenant::query()
            ->when($request->filled('slug'), fn ($q) => $q->where('booking_slug', $request->string('slug')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('business_name')
            ->paginate($request->integer('per_page', 50));

        return TenantResource::collection($tenants);
    }

    public function show(Tenant $tenant): TenantResource
    {
        return new TenantResource($tenant);
    }
}
