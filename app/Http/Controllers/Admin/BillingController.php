<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Contracts\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $monthStart = now()->startOfMonth();

        $stats = [
            'mrr' => (float) Tenant::where('status', Tenant::STATUS_ACTIVE)->sum('monthly_amount'),
            'collected' => (float) Invoice::where('status', Invoice::STATUS_PAID)
                ->where('paid_at', '>=', $monthStart)->sum('amount'),
            'past_due' => (float) Invoice::where('status', Invoice::STATUS_PAST_DUE)->sum('amount'),
            'past_due_count' => Invoice::where('status', Invoice::STATUS_PAST_DUE)->count(),
            'paid_count' => Invoice::where('status', Invoice::STATUS_PAID)->where('issued_on', '>=', $monthStart)->count(),
            'invoice_count' => Invoice::where('issued_on', '>=', $monthStart)->count(),
        ];

        $invoices = Invoice::with('tenant')
            ->orderByDesc('issued_on')
            ->orderByDesc('id')
            ->get();

        return view('admin.billing', compact('stats', 'invoices'));
    }
}
