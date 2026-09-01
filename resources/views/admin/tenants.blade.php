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
@php($statusPill = ['active' => 'wf-pill--dgreen', 'trial' => 'wf-pill--damber', 'paused' => 'wf-pill--dgrey'])

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Tenants</div>
        <div style="font-size:14.5px;color:var(--wf-dark-mute)">Every business account, its plan and onboarding state.</div>
    </div>
    <button class="btn wf-btn-brand" style="height:46px;font-size:15px" data-bs-toggle="modal" data-bs-target="#wfNewTenant">+ New Tenant</button>
</div>

<div class="row g-3">
    @forelse ($tenants as $t)
        @php($rate = $t->appts_month > 0 ? round($t->confirmed_month / $t->appts_month * 100) : 0)
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="wf-card p-3 h-100 d-flex flex-column gap-3">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="wf-avatar" style="width:38px;height:38px;border-radius:11px;font-size:13px">{{ $t->initials() }}</span>
                        <div>
                            <div class="fw-bold" style="font-size:15px">{{ $t->business_name }}</div>
                            <div style="font-size:12px;color:var(--wf-dark-mute)">{{ $t->industry ?? '—' }} &middot; {{ $t->plan ?? 'No plan' }}</div>
                        </div>
                    </div>
                    <span class="wf-pill {{ $statusPill[$t->status] ?? 'wf-pill--dgrey' }}">{{ ucfirst($t->status) }}</span>
                </div>

                <div class="d-flex gap-3">
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Appts / mo</div><div class="fw-bold" style="font-size:18px">{{ $t->appts_month }}</div></div>
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Confirm</div><div class="fw-bold" style="font-size:18px">{{ $rate }}%</div></div>
                    <div><div style="font-size:11px;color:var(--wf-dark-mute)">Onboarded</div><div class="fw-bold" style="font-size:18px">{{ $t->created_at?->format('M y') ?? '—' }}</div></div>
                </div>

                <div class="wf-mono" style="font-size:12px;color:#E48FCB">/{{ $t->bookingPath() }}</div>

                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('admin.tenants.edit', $t) }}" class="btn btn-sm flex-fill fw-semibold text-white" style="border-radius:9px;font-size:12.5px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.16)">Edit</a>
                    <button class="btn btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#wfEmbed"
                            data-embed-business="{{ $t->business_name }}" data-embed-url="{{ $t->bookingUrl() }}"
                            style="border-radius:9px;font-size:12.5px;color:#D9BCF2;border:1px solid rgba(255,255,255,0.16)">Embed</button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="wf-card p-4 text-center" style="color:var(--wf-dark-mute)">No tenants yet — create the first one.</div></div>
    @endforelse
</div>
@endsection
