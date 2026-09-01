<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Restricts every query on a tenant-owned model to the signed-in
 * tenant's rows. Admin (Webefy) users are unrestricted — they work
 * across all tenants. Unauthenticated contexts (the public booking
 * page, the n8n API) are also unrestricted and MUST scope by
 * tenant_id / slug explicitly.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if ($user && $user->role === 'tenant' && $user->tenant_id) {
            $builder->where($model->getTable().'.tenant_id', $user->tenant_id);
        }
    }
}
