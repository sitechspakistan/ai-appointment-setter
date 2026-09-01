{{-- Tenant Admin top bar + mobile nav strip. Pass ['active' => '...']. --}}
@php($active = $active ?? 'overview')

<header class="wf-topbar bg-white" style="border-bottom:1px solid var(--wf-border)">
    <div class="d-flex align-items-center gap-2">
        @include('includes.user_menu', [
            'label' => 'SH',
            'name' => "Sarah's HVAC",
            'email' => 'sarah@sarahshvac.com',
        ])
        <div class="fw-bold" style="font-size:16px">Sarah's HVAC</div>
        <span class="wf-pill wf-pill--green d-none d-sm-inline">Active plan</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        @include('includes.user_menu', [
            'label' => 'S',
            'shape' => 'circle',
            'name' => 'Sarah',
            'email' => 'sarah@sarahshvac.com',
        ])
    </div>
</header>

<nav class="wf-navstrip wf-scroll bg-white" style="border-bottom:1px solid var(--wf-border)">
    <a href="{{ route('tenant.overview') }}"         class="wf-navstrip__link {{ $active === 'overview'     ? 'is-active' : '' }}">Overview</a>
    <a href="{{ route('tenant.appointments') }}"     class="wf-navstrip__link {{ $active === 'appointments' ? 'is-active' : '' }}">Appointments</a>
    <a href="{{ route('tenant.reminders') }}"        class="wf-navstrip__link {{ $active === 'reminders'    ? 'is-active' : '' }}">Reminders</a>
    <a href="{{ route('tenant.booking-settings') }}" class="wf-navstrip__link {{ $active === 'settings'     ? 'is-active' : '' }}">Booking Page Settings</a>
    <a href="{{ route('tenant.embed') }}"            class="wf-navstrip__link {{ $active === 'embed'        ? 'is-active' : '' }}">Embed Code</a>
    <a href="{{ route('login') }}" class="wf-navstrip__link">Logout</a>
</nav>
