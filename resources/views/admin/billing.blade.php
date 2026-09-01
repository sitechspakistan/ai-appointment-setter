@extends('layouts.portal')

@section('title', 'Billing · Super Admin')
@section('portal_class', 'wf-portal--dark')

@section('sidebar')
    @include('includes.admin_sidebar', ['active' => 'billing'])
@endsection

@section('topbar')
    @include('includes.admin_header', ['active' => 'billing'])
@endsection

@section('page')
@php($statePill = ['paid' => 'wf-pill--dgreen', 'trial' => 'wf-pill--damber', 'past_due' => 'wf-pill--dred', 'open' => 'wf-pill--dgrey'])

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Billing</div>
    <div style="font-size:14.5px;color:var(--wf-dark-mute)">{{ now()->format('F Y') }} cycle &middot; invoices issue on the 1st.</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label" style="color:#E6BEE0">MRR</div>
            <div class="wf-stat__value">${{ number_format($stats['mrr']) }}</div>
            <div class="wf-stat__delta" style="color:#D9BCF2">active subscriptions</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Collected this cycle</div>
            <div class="wf-stat__value">${{ number_format($stats['collected']) }}</div>
            <div class="wf-stat__delta" style="color:#0FBF8F">{{ $stats['paid_count'] }} of {{ $stats['invoice_count'] }} invoices paid</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Past due</div>
            <div class="wf-stat__value" style="color:#F0736C">${{ number_format($stats['past_due']) }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-dark-mute)">{{ $stats['past_due_count'] }} tenants</div>
        </div>
    </div>
</div>

<div class="wf-card table-responsive" style="border-radius:16px">
    <table class="table align-middle mb-0">
        <thead class="wf-thead">
            <tr>
                <th class="ps-4 py-3">TENANT</th>
                <th class="py-3">PLAN</th>
                <th class="py-3 text-end">SEATS</th>
                <th class="py-3 text-end">AMOUNT</th>
                <th class="py-3">INVOICE</th>
                <th class="pe-4 py-3">STATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $i)
                <tr>
                    <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $i->tenant?->business_name ?? '—' }}</td>
                    <td class="py-3" style="font-size:14px;color:#A6A6C0">{{ $i->plan ?? '—' }}</td>
                    <td class="py-3 text-end" style="font-size:14px">{{ $i->seats }}</td>
                    <td class="py-3 text-end fw-bold" style="font-size:14px">${{ number_format($i->amount, 0) }}</td>
                    <td class="py-3 wf-mono" style="font-size:12.5px;color:#E48FCB">{{ $i->number }}</td>
                    <td class="pe-4 py-3"><span class="wf-pill {{ $statePill[$i->status] ?? 'wf-pill--dgrey' }}">{{ $i->statusLabel() }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4" style="color:var(--wf-dark-mute)">No invoices yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
