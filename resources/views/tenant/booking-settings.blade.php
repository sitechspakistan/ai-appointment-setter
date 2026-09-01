@extends('layouts.portal')

@section('title', 'Booking Page Settings · Sarah\'s HVAC')

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'settings'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'settings'])
@endsection

@section('page')
@php
    $services  = ['AC Repair', 'Heating Issue', 'Tune-Up', 'Duct Cleaning', 'Emergency Call-Out'];
    $variables = ['name', 'service', 'date', 'time', 'business'];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Booking Page Settings</div>
        <div style="font-size:14.5px;color:#6B6B85">Everything the AI setter uses when it answers a call or a web booking.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('booking') }}" class="btn fw-semibold" style="height:40px;border-radius:10px;border:1px solid #E0E0EA;color:#4A4A63;font-size:13.5px">Preview booking page</a>
        <button class="btn wf-btn-brand" style="height:40px;font-size:13.5px">Save changes</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 h-100">
            <label class="form-label fw-bold" style="font-size:12.5px;color:#4A4A63">Business Name</label>
            <input class="form-control mb-4" value="Sarah's HVAC" style="border-radius:11px;height:46px;border-color:#E0E0EA;background:#FBFBFD">

            <div class="fw-bold mb-2" style="font-size:12.5px;color:#4A4A63">Services Offered</div>
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach ($services as $s)
                    <span class="d-flex align-items-center gap-2 fw-semibold" style="padding:8px 13px;border-radius:20px;font-size:13px;background:#FBF4FA;border:1px solid #F0DDEE;color:#A6127F">
                        {{ $s }} <span style="color:#C88FBD;cursor:pointer">&times;</span>
                    </span>
                @endforeach
                <span class="d-flex align-items-center fw-semibold" style="padding:8px 13px;border-radius:20px;border:1px dashed #CFCFDD;font-size:13px;color:var(--wf-ink-mute);cursor:pointer">+ Add service</span>
            </div>

            <div class="fw-bold mb-2" style="font-size:12.5px;color:#4A4A63">Business Hours</div>
            <div class="rounded-3" style="border:1px solid #EDEDF3">
                <div class="d-flex justify-content-between px-3 py-2" style="font-size:13.5px;border-bottom:1px solid #F2F2F7"><span class="fw-semibold">Mon – Fri</span><span style="color:#4A4A63">7:00 AM – 6:00 PM</span></div>
                <div class="d-flex justify-content-between px-3 py-2" style="font-size:13.5px;border-bottom:1px solid #F2F2F7"><span class="fw-semibold">Saturday</span><span style="color:#4A4A63">8:00 AM – 1:00 PM</span></div>
                <div class="d-flex justify-content-between px-3 py-2" style="font-size:13.5px"><span class="fw-semibold">Sunday</span><span style="color:var(--wf-ink-mute)">Closed &middot; emergency line only</span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:12.5px;color:#4A4A63">Confirmation Call Script</div>
                <span class="wf-pill wf-pill--grey">Read-only &middot; managed by Webefy</span>
            </div>
            <div class="p-3 rounded-3" style="background:#F7F7FB;border:1px solid #EAEAF2;font-size:13.5px;line-height:1.7;color:#4A4A63">
                &ldquo;Hi, this is the scheduling assistant for <strong style="color:#0B0B18">Sarah's HVAC</strong> calling about your <strong style="color:#0B0B18">AC Repair</strong> appointment on <strong style="color:#0B0B18">Tuesday at 9:00 AM</strong>. Press 1 or say &lsquo;confirm&rsquo; to keep it, or 2 to reschedule. If you need anything before then, we're here Monday to Friday, 7 to 6.&rdquo;
            </div>
        </div>

        <div class="wf-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:12.5px;color:#4A4A63">WhatsApp Reminder Message</div>
                <span class="wf-pill wf-pill--green">Editable</span>
            </div>
            <textarea class="form-control mb-3 wf-mono" rows="4" style="border-radius:11px;border-color:#E0E0EA;background:#FBFBFD;font-size:13px;line-height:1.6">Hi @{{name}}, this is Sarah's HVAC confirming your @{{service}} on @{{date}} at @{{time}}. Reply YES to confirm or CHANGE to pick a new time.</textarea>

            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                <span class="fw-semibold" style="font-size:11.5px;color:var(--wf-ink-mute)">Insert variable:</span>
                @foreach ($variables as $v)
                    @php($token = '{' . '{' . $v . '}' . '}')
                    <span class="wf-mono" style="font-size:11.5px;padding:5px 9px;border-radius:7px;border:1px solid #E0E0EA;color:#6B6B85;cursor:pointer">{{ $token }}</span>
                @endforeach
            </div>
            <div style="font-size:11.5px;color:var(--wf-ink-mute)">Sent 3 hours before the appointment. A second nudge goes out at 6:00 PM the day before if unconfirmed.</div>
        </div>
    </div>
</div>
@endsection
