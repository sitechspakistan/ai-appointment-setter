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
@php($statusPill = ['active' => 'wf-pill--dgreen', 'trial' => 'wf-pill--damber', 'paused' => 'wf-pill--dgrey'])

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">All Tenants</div>
        <div style="font-size:14.5px;color:var(--wf-dark-mute)">{{ $stats['total_tenants'] }} businesses live on the Appointment Setter</div>
    </div>
    <button class="btn wf-btn-brand" style="height:46px;font-size:15px" data-bs-toggle="modal" data-bs-target="#wfNewTenant">+ New Tenant</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Total Tenants</div>
            <div class="wf-stat__value">{{ $stats['total_tenants'] }}</div>
            <div class="wf-stat__delta" style="color:#0FBF8F">+{{ $stats['new_tenants_this_month'] }} this month</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Appointments This Month</div>
            <div class="wf-stat__value">{{ $stats['appts_this_month'] }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-dark-mute)">across all tenants</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Overall Confirmation Rate</div>
            <div class="wf-stat__value">{{ $stats['confirmation_rate'] }}%</div>
            <div class="wf-meter"><div class="wf-meter__fill" style="width:{{ $stats['confirmation_rate'] }}%"></div></div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label" style="color:#E6BEE0">Reminders Sent Today</div>
            <div class="wf-stat__value">{{ $stats['reminders_today'] }}</div>
            <div class="wf-stat__delta" style="color:#D9BCF2">across {{ $stats['reminder_tenants_today'] }} tenants &middot; WhatsApp + voice</div>
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
                @forelse ($tenants as $t)
                    @php($rate = $t->appts_month > 0 ? round($t->confirmed_month / $t->appts_month * 100) : 0)
                    <tr @style(['opacity:0.62' => $t->status === 'paused'])>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="wf-avatar" style="width:26px;height:26px;border-radius:7px;font-size:11px">{{ $t->initials() }}</span>
                                <span class="fw-bold" style="font-size:14px">{{ $t->business_name }}</span>
                            </div>
                        </td>
                        <td class="py-3" style="font-size:14px;color:#A6A6C0">{{ $t->industry ?? '—' }}</td>
                        <td class="py-3 wf-mono" style="font-size:12.5px;color:#E48FCB">/{{ $t->bookingPath() }}</td>
                        <td class="py-3 text-end fw-bold" style="font-size:14px">{{ $t->appts_month }}</td>
                        <td class="py-3 text-end fw-bold" style="font-size:14px;color:{{ $rate >= 88 ? '#0FBF8F' : ($rate >= 80 ? '#E8A32C' : '#F0736C') }}">{{ $rate }}%</td>
                        <td class="py-3"><span class="wf-pill {{ $statusPill[$t->status] ?? 'wf-pill--dgrey' }}">{{ ucfirst($t->status) }}</span></td>
                        <td class="pe-4 py-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm wf-mono" title="Get Embed Code"
                                        data-bs-toggle="modal" data-bs-target="#wfEmbed"
                                        data-embed-business="{{ $t->business_name }}" data-embed-url="{{ $t->bookingUrl() }}"
                                        style="width:28px;height:28px;padding:0;border-radius:8px;border:1px solid rgba(255,255,255,0.16);color:#D9BCF2;font-size:11px;line-height:1">&lt;/&gt;</button>
                                <div class="dropdown">
                                    <button class="btn btn-sm" data-bs-toggle="dropdown" aria-expanded="false" style="width:28px;height:28px;padding:0;border-radius:8px;border:1px solid rgba(255,255,255,0.16);color:#D9BCF2;line-height:1">&ctdot;</button>
                                    <ul class="dropdown-menu dropdown-menu-end wf-menu-dark">
                                        <li><a class="dropdown-item" href="{{ route('admin.tenants.edit', $t) }}">Edit</a></li>
                                        <li>
                                            <button type="button" class="dropdown-item"
                                                    data-bs-toggle="modal" data-bs-target="#wfEmbed"
                                                    data-embed-business="{{ $t->business_name }}" data-embed-url="{{ $t->bookingUrl() }}">Get Embed Code</button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.tenants.status', $t) }}"
                                                  data-wf-confirm="{{ $t->status === 'paused' ? 'Reactivate' : 'Suspend' }} {{ $t->business_name }}?">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $t->status === 'paused' ? 'active' : 'paused' }}">
                                                <button type="submit" class="dropdown-item fw-semibold" style="color:{{ $t->status === 'paused' ? '#3FD9AE' : '#F0736C' }}">
                                                    {{ $t->status === 'paused' ? 'Reactivate' : 'Suspend' }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4" style="color:var(--wf-dark-mute)">No tenants yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($tenants->hasPages())
        <div class="px-4 py-3" style="border-top:1px solid rgba(255,255,255,0.07)">
            {{ $tenants->links() }}
        </div>
    @endif
</div>
@endsection
