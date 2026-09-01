@extends('layouts.portal')

@section('title', 'Overview · '.$tenant->business_name)

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
@php($statusPill = ['confirmed' => 'wf-pill--green', 'pending' => 'wf-pill--amber', 'declined' => 'wf-pill--red', 'completed' => 'wf-pill--grey', 'no_show' => 'wf-pill--red'])
@php($firstName = \Illuminate\Support\Str::before($authUser->name, ' '))

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Welcome back, {{ $firstName }}</div>
    <div style="font-size:14.5px;color:#6B6B85">
        {{ $stats['appts_week'] }} appointments booked this week &middot; {{ $stats['pending_week'] }} still need to confirm.
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Appointments This Week</div>
            <div class="wf-stat__value">{{ $stats['appts_week'] }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">Mon–Sun</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Confirmed</div>
            <div class="wf-stat__value" style="color:#0A9B74">{{ $stats['confirmed_week'] }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">{{ $stats['confirmed_pct'] }}% of this week</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card p-3 h-100" style="border-color:#F0DFC4">
            <div class="wf-stat__label">Pending Confirmation</div>
            <div class="wf-stat__value" style="color:#C98014">{{ $stats['pending_week'] }}</div>
            <div class="wf-stat__delta" style="color:#C98014">reminder goes out the day before</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label">Reminders Sent This Week</div>
            <div class="wf-stat__value" style="color:#A6127F">{{ $stats['reminders_week'] }}</div>
            <div class="wf-stat__delta" style="color:#7A2BC0">WhatsApp + confirmation call</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="wf-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-baseline mb-4">
                <div class="fw-bold" style="font-size:16px">Appointments per day</div>
                <div style="font-size:12px;color:var(--wf-ink-mute)">Last 7 days &middot; {{ collect($week)->sum('count') }} total</div>
            </div>
            <div class="d-flex align-items-end gap-3" style="height:190px">
                @foreach ($week as $d)
                    @php($h = $d['count'] === 0 ? 6 : 18 + ($d['count'] / $weekMax) * 130)
                    <div class="d-flex flex-column align-items-center gap-2 flex-fill h-100 justify-content-end">
                        <div class="fw-bold" style="font-size:12px;color:#4A4A63">{{ $d['count'] }}</div>
                        <div style="width:100%;height:{{ $h }}px;border-radius:{{ $d['count'] === 0 ? '4px' : '9px 9px 4px 4px' }};background:{{ $d['count'] === 0 ? '#E6E6EE' : 'linear-gradient(180deg,#EC008C,#3F2BD4)' }}"></div>
                        <div class="fw-semibold" style="font-size:11.5px;color:var(--wf-ink-mute)">{{ $d['day'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="p-4 rounded-4 mb-3" style="background:#0B0B18;color:#fff">
            <div class="fw-bold mb-2" style="font-size:13px;color:#C6C6DC">Your Booking Link</div>
            <div class="mb-3 wf-mono" id="tenantBookingLink" style="font-size:12.5px;line-height:1.5;color:#E48FCB;word-break:break-all">{{ $tenant->bookingUrl() }}</div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm flex-fill wf-btn-brand" style="height:40px;font-size:13.5px" data-wf-copy="#tenantBookingLink" data-wf-copy-label="Copied &check;">Copy link</button>
                <button class="btn btn-sm flex-fill fw-semibold" data-bs-toggle="modal" data-bs-target="#wfEmbed"
                        data-embed-business="{{ $tenant->business_name }}" data-embed-url="{{ $tenant->bookingUrl() }}"
                        style="height:40px;border-radius:10px;font-size:13.5px;color:#D9D9EA;border:1px solid rgba(255,255,255,0.22)">Get Embed Code</button>
            </div>
            <a href="{{ route('booking', $tenant->booking_slug) }}" target="_blank" class="btn btn-sm w-100 fw-semibold mt-2" style="height:38px;border-radius:10px;font-size:13px;color:#E48FCB;border:1px dashed rgba(255,255,255,0.24)">Open booking page &rarr;</a>
        </div>

        <div class="wf-card p-3">
            <div class="fw-bold mb-2" style="font-size:13px">Next up</div>
            @if ($nextUp)
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex flex-column align-items-center justify-content-center fw-bold" style="width:44px;height:44px;border-radius:12px;background:#FBF4FA;color:#C0179A;font-size:11px;line-height:1.1">{{ \Illuminate\Support\Carbon::parse($nextUp->appointment_time)->format('g:i') }}<span style="font-size:9px">{{ \Illuminate\Support\Carbon::parse($nextUp->appointment_time)->format('A') }}</span></div>
                    <div>
                        <div class="fw-bold" style="font-size:14px">{{ $nextUp->customer_name }} — {{ $nextUp->service_name }}</div>
                        <div style="font-size:12px;color:var(--wf-ink-mute)">{{ $nextUp->dateLabel() }} &middot; {{ ucfirst($nextUp->status) }}</div>
                    </div>
                </div>
            @else
                <div style="font-size:13px;color:var(--wf-ink-mute)">No upcoming appointments.</div>
            @endif
        </div>
    </div>
</div>

<div class="wf-card">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid #EDEDF3">
        <div class="fw-bold" style="font-size:16px">Upcoming Appointments</div>
        <a href="{{ route('tenant.appointments') }}" style="font-size:12.5px;color:var(--wf-ink-mute);font-weight:600">View all</a>
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
                @forelse ($upcoming as $a)
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
                    <tr><td colspan="7" class="text-center py-4" style="color:var(--wf-ink-mute)">No upcoming appointments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
