@extends('layouts.portal')

@section('title', 'All Tenants · Super Admin')
@section('portal_class', 'wf-portal--dark')

@section('sidebar')
    @include('includes.admin_sidebar', ['active' => 'overview'])
@endsection

@section('topbar')
    @include('includes.admin_header', ['active' => 'overview'])
@endsection

@section('modals')
    @include('includes.modal_new_tenant')
    @include('includes.modal_embed')
@endsection

@section('page')
@php
    $tenants = [
        ['name' => "Sarah's HVAC",          'initials' => 'SH', 'industry' => 'HVAC',          'slug' => '/book/sarahshvac',  'appts' => 42, 'rate' => '91%', 'status' => 'Active', 'g' => ['#EC008C', '#A21CAF']],
        ['name' => 'Bright Smile Dental',    'initials' => 'BS', 'industry' => 'Dental',        'slug' => '/book/brightsmile', 'appts' => 68, 'rate' => '89%', 'status' => 'Active', 'g' => ['#A21CAF', '#2B4EC8']],
        ['name' => 'Blue Wave Pool Care',    'initials' => 'BW', 'industry' => 'Pool Cleaning', 'slug' => '/book/bluewave',    'appts' => 31, 'rate' => '84%', 'status' => 'Trial',  'g' => ['#2B4EC8', '#5FC7F0']],
        ['name' => 'Luxe Hair Studio',       'initials' => 'LH', 'industry' => 'Salon',         'slug' => '/book/luxehair',    'appts' => 55, 'rate' => '93%', 'status' => 'Active', 'g' => ['#EC008C', '#7A2BC0']],
        ['name' => 'Peak Roofing Co.',       'initials' => 'PR', 'industry' => 'Roofing',       'slug' => '/book/peakroofing', 'appts' => 19, 'rate' => '72%', 'status' => 'Paused', 'g' => ['#5B5B78', '#8A8AA0']],
        ['name' => 'Comfort Air Solutions',  'initials' => 'CA', 'industry' => 'HVAC',          'slug' => '/book/comfortair',  'appts' => 37, 'rate' => '88%', 'status' => 'Active', 'g' => ['#2B4EC8', '#A21CAF']],
        ['name' => 'Vista Med Spa',          'initials' => 'VM', 'industry' => 'Med Spa',       'slug' => '/book/vistamedspa', 'appts' => 48, 'rate' => '90%', 'status' => 'Trial',  'g' => ['#EC008C', '#E8A32C']],
    ];
    $statusPill = ['Active' => 'wf-pill--dgreen', 'Trial' => 'wf-pill--damber', 'Paused' => 'wf-pill--dgrey'];
@endphp

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">All Tenants</div>
        <div style="font-size:14.5px;color:var(--wf-dark-mute)">12 businesses live on the Appointment Setter &middot; updated 4 minutes ago</div>
    </div>
    <button class="btn wf-btn-brand" style="height:46px;font-size:15px" data-bs-toggle="modal" data-bs-target="#wfNewTenant">+ New Tenant</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Total Tenants</div>
            <div class="wf-stat__value">12</div>
            <div class="wf-stat__delta" style="color:#0FBF8F">+2 this month</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Appointments This Month</div>
            <div class="wf-stat__value">340</div>
            <div class="wf-stat__delta" style="color:#0FBF8F">+18% vs. July</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Overall Confirmation Rate</div>
            <div class="wf-stat__value">87%</div>
            <div class="wf-meter"><div class="wf-meter__fill" style="width:87%"></div></div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label" style="color:#E6BEE0">Reminders Sent Today</div>
            <div class="wf-stat__value">24</div>
            <div class="wf-stat__delta" style="color:#D9BCF2">across 7 tenants &middot; WhatsApp + voice</div>
        </div>
    </div>
</div>

<div class="wf-card" style="border-radius:16px">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="wf-thead">
                <tr>
                    <th class="ps-4 py-3">BUSINESS NAME</th>
                    <th class="py-3">INDUSTRY</th>
                    <th class="py-3">BOOKING LINK</th>
                    <th class="py-3 text-end">APPTS / MO</th>
                    <th class="py-3 text-end">CONFIRM %</th>
                    <th class="py-3">STATUS</th>
                    <th class="pe-4 py-3 text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $i => $t)
                    <tr @style(['background:rgba(236,0,140,0.05)' => $i === 0, 'opacity:0.62' => $t['status'] === 'Paused'])>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="wf-avatar" style="width:26px;height:26px;border-radius:7px;font-size:11px;background:linear-gradient(135deg,{{ $t['g'][0] }},{{ $t['g'][1] }})">{{ $t['initials'] }}</span>
                                <span class="fw-bold" style="font-size:14px">{{ $t['name'] }}</span>
                            </div>
                        </td>
                        <td class="py-3" style="font-size:14px;color:#A6A6C0">{{ $t['industry'] }}</td>
                        <td class="py-3 wf-mono" style="font-size:12.5px;color:#E48FCB">{{ $t['slug'] }}</td>
                        <td class="py-3 text-end fw-bold" style="font-size:14px">{{ $t['appts'] }}</td>
                        <td class="py-3 text-end fw-bold" style="font-size:14px;color:{{ (int) $t['rate'] >= 88 ? '#0FBF8F' : ((int) $t['rate'] >= 80 ? '#E8A32C' : '#F0736C') }}">{{ $t['rate'] }}</td>
                        <td class="py-3"><span class="wf-pill {{ $statusPill[$t['status']] }}">{{ $t['status'] }}</span></td>
                        <td class="pe-4 py-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm wf-mono" title="Get Embed Code" data-bs-toggle="modal" data-bs-target="#wfEmbed" style="width:28px;height:28px;padding:0;border-radius:8px;border:1px solid rgba(255,255,255,0.16);color:#D9BCF2;font-size:11px;line-height:1">&lt;/&gt;</button>
                                <div class="dropdown">
                                    <button class="btn btn-sm" data-bs-toggle="dropdown" aria-expanded="false" style="width:28px;height:28px;padding:0;border-radius:8px;border:1px solid rgba(255,255,255,0.16);color:#D9BCF2;line-height:1">&ctdot;</button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="background:#14142A;border:1px solid rgba(255,255,255,0.14)">
                                        <li><a class="dropdown-item text-white" href="{{ route('tenant.overview') }}">View Dashboard</a></li>
                                        <li><a class="dropdown-item text-white-50" href="{{ route('tenant.overview') }}">Impersonate</a></li>
                                        <li><a class="dropdown-item text-white-50" href="#">Edit</a></li>
                                        <li><button type="button" class="dropdown-item text-white-50" data-bs-toggle="modal" data-bs-target="#wfEmbed">Get Embed Code</button></li>
                                        <li><hr class="dropdown-divider" style="border-color:rgba(255,255,255,0.1)"></li>
                                        <li><a class="dropdown-item fw-semibold" href="#" style="color:#F0736C">Suspend</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid rgba(255,255,255,0.07);font-size:12.5px;color:var(--wf-dark-mute)">
        <div>Showing 7 of 12 tenants</div>
        <div class="d-flex gap-2 align-items-center">
            <span class="px-2 py-1 rounded-2" style="border:1px solid rgba(255,255,255,0.14)">Prev</span>
            <span class="px-2 py-1 rounded-2 fw-bold text-white" style="background:rgba(255,255,255,0.12)">1</span>
            <span class="px-2 py-1 rounded-2" style="border:1px solid rgba(255,255,255,0.14)">2</span>
            <span class="px-2 py-1 rounded-2" style="border:1px solid rgba(255,255,255,0.14)">Next</span>
        </div>
    </div>
</div>
@endsection
