{{-- Tenant Admin top bar + mobile nav strip. Pass ['active' => '...']. --}}
@php($active = $active ?? 'overview')
@php($t = $currentTenant)
@php($statusLabels = ['active' => 'Active plan', 'trial' => 'Trial', 'paused' => 'Paused'])

<header class="wf-topbar bg-white" style="border-bottom:1px solid var(--wf-border)">
    <div class="d-flex align-items-center gap-2">
        @include('includes.user_menu', [
            'label' => $t?->initials() ?? 'T',
            'name' => $t?->business_name ?? 'Your business',
            'email' => $authUser?->email ?? '',
        ])
        <div class="fw-bold" style="font-size:16px">{{ $t?->business_name ?? 'Your business' }}</div>
        <span class="wf-pill wf-pill--{{ $t?->status === 'active' ? 'green' : ($t?->status === 'paused' ? 'red' : 'amber') }} d-none d-sm-inline">
            {{ $statusLabels[$t?->status] ?? 'Trial' }}
        </span>
    </div>
    <div class="d-flex align-items-center gap-3">
        @include('includes.user_menu', [
            'label' => $authUser?->initials() ?? 'U',
            'shape' => 'circle',
            'name' => $authUser?->name ?? 'You',
            'email' => $authUser?->email ?? '',
        ])
    </div>
</header>

<nav class="wf-navstrip wf-scroll bg-white" style="border-bottom:1px solid var(--wf-border)">
    <a href="{{ route('tenant.overview') }}"         class="wf-navstrip__link {{ $active === 'overview'     ? 'is-active' : '' }}">Overview</a>
    <a href="{{ route('tenant.appointments') }}"     class="wf-navstrip__link {{ $active === 'appointments' ? 'is-active' : '' }}">Appointments</a>
    <a href="{{ route('tenant.reminders') }}"        class="wf-navstrip__link {{ $active === 'reminders'    ? 'is-active' : '' }}">Reminders</a>
    <a href="{{ route('tenant.booking-settings') }}" class="wf-navstrip__link {{ $active === 'settings'     ? 'is-active' : '' }}">Booking Page Settings</a>
    <a href="{{ route('tenant.embed') }}"            class="wf-navstrip__link {{ $active === 'embed'        ? 'is-active' : '' }}">Embed Code</a>
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="wf-navstrip__link border-0">Logout</button>
    </form>
</nav>
