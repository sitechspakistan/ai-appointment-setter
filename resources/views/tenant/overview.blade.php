@extends('layouts.portal')

@section('title', 'Overview · Sarah\'s HVAC')

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'overview'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'overview'])
@endsection

@section('modals')
    @include('includes.modal_embed')
@endsection

@section('page')
@php
    $week = [
        ['day' => 'Fri', 'count' => 2], ['day' => 'Sat', 'count' => 1], ['day' => 'Sun', 'count' => 0],
        ['day' => 'Mon', 'count' => 4], ['day' => 'Tue', 'count' => 5], ['day' => 'Wed', 'count' => 3], ['day' => 'Thu', 'count' => 3],
    ];
    $appointments = [
        ['customer' => 'Marcus Reed',    'service' => 'AC Repair',      'date' => 'Tue, Sep 1', 'time' => '9:00 AM',  'status' => 'Confirmed', 'phone' => '(512) 447-0192'],
        ['customer' => 'Dana Whitfield', 'service' => 'Heating Issue',  'date' => 'Tue, Sep 1', 'time' => '11:30 AM', 'status' => 'Confirmed', 'phone' => '(512) 903-7741'],
        ['customer' => 'Ollie Nakamura', 'service' => 'Tune-Up',        'date' => 'Wed, Sep 2', 'time' => '8:00 AM',  'status' => 'Pending',   'phone' => '(737) 220-5518'],
        ['customer' => 'Priya Raman',    'service' => 'AC Repair',      'date' => 'Wed, Sep 2', 'time' => '1:15 PM',  'status' => 'Confirmed', 'phone' => '(512) 664-3390'],
        ['customer' => 'Greg Salazar',   'service' => 'Duct Cleaning',  'date' => 'Thu, Sep 3', 'time' => '10:00 AM', 'status' => 'Declined',  'phone' => '(210) 458-1174'],
        ['customer' => 'Erin Cole',      'service' => 'Tune-Up',        'date' => 'Thu, Sep 3', 'time' => '3:45 PM',  'status' => 'Pending',   'phone' => '(512) 771-0286'],
    ];
    $statusPill = ['Confirmed' => 'wf-pill--green', 'Pending' => 'wf-pill--amber', 'Declined' => 'wf-pill--red'];
@endphp

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Welcome back, Sarah</div>
    <div style="font-size:14.5px;color:#6B6B85">Your AI setter booked 4 jobs while you were out. 2 customers still need to confirm.</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Appointments This Week</div>
            <div class="wf-stat__value">18</div>
            <div class="wf-stat__delta" style="color:#0A9B74">+3 vs. last week</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Confirmed</div>
            <div class="wf-stat__value" style="color:#0A9B74">15</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">83% of this week</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100" style="border-color:#F0DFC4">
            <div class="wf-stat__label">Pending Confirmation</div>
            <div class="wf-stat__value" style="color:#C98014">2</div>
            <div class="wf-stat__delta" style="color:#C98014">Reminder goes out at 6pm</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label">Reminders Sent &middot; No-shows Prevented</div>
            <div class="wf-stat__value" style="color:#A6127F">15</div>
            <div class="wf-stat__delta" style="color:#7A2BC0">WhatsApp + confirmation call</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="wf-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-baseline mb-4">
                <div class="fw-bold" style="font-size:16px">Appointments per day</div>
                <div style="font-size:12px;color:var(--wf-ink-mute)">Last 7 days &middot; 18 total</div>
            </div>
            <div class="d-flex align-items-end gap-3" style="height:190px">
                @foreach ($week as $d)
                    @php($h = $d['count'] === 0 ? 6 : 18 + ($d['count'] / 5) * 110)
                    <div class="d-flex flex-column align-items-center gap-2 flex-fill h-100 justify-content-end">
                        <div class="fw-bold" style="font-size:12px;color:#4A4A63">{{ $d['count'] }}</div>
                        <div style="width:100%;height:{{ $h }}px;border-radius:{{ $d['count'] === 0 ? '4px' : '9px 9px 4px 4px' }};background:{{ $d['count'] === 0 ? '#E6E6EE' : ($d['count'] >= 4 ? 'linear-gradient(180deg,#EC008C,#8B1FD6)' : ($d['count'] >= 3 ? 'linear-gradient(180deg,#B02DC4,#2B4EC8)' : 'linear-gradient(180deg,#F5A0D2,#C87CD8)')) }}"></div>
                        <div class="fw-semibold" style="font-size:11.5px;color:var(--wf-ink-mute)">{{ $d['day'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="p-4 rounded-4 mb-3" style="background:#0B0B18;color:#fff">
            <div class="fw-bold mb-2" style="font-size:13px;color:#C6C6DC">Your Booking Link</div>
            <div class="mb-3 wf-mono" id="tenantBookingLink" style="font-size:12.5px;line-height:1.5;color:#E48FCB;word-break:break-all">ai-appointment.webefytoday.com/book/sarahshvac</div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm flex-fill wf-btn-brand" style="height:40px;font-size:13.5px" data-wf-copy="#tenantBookingLink" data-wf-copy-label="Copied &check;">Copy link</button>
                <a href="{{ route('tenant.embed') }}" class="btn btn-sm flex-fill fw-semibold" style="height:40px;border-radius:10px;font-size:13.5px;color:#D9D9EA;border:1px solid rgba(255,255,255,0.22)">Get Embed Code</a>
            </div>
            <a href="{{ route('booking') }}" class="btn btn-sm w-100 fw-semibold mt-2" style="height:38px;border-radius:10px;font-size:13px;color:#E48FCB;border:1px dashed rgba(255,255,255,0.24)">Open booking page &rarr;</a>
        </div>

        <div class="wf-card p-3">
            <div class="fw-bold mb-2" style="font-size:13px">Next up</div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex flex-column align-items-center justify-content-center fw-bold" style="width:44px;height:44px;border-radius:12px;background:#FBF4FA;color:#C0179A;font-size:11px;line-height:1.1">9:00<span style="font-size:9px">AM</span></div>
                <div>
                    <div class="fw-bold" style="font-size:14px">Marcus Reed — AC Repair</div>
                    <div style="font-size:12px;color:var(--wf-ink-mute)">Tue, Sep 1 &middot; Confirmed by WhatsApp</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wf-card">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid #EDEDF3">
        <div class="fw-bold" style="font-size:16px">Upcoming Appointments</div>
        <a href="{{ route('tenant.appointments') }}" style="font-size:12.5px;color:var(--wf-ink-mute);font-weight:600">View all 18</a>
    </div>
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
            <tbody>
                @foreach ($appointments as $a)
                    <tr @style(['background:#FFFBF3' => $a['status'] === 'Pending'])>
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
