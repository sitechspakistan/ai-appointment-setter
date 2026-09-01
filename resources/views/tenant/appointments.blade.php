@extends('layouts.portal')

@section('title', 'Appointments · Sarah\'s HVAC')

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'appointments'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'appointments'])
@endsection

@section('page')
@php
    $appointments = [
        ['customer' => 'Marcus Reed',    'service' => 'AC Repair',          'date' => 'Tue, Sep 1', 'time' => '9:00 AM',  'status' => 'Confirmed', 'phone' => '(512) 447-0192'],
        ['customer' => 'Dana Whitfield', 'service' => 'Heating Issue',      'date' => 'Tue, Sep 1', 'time' => '11:30 AM', 'status' => 'Confirmed', 'phone' => '(512) 903-7741'],
        ['customer' => 'Ollie Nakamura', 'service' => 'Tune-Up',            'date' => 'Wed, Sep 2', 'time' => '8:00 AM',  'status' => 'Pending',   'phone' => '(737) 220-5518'],
        ['customer' => 'Priya Raman',    'service' => 'AC Repair',          'date' => 'Wed, Sep 2', 'time' => '1:15 PM',  'status' => 'Confirmed', 'phone' => '(512) 664-3390'],
        ['customer' => 'Greg Salazar',   'service' => 'Duct Cleaning',      'date' => 'Thu, Sep 3', 'time' => '10:00 AM', 'status' => 'Declined',  'phone' => '(210) 458-1174'],
        ['customer' => 'Erin Cole',      'service' => 'Tune-Up',            'date' => 'Thu, Sep 3', 'time' => '3:45 PM',  'status' => 'Pending',   'phone' => '(512) 771-0286'],
        ['customer' => 'Tom Beaudry',    'service' => 'Emergency Call-Out', 'date' => 'Fri, Sep 4', 'time' => '7:30 AM',  'status' => 'Confirmed', 'phone' => '(512) 338-9027'],
        ['customer' => 'Nina Alvarez',   'service' => 'Heating Issue',      'date' => 'Fri, Sep 4', 'time' => '2:00 PM',  'status' => 'Pending',   'phone' => '(737) 601-4488'],
    ];
    $statusPill = ['Confirmed' => 'wf-pill--green', 'Pending' => 'wf-pill--amber', 'Declined' => 'wf-pill--red'];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Appointments</div>
        <div style="font-size:14.5px;color:#6B6B85">Everything the AI setter booked, newest first.</div>
    </div>
    <div class="d-flex gap-2" data-wf-row-filter="#apptRows">
        @foreach (['All', 'Confirmed', 'Pending', 'Declined'] as $i => $label)
            <button type="button" class="btn btn-sm fw-semibold wf-filter {{ $i === 0 ? 'is-active' : '' }}" data-filter="{{ $label }}"
                    style="border-radius:9px;font-size:12.5px">{{ $label }}</button>
        @endforeach
    </div>
</div>

<div class="wf-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="wf-thead">
                <tr>
                    <th class="ps-4 py-3">CUSTOMER</th>
                    <th class="py-3">SERVICE</th>
                    <th class="py-3">DATE</th>
                    <th class="py-3">TIME</th>
                    <th class="py-3">STATUS</th>
                    <th class="py-3">PHONE</th>
                    <th class="pe-4 py-3 text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="apptRows">
                @foreach ($appointments as $a)
                    <tr data-status="{{ $a['status'] }}" @style(['background:#FFFBF3' => $a['status'] === 'Pending'])>
                        <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $a['customer'] }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a['service'] }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a['date'] }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a['time'] }}</td>
                        <td class="py-3"><span class="wf-pill {{ $statusPill[$a['status']] }}">{{ $a['status'] }}</span></td>
                        <td class="py-3 wf-mono" style="font-size:12.5px;color:#6B6B85">{{ $a['phone'] }}</td>
                        <td class="pe-4 py-3">
                            <div class="d-flex justify-content-end gap-2">
                                @if ($a['status'] === 'Pending')
                                    <button class="btn btn-sm fw-semibold text-white" style="border-radius:8px;font-size:12px;padding:6px 11px;background:linear-gradient(100deg,#EC008C,#A21CAF)">Mark confirmed</button>
                                @endif
                                <button class="btn btn-sm fw-semibold" style="border-radius:8px;font-size:12px;padding:6px 11px;border:1px solid #E0E0EA;color:var(--wf-ink-mute)">{{ $a['status'] === 'Declined' ? 'Rebook' : 'Cancel' }}</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
