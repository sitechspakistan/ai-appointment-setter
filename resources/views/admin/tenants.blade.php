@extends('layouts.portal')

@section('title', 'Tenants · Super Admin')
@section('portal_class', 'wf-portal--dark')

@section('sidebar')
    @include('includes.admin_sidebar', ['active' => 'tenants'])
@endsection

@section('topbar')
    @include('includes.admin_header', ['active' => 'tenants'])
@endsection

@section('modals')
    @include('includes.modal_new_tenant')
    @include('includes.modal_embed')
@endsection

@section('page')
@php
    $tenants = [
        ['name' => "Sarah's HVAC",         'initials' => 'SH', 'industry' => 'HVAC',          'slug' => '/book/sarahshvac',  'appts' => 42, 'rate' => '91%', 'status' => 'Active', 'plan' => 'Growth',  'since' => 'Mar 26', 'g' => ['#EC008C', '#A21CAF']],
        ['name' => 'Bright Smile Dental',   'initials' => 'BS', 'industry' => 'Dental',        'slug' => '/book/brightsmile', 'appts' => 68, 'rate' => '89%', 'status' => 'Active', 'plan' => 'Growth',  'since' => 'Jan 26', 'g' => ['#A21CAF', '#2B4EC8']],
        ['name' => 'Blue Wave Pool Care',   'initials' => 'BW', 'industry' => 'Pool Cleaning', 'slug' => '/book/bluewave',    'appts' => 31, 'rate' => '84%', 'status' => 'Trial',  'plan' => 'Trial',   'since' => 'Aug 26', 'g' => ['#2B4EC8', '#5FC7F0']],
        ['name' => 'Luxe Hair Studio',      'initials' => 'LH', 'industry' => 'Salon',         'slug' => '/book/luxehair',    'appts' => 55, 'rate' => '93%', 'status' => 'Active', 'plan' => 'Scale',   'since' => 'Nov 25', 'g' => ['#EC008C', '#7A2BC0']],
        ['name' => 'Peak Roofing Co.',      'initials' => 'PR', 'industry' => 'Roofing',       'slug' => '/book/peakroofing', 'appts' => 19, 'rate' => '72%', 'status' => 'Paused', 'plan' => 'Starter', 'since' => 'Feb 26', 'g' => ['#5B5B78', '#8A8AA0']],
        ['name' => 'Comfort Air Solutions', 'initials' => 'CA', 'industry' => 'HVAC',          'slug' => '/book/comfortair',  'appts' => 37, 'rate' => '88%', 'status' => 'Active', 'plan' => 'Growth',  'since' => 'Apr 26', 'g' => ['#2B4EC8', '#A21CAF']],
        ['name' => 'Vista Med Spa',         'initials' => 'VM', 'industry' => 'Med Spa',       'slug' => '/book/vistamedspa', 'appts' => 48, 'rate' => '90%', 'status' => 'Trial',  'plan' => 'Trial',   'since' => 'Aug 26', 'g' => ['#EC008C', '#E8A32C']],
    ];
    $statusPill = ['Active' => 'wf-pill--dgreen', 'Trial' => 'wf-pill--damber', 'Paused' => 'wf-pill--dgrey'];
@endphp

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Tenants</div>
        <div style="font-size:14.5px;color:var(--wf-dark-mute)">Every business account, its plan and onboarding state.</div>
    </div>
    <button class="btn wf-btn-brand" style="height:46px;font-size:15px" data-bs-toggle="modal" data-bs-target="#wfNewTenant">+ New Tenant</button>
</div>

<div class="row g-3">
    @foreach ($tenants as $t)
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="wf-card p-3 h-100 d-flex flex-column gap-3">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="wf-avatar" style="width:38px;height:38px;border-radius:11px;font-size:13px;background:linear-gradient(135deg,{{ $t['g'][0] }},{{ $t['g'][1] }})">{{ $t['initials'] }}</span>
                        <div>
                            <div class="fw-bold" style="font-size:15px">{{ $t['name'] }}</div>
                            <div style="font-size:12px;color:var(--wf-dark-mute)">{{ $t['industry'] }} &middot; {{ $t['plan'] }}</div>
                        </div>
                    </div>
                    <span class="wf-pill {{ $statusPill[$t['status']] }}">{{ $t['status'] }}</span>
                </div>

                <div class="d-flex gap-3">
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Appts / mo</div><div class="fw-bold" style="font-size:18px">{{ $t['appts'] }}</div></div>
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Confirm</div><div class="fw-bold" style="font-size:18px">{{ $t['rate'] }}</div></div>
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Onboarded</div><div class="fw-bold" style="font-size:18px">{{ $t['since'] }}</div></div>
                </div>

                <div class="wf-mono" style="font-size:12px;color:#E48FCB">{{ $t['slug'] }}</div>

                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('tenant.overview') }}" class="btn btn-sm flex-fill fw-semibold text-white" style="border-radius:9px;font-size:12.5px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.16)">View dashboard</a>
                    <button class="btn btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#wfEmbed" style="border-radius:9px;font-size:12.5px;color:#D9BCF2;border:1px solid rgba(255,255,255,0.16)">Embed</button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
