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
@php
    $invoices = [
        ['tenant' => "Sarah's HVAC",       'plan' => 'Growth',  'seats' => 3, 'amount' => '$420', 'number' => 'INV-2609-014', 'state' => 'Paid'],
        ['tenant' => 'Bright Smile Dental', 'plan' => 'Growth',  'seats' => 5, 'amount' => '$560', 'number' => 'INV-2609-015', 'state' => 'Paid'],
        ['tenant' => 'Luxe Hair Studio',   'plan' => 'Scale',   'seats' => 8, 'amount' => '$890', 'number' => 'INV-2609-016', 'state' => 'Paid'],
        ['tenant' => 'Peak Roofing Co.',   'plan' => 'Starter', 'seats' => 1, 'amount' => '$185', 'number' => 'INV-2609-017', 'state' => 'Past due'],
        ['tenant' => 'Vista Med Spa',      'plan' => 'Trial',   'seats' => 2, 'amount' => '$0',   'number' => '—',            'state' => 'Trial'],
    ];
    $statePill = ['Paid' => 'wf-pill--dgreen', 'Trial' => 'wf-pill--damber', 'Past due' => 'wf-pill--dred'];
@endphp

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Billing</div>
    <div style="font-size:14.5px;color:var(--wf-dark-mute)">September 2026 cycle &middot; invoices issue on the 1st.</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label" style="color:#E6BEE0">MRR</div>
            <div class="wf-stat__value">$4,860</div>
            <div class="wf-stat__delta" style="color:#D9BCF2">+$740 vs. August</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Collected this cycle</div>
            <div class="wf-stat__value">$4,215</div>
            <div class="wf-stat__delta" style="color:#0FBF8F">9 of 12 invoices paid</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Past due</div>
            <div class="wf-stat__value" style="color:#F0736C">$645</div>
            <div class="wf-stat__delta" style="color:var(--wf-dark-mute)">2 tenants &middot; auto-retry Sep 4</div>
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
            @foreach ($invoices as $i)
                <tr>
                    <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $i['tenant'] }}</td>
                    <td class="py-3" style="font-size:14px;color:#A6A6C0">{{ $i['plan'] }}</td>
                    <td class="py-3 text-end" style="font-size:14px">{{ $i['seats'] }}</td>
                    <td class="py-3 text-end fw-bold" style="font-size:14px">{{ $i['amount'] }}</td>
                    <td class="py-3 wf-mono" style="font-size:12.5px;color:#E48FCB">{{ $i['number'] }}</td>
                    <td class="pe-4 py-3"><span class="wf-pill {{ $statePill[$i['state']] }}">{{ $i['state'] }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
