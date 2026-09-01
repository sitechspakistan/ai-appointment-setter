@extends('layouts.portal')

@section('title', 'Reminders · Sarah\'s HVAC')

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'reminders'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'reminders'])
@endsection

@section('page')
@php
    $reminders = [
        ['customer' => 'Marcus Reed',    'channel' => 'WhatsApp',   'sent' => 'Mon 6:00 PM', 'appt' => 'Tue 9:00 AM',  'outcome' => 'Confirmed'],
        ['customer' => 'Dana Whitfield', 'channel' => 'Voice call', 'sent' => 'Mon 6:04 PM', 'appt' => 'Tue 11:30 AM', 'outcome' => 'Confirmed'],
        ['customer' => 'Ollie Nakamura', 'channel' => 'WhatsApp',   'sent' => 'Tue 6:00 PM', 'appt' => 'Wed 8:00 AM',  'outcome' => 'No reply'],
        ['customer' => 'Priya Raman',    'channel' => 'WhatsApp',   'sent' => 'Tue 6:00 PM', 'appt' => 'Wed 1:15 PM',  'outcome' => 'Confirmed'],
        ['customer' => 'Greg Salazar',   'channel' => 'Voice call', 'sent' => 'Wed 6:02 PM', 'appt' => 'Thu 10:00 AM', 'outcome' => 'Declined'],
        ['customer' => 'Erin Cole',      'channel' => 'WhatsApp',   'sent' => 'Wed 6:00 PM', 'appt' => 'Thu 3:45 PM',  'outcome' => 'No reply'],
    ];
    $outcomePill = ['Confirmed' => 'wf-pill--green', 'Declined' => 'wf-pill--red', 'No reply' => 'wf-pill--grey'];
@endphp

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Reminders</div>
    <div style="font-size:14.5px;color:#6B6B85">Every nudge the AI setter sent on your behalf.</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Sent this week</div>
            <div class="wf-stat__value" style="font-size:34px">15</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">WhatsApp 11 &middot; Voice 4</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Replied / confirmed</div>
            <div class="wf-stat__value" style="font-size:34px;color:#0A9B74">12</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">80% response rate</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label">Queued next</div>
            <div class="wf-stat__value" style="font-size:34px;color:#A6127F">3</div>
            <div class="wf-stat__delta" style="color:#7A2BC0">Goes out today at 6:00 PM</div>
        </div>
    </div>
</div>

<div class="wf-card table-responsive">
    <table class="table align-middle mb-0">
        <thead class="wf-thead">
            <tr>
                <th class="ps-4 py-3">CUSTOMER</th>
                <th class="py-3">CHANNEL</th>
                <th class="py-3">SENT</th>
                <th class="py-3">APPOINTMENT</th>
                <th class="pe-4 py-3">OUTCOME</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reminders as $r)
                <tr>
                    <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $r['customer'] }}</td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $r['channel'] }}</td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $r['sent'] }}</td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $r['appt'] }}</td>
                    <td class="pe-4 py-3"><span class="wf-pill {{ $outcomePill[$r['outcome']] }}">{{ $r['outcome'] }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
