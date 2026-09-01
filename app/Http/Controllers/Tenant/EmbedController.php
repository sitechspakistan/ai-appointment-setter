<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class EmbedController extends Controller
{
    public function show(): View
    {
        return view('tenant.embed', [
            'tenant' => auth()->user()->tenant,
        ]);
    }
}
