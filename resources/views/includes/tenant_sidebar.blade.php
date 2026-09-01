{{-- Tenant Admin sidebar. Pass ['active' => 'overview'|'appointments'|'reminders'|'settings'|'embed']. --}}
@php($active = $active ?? 'overview')
@php($t = $currentTenant)

<aside class="wf-sidebar" style="background:#fff;border-right:1px solid var(--wf-border)">
    <div class="mb-4 mx-2">
        <img src="{{ asset('assets/img/webefy-logo.png') }}" alt="Webefy Today" style="height:26px;width:auto">
    </div>

    <div class="d-flex align-items-center gap-2 p-2 rounded-4 mb-4" style="background:#FBF4FA;border:1px solid #F0DDEE">
        <span class="wf-avatar" style="width:34px;height:34px;border-radius:10px;font-size:13px">{{ $t?->initials() ?? 'T' }}</span>
        <div>
            <div class="fw-bold" style="font-size:13.5px">{{ $t?->business_name ?? 'Your business' }}</div>
            @if($t?->location)
                <div style="font-size:11px;color:var(--wf-ink-mute)">{{ $t->location }}</div>
            @endif
        </div>
    </div>

    <a href="{{ route('tenant.overview') }}" class="wf-sidebar__link {{ $active === 'overview' ? 'is-active' : '' }}">
        <span>Overview</span>
    </a>
    <a href="{{ route('tenant.appointments') }}" class="wf-sidebar__link {{ $active === 'appointments' ? 'is-active' : '' }}">
        <span>Appointments</span>
    </a>
    <a href="{{ route('tenant.reminders') }}" class="wf-sidebar__link {{ $active === 'reminders' ? 'is-active' : '' }}">
        <span>Reminders</span>
    </a>
    <a href="{{ route('tenant.booking-settings') }}" class="wf-sidebar__link {{ $active === 'settings' ? 'is-active' : '' }}">
        <span>Booking Page Settings</span>
    </a>
    <a href="{{ route('tenant.embed') }}" class="wf-sidebar__link {{ $active === 'embed' ? 'is-active' : '' }}">
        <span>Embed Code</span>
    </a>

    <div class="mt-auto">
        <div class="p-3 rounded-4 mb-3" style="background:linear-gradient(150deg,#FDF0F8,#EFF0FC);border:1px solid #EADFF3">
            <div class="fw-semibold" style="font-size:12px;color:#6B6B85">AI setter status</div>
            <div class="d-flex align-items-center gap-2 fw-bold my-1" style="font-size:14px">
                <span style="width:8px;height:8px;border-radius:50%;background:#0FBF8F"></span>Answering calls 24/7
            </div>
            <div style="font-size:11.5px;color:var(--wf-ink-mute)">Path-based booking link is live</div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="wf-sidebar__link w-100 border-0 bg-transparent text-start">Logout</button>
        </form>
    </div>
</aside>
