@extends('layouts.portal')

@section('title', 'Appointments · '.$currentTenant->business_name)

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'appointments'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'appointments'])
@endsection

@section('page')
@php($statusPill = ['confirmed' => 'wf-pill--green', 'pending' => 'wf-pill--amber', 'declined' => 'wf-pill--red', 'completed' => 'wf-pill--grey', 'no_show' => 'wf-pill--red'])

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Appointments</div>
        <div style="font-size:14.5px;color:#6B6B85">Everything the AI setter booked, newest first.</div>
    </div>
    <div class="d-flex gap-2">
        @foreach (['all' => 'All', 'confirmed' => 'Confirmed', 'pending' => 'Pending', 'declined' => 'Declined'] as $value => $label)
            <a href="{{ route('tenant.appointments', $value === 'all' ? [] : ['status' => $value]) }}"
               class="btn btn-sm fw-semibold wf-filter {{ $filter === $value ? 'is-active' : '' }}"
               style="border-radius:9px;font-size:12.5px">{{ $label }}</a>
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
            <tbody>
                @forelse ($appointments as $a)
                    <tr @style(['background:#FFFBF3' => $a->status === 'pending'])>
                        <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $a->customer_name }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a->service_name }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a->dateLabel() }}</td>
                        <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $a->timeLabel() }}</td>
                        <td class="py-3"><span class="wf-pill {{ $statusPill[$a->status] ?? 'wf-pill--grey' }}">{{ $a->statusLabel() }}</span></td>
                        <td class="py-3 wf-mono" style="font-size:12.5px;color:#6B6B85">{{ $a->customer_phone }}</td>
                        <td class="pe-4 py-3">
                            <form method="POST" action="{{ route('tenant.appointments.update', $a) }}" class="d-flex justify-content-end gap-2">
                                @csrf @method('PATCH')
                                @if ($a->status === 'pending')
                                    <button name="action" value="confirm" class="btn btn-sm fw-semibold text-white" style="border-radius:8px;font-size:12px;padding:6px 11px;background:linear-gradient(100deg,#EC008C,#A21CAF)">Mark confirmed</button>
                                @endif
                                <button name="action" value="{{ $a->status === 'declined' ? 'rebook' : 'cancel' }}" class="btn btn-sm fw-semibold" style="border-radius:8px;font-size:12px;padding:6px 11px;border:1px solid #E0E0EA;color:var(--wf-ink-mute)">{{ $a->status === 'declined' ? 'Rebook' : 'Cancel' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4" style="color:var(--wf-ink-mute)">No appointments match this filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($appointments->hasPages())
        <div class="px-4 py-3" style="border-top:1px solid #EDEDF3">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
