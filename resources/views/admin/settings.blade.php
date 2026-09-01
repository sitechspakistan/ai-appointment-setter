@extends('layouts.portal')

@section('title', 'Settings · Super Admin')
@section('portal_class', 'wf-portal--dark')

@section('sidebar')
    @include('includes.admin_sidebar', ['active' => 'settings'])
@endsection

@section('topbar')
    @include('includes.admin_header', ['active' => 'settings'])
@endsection

@section('page')
@php
    $toggles = [
        ['label' => 'WhatsApp reminders',       'hint' => '3 hours before, plus 6 PM day-before nudge',   'on' => true],
        ['label' => 'AI confirmation calls',    'hint' => 'Voice fallback when WhatsApp goes unanswered', 'on' => true],
        ['label' => 'Auto-suspend on past due', 'hint' => 'Pause booking page after 14 days unpaid',      'on' => false],
        ['label' => 'Weekly owner digest',      'hint' => "Monday email with last week's numbers",         'on' => true],
    ];
@endphp

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Settings</div>
    <div style="font-size:14.5px;color:var(--wf-dark-mute)">Agency-wide defaults inherited by every new tenant.</div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 h-100">
            <div class="fw-bold mb-3" style="font-size:16px">Agency profile</div>

            <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Agency name</label>
            <input class="form-control mb-3" value="Webefy Today" style="border-radius:11px;height:46px">

            <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Booking domain</label>
            <input class="form-control mb-3 wf-mono" value="ai-appointment.webefytoday.com" style="border-radius:11px;height:46px;font-size:13px">

            <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Support inbox</label>
            <input class="form-control" value="support@webefytoday.com" style="border-radius:11px;height:46px">
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 h-100 d-flex flex-column gap-3">
            <div class="fw-bold" style="font-size:16px">New-tenant defaults</div>

            @foreach ($toggles as $s)
                <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.04)">
                    <div>
                        <div class="fw-semibold" style="font-size:14px">{{ $s['label'] }}</div>
                        <div style="font-size:12px;color:var(--wf-dark-mute)">{{ $s['hint'] }}</div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" style="width:2.6em;height:1.4em" @checked($s['on'])>
                    </div>
                </div>
            @endforeach

            <div class="p-3 rounded-3" style="background:rgba(43,78,200,0.14);border:1px solid rgba(43,78,200,0.38);font-size:12.5px;color:#BFCBF5">
                Changing a default does not alter tenants already onboarded.
            </div>
        </div>
    </div>
</div>
@endsection
