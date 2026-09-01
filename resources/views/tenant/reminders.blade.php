@extends('layouts.portal')

@section('title', 'Reminders · '.$currentTenant->business_name)

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'reminders'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'reminders'])
@endsection

@section('page')
@php($outcomePill = ['confirmed' => 'wf-pill--green', 'declined' => 'wf-pill--red', 'no_reply' => 'wf-pill--grey'])

<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Reminders</div>
    <div style="font-size:14.5px;color:#6B6B85">Every nudge the AI setter sent on your behalf.</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Sent this week</div>
            <div class="wf-stat__value" style="font-size:34px">{{ $stats['sent_week'] }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">WhatsApp {{ $stats['sent_whatsapp'] }} &middot; Voice {{ $stats['sent_voice'] }}</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card p-3 h-100">
            <div class="wf-stat__label">Replied / confirmed</div>
            <div class="wf-stat__value" style="font-size:34px;color:#0A9B74">{{ $stats['confirmed_week'] }}</div>
            <div class="wf-stat__delta" style="color:var(--wf-ink-mute)">{{ $stats['response_pct'] }}% response rate</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="wf-card wf-stat--accent p-3 h-100">
            <div class="wf-stat__label">Queued next</div>
            <div class="wf-stat__value" style="font-size:34px;color:#A6127F">{{ $stats['queued'] }}</div>
            <div class="wf-stat__delta" style="color:#7A2BC0">waiting to send</div>
        </div>
    </div>
</div>

<div class="wf-card table-responsive">
    <table class="table align-middle mb-0">
        <thead class="wf-thead">
            <tr>
                <th class="ps-4 py-3">CUSTOMER</th>
                <th class="py-3">CHANNEL</th>
                <th class="py-3">SENT</th>
                <th class="py-3">APPOINTMENT</th>
                <th class="pe-4 py-3">OUTCOME</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reminders as $r)
                <tr>
                    <td class="ps-4 py-3 fw-bold" style="font-size:14px">{{ $r->appointment?->customer_name ?? '—' }}</td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">{{ $r->channelLabel() }}</td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">
                        {{ $r->sent_at?->format('D g:i A') ?? 'Queued '.$r->scheduled_for?->format('D g:i A') }}
                    </td>
                    <td class="py-3" style="font-size:14px;color:#4A4A63">
                        {{ $r->appointment?->dateLabel() }} · {{ $r->appointment?->timeLabel() }}
                    </td>
                    <td class="pe-4 py-3">
                        @if ($r->status === 'queued')
                            <span class="wf-pill wf-pill--amber">Queued</span>
                        @else
                            <span class="wf-pill {{ $outcomePill[$r->outcome] ?? 'wf-pill--grey' }}">{{ $r->outcomeLabel() }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4" style="color:var(--wf-ink-mute)">No reminders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($reminders->hasPages())
        <div class="px-4 py-3" style="border-top:1px solid #EDEDF3">{{ $reminders->links() }}</div>
    @endif
</div>
@endsection
