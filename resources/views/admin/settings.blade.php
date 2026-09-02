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
    $toggleMeta = [
        'default_whatsapp_reminders' => ['WhatsApp reminders', '3 hours before, plus 6 PM day-before nudge'],
        'default_ai_confirmation_calls' => ['AI confirmation calls', 'Voice fallback when WhatsApp goes unanswered'],
        'default_auto_suspend_past_due' => ['Auto-suspend on past due', 'Pause booking page after 14 days unpaid'],
        'default_weekly_owner_digest' => ['Weekly owner digest', "Monday email with last week's numbers"],
    ];
@endphp

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Settings</div>
            <div style="font-size:14.5px;color:var(--wf-dark-mute)">Agency-wide defaults inherited by every new tenant.</div>
        </div>
        <button type="submit" class="btn wf-btn-brand" style="height:44px;border-radius:11px">Save changes</button>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="wf-card p-4 h-100">
                <div class="fw-bold mb-3" style="font-size:16px">Agency profile</div>

                <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Agency name</label>
                <input name="agency_name" value="{{ old('agency_name', $settings['agency_name'] ?? '') }}" class="form-control mb-3" style="border-radius:11px;height:46px">

                <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Booking domain</label>
                <input name="booking_domain" value="{{ old('booking_domain', $settings['booking_domain'] ?? '') }}" class="form-control mb-3 wf-mono" style="border-radius:11px;height:46px;font-size:13px">

                <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Support inbox</label>
                <input name="support_inbox" type="email" value="{{ old('support_inbox', $settings['support_inbox'] ?? '') }}" class="form-control" style="border-radius:11px;height:46px">
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="wf-card p-4 h-100 d-flex flex-column gap-3">
                <div class="fw-bold" style="font-size:16px">New-tenant defaults</div>

                @foreach ($toggleKeys as $key)
                    <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.04)">
                        <div>
                            <div class="fw-semibold" style="font-size:14px">{{ $toggleMeta[$key][0] }}</div>
                            <div style="font-size:12px;color:var(--wf-dark-mute)">{{ $toggleMeta[$key][1] }}</div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="{{ $key }}" value="1"
                                   style="width:2.6em;height:1.4em"
                                   @checked(old($key, filter_var($settings[$key] ?? false, FILTER_VALIDATE_BOOLEAN)))>
                        </div>
                    </div>
                @endforeach

                <div class="p-3 rounded-3" style="background:rgba(43,78,200,0.14);border:1px solid rgba(43,78,200,0.38);font-size:12.5px;color:#BFCBF5">
                    Changing a default does not alter tenants already onboarded.
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="wf-card p-4">
                <div class="fw-bold mb-1" style="font-size:16px">Automation (n8n)</div>
                <div style="font-size:12.5px;color:var(--wf-dark-mute);margin-bottom:14px">
                    When a customer books, this app POSTs the appointment to your n8n workflow so it can place the confirmation call.
                    The request is signed with <span class="wf-mono">X-Webefy-Signature</span> (secret in <span class="wf-mono">.env</span>).
                </div>
                <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">n8n booking webhook URL</label>
                <input name="n8n_booking_webhook_url" type="url" placeholder="https://n8n.sitechs.co/webhook/webefy-booking"
                       value="{{ old('n8n_booking_webhook_url', $settings['n8n_booking_webhook_url'] ?? '') }}"
                       class="form-control wf-mono" style="border-radius:11px;height:46px;font-size:13px">
                <div style="font-size:11.5px;color:var(--wf-dark-mute);margin-top:6px">Leave blank to disable the push (workflows can still poll the API).</div>
            </div>
        </div>
    </div>
</form>
@endsection
