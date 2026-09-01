@extends('layouts.portal')

@section('title', $tenant->business_name.' · Super Admin')
@section('portal_class', 'wf-portal--dark')

@section('sidebar')
    @include('includes.admin_sidebar', ['active' => 'tenants'])
@endsection

@section('topbar')
    @include('includes.admin_header', ['active' => 'tenants'])
@endsection

@section('page')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">{{ $tenant->business_name }}</div>
        <div style="font-size:14.5px;color:var(--wf-dark-mute)">Account, plan and AI provider configuration.</div>
    </div>
    <a href="{{ route('admin.tenants') }}" class="btn fw-semibold" style="height:44px;border-radius:11px;border:1px solid rgba(255,255,255,0.16);color:#C6C6DC">← Back to tenants</a>
</div>

<form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="wf-card p-4 h-100 d-flex flex-column gap-3">
                <div class="fw-bold" style="font-size:16px">Account</div>

                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Business name</label>
                    <input name="business_name" value="{{ old('business_name', $tenant->business_name) }}" class="form-control" style="border-radius:11px;height:46px">
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Industry</label>
                        <input name="industry" value="{{ old('industry', $tenant->industry) }}" class="form-control" style="border-radius:11px;height:46px">
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Status</label>
                        <select name="status" class="form-select" style="border-radius:11px;height:46px">
                            @foreach (['active', 'trial', 'paused'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $tenant->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Booking slug</label>
                    <div class="input-group">
                        <span class="input-group-text wf-affix" style="border-radius:11px 0 0 11px">/book/</span>
                        <input name="booking_slug" value="{{ old('booking_slug', $tenant->booking_slug) }}" class="form-control wf-mono" style="border-radius:0 11px 11px 0;height:46px;color:#E48FCB">
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Plan</label>
                        <input name="plan" value="{{ old('plan', $tenant->plan) }}" class="form-control" style="border-radius:11px;height:46px">
                    </div>
                    <div class="col-4">
                        <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Seats</label>
                        <input name="seats" type="number" min="1" value="{{ old('seats', $tenant->seats) }}" class="form-control" style="border-radius:11px;height:46px">
                    </div>
                    <div class="col-4">
                        <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Monthly $</label>
                        <input name="monthly_amount" type="number" step="0.01" min="0" value="{{ old('monthly_amount', $tenant->monthly_amount) }}" class="form-control" style="border-radius:11px;height:46px">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="wf-card p-4 h-100 d-flex flex-column gap-3">
                <div class="fw-bold" style="font-size:16px">AI providers <span style="font-size:12px;font-weight:500;color:var(--wf-dark-mute)">— leave blank to use Webefy defaults</span></div>

                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Vapi phone number ID</label>
                    <input name="vapi_phone_number_id" value="{{ old('vapi_phone_number_id', $tenant->vapi_phone_number_id) }}" class="form-control wf-mono" style="border-radius:11px;height:46px;font-size:13px">
                </div>
                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Vapi assistant ID</label>
                    <input name="vapi_assistant_id" value="{{ old('vapi_assistant_id', $tenant->vapi_assistant_id) }}" class="form-control wf-mono" style="border-radius:11px;height:46px;font-size:13px">
                </div>
                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">WhatsApp phone number ID</label>
                    <input name="whatsapp_phone_number_id" value="{{ old('whatsapp_phone_number_id', $tenant->whatsapp_phone_number_id) }}" class="form-control wf-mono" style="border-radius:11px;height:46px;font-size:13px">
                </div>
                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">WhatsApp template name</label>
                    <input name="whatsapp_template_name" value="{{ old('whatsapp_template_name', $tenant->whatsapp_template_name) }}" class="form-control wf-mono" style="border-radius:11px;height:46px;font-size:13px">
                </div>
                <div>
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Confirmation call script</label>
                    <textarea name="confirmation_call_script" rows="3" class="form-control" style="border-radius:11px;font-size:13px">{{ old('confirmation_call_script', $tenant->confirmation_call_script) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.tenants') }}" class="btn fw-semibold" style="height:44px;border-radius:11px;border:1px solid rgba(255,255,255,0.16);color:#C6C6DC">Cancel</a>
        <button type="submit" class="btn wf-btn-brand" style="height:44px;border-radius:11px">Save changes</button>
    </div>
</form>
@endsection
