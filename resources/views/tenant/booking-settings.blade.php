@extends('layouts.portal')

@section('title', 'Booking Page Settings · '.$tenant->business_name)

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'settings'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'settings'])
@endsection

@section('page')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Booking Page Settings</div>
        <div style="font-size:14.5px;color:#6B6B85">Everything the AI setter uses when it answers a call or a web booking.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('booking', $tenant->booking_slug) }}" target="_blank" class="btn fw-semibold" style="height:40px;border-radius:10px;border:1px solid #E0E0EA;color:#4A4A63;font-size:13.5px">Preview booking page</a>
        <button type="submit" form="bookingSettingsForm" class="btn wf-btn-brand" style="height:40px;font-size:13.5px">Save changes</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 h-100">
            <form method="POST" action="{{ route('tenant.booking-settings.update') }}" id="bookingSettingsForm">
                @csrf @method('PUT')

                <label class="form-label fw-bold" style="font-size:12.5px;color:#4A4A63">Business Name</label>
                <input name="business_name" value="{{ old('business_name', $tenant->business_name) }}" class="form-control mb-4" style="border-radius:11px;height:46px;border-color:#E0E0EA;background:#FBFBFD">

                <div class="fw-bold mb-2" style="font-size:12.5px;color:#4A4A63">Business Hours</div>
                <div class="rounded-3 mb-2" style="border:1px solid #EDEDF3">
                    @foreach ($hours as $h)
                        <div class="d-flex align-items-center justify-content-between gap-2 px-3 py-2" style="font-size:13px;{{ ! $loop->last ? 'border-bottom:1px solid #F2F2F7' : '' }}">
                            <span class="fw-semibold" style="width:64px">{{ \Illuminate\Support\Str::substr($h->dayName(), 0, 3) }}</span>
                            <input type="time" name="hours[{{ $h->day_of_week }}][opens_at]" value="{{ $h->opens_at ? \Illuminate\Support\Str::substr($h->opens_at, 0, 5) : '' }}" class="form-control form-control-sm" style="max-width:120px">
                            <span style="color:#8A8AA0">–</span>
                            <input type="time" name="hours[{{ $h->day_of_week }}][closes_at]" value="{{ $h->closes_at ? \Illuminate\Support\Str::substr($h->closes_at, 0, 5) : '' }}" class="form-control form-control-sm" style="max-width:120px">
                            <label class="d-flex align-items-center gap-1 m-0" style="font-size:12px;color:#8A8AA0">
                                <input type="hidden" name="hours[{{ $h->day_of_week }}][is_closed]" value="0">
                                <input type="checkbox" name="hours[{{ $h->day_of_week }}][is_closed]" value="1" @checked($h->is_closed)> Closed
                            </label>
                        </div>
                    @endforeach
                </div>
            </form>

            <div class="fw-bold mb-2 mt-4" style="font-size:12.5px;color:#4A4A63">Services Offered</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($services as $s)
                    <span class="d-flex align-items-center gap-2 fw-semibold" style="padding:8px 13px;border-radius:20px;font-size:13px;background:#FBF4FA;border:1px solid #F0DDEE;color:#A6127F">
                        @if($s->icon) {{ $s->icon }} @endif {{ $s->name }}
                        <form method="POST" action="{{ route('tenant.services.destroy', $s) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm p-0 border-0" style="color:#C88FBD;line-height:1">&times;</button>
                        </form>
                    </span>
                @endforeach
            </div>
            <form method="POST" action="{{ route('tenant.services.store') }}" class="d-flex gap-2 mt-2">
                @csrf
                <input name="icon" maxlength="4" class="form-control form-control-sm" placeholder="🛠️" style="max-width:64px;border-radius:20px;text-align:center">
                <input name="name" required class="form-control form-control-sm" placeholder="New service name" style="max-width:220px;border-radius:20px">
                <button type="submit" class="btn btn-sm fw-semibold" style="border-radius:20px;border:1px dashed #CFCFDD;color:#8A8AA0">+ Add service</button>
            </form>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="wf-card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:12.5px;color:#4A4A63">Confirmation Call Script</div>
                <span class="wf-pill wf-pill--grey">Read-only &middot; managed by Webefy</span>
            </div>
            <div class="p-3 rounded-3" style="background:#F7F7FB;border:1px solid #EAEAF2;font-size:13.5px;line-height:1.7;color:#4A4A63">
                {{ $tenant->confirmation_call_script ?: '“Hi, this is the scheduling assistant for '.$tenant->business_name.' calling about your appointment. Press 1 or say ‘confirm’ to keep it, or 2 to reschedule.”' }}
            </div>
        </div>

        <div class="wf-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:12.5px;color:#4A4A63">WhatsApp Reminder Message</div>
                <span class="wf-pill wf-pill--green">Editable</span>
            </div>
            <textarea form="bookingSettingsForm" name="whatsapp_reminder_message" rows="4" class="form-control mb-3 wf-mono" style="border-radius:11px;border-color:#E0E0EA;background:#FBFBFD;font-size:13px;line-height:1.6">{{ old('whatsapp_reminder_message', $tenant->whatsapp_reminder_message) }}</textarea>

            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                <span class="fw-semibold" style="font-size:11.5px;color:var(--wf-ink-mute)">Available variables:</span>
                @foreach ($variables as $v)
                    @php($token = '{' . '{' . $v . '}' . '}')
                    <span class="wf-mono" style="font-size:11.5px;padding:5px 9px;border-radius:7px;border:1px solid #E0E0EA;color:#6B6B85">{{ $token }}</span>
                @endforeach
            </div>
            <div style="font-size:11.5px;color:var(--wf-ink-mute)">Sent 3 hours before the appointment. A second nudge goes out the day before if unconfirmed.</div>
        </div>
    </div>
</div>
@endsection
