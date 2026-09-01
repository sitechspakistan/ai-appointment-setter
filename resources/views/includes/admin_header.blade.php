{{-- Super Admin top bar + mobile nav strip. Pass ['active' => '...']. --}}
@php($active = $active ?? 'overview')
@php($name = $authUser?->name ?? 'Super Admin')
@php($email = $authUser?->email ?? '')

<header class="wf-topbar">
    <div class="d-flex align-items-center gap-3">
        <span class="fw-semibold wf-mono" style="font-size:11px;letter-spacing:0.14em;padding:6px 11px;border-radius:7px;background:var(--wf-grad)">SUPER ADMIN</span>
        <span class="d-none d-md-inline" style="font-size:14px;color:#9A9AB8">
            Logged in as <span class="fw-bold text-white">{{ $name }}</span>@if($email) &middot; {{ $email }}@endif
        </span>
    </div>
    <div class="d-flex align-items-center gap-3">
        @include('includes.user_menu', [
            'label' => $authUser?->initials() ?? 'WT',
            'name' => $name,
            'email' => $email,
            'theme' => 'dark',
            'avatarStyle' => 'background:linear-gradient(135deg,#EC008C,#2B4EC8)',
        ])
    </div>
</header>

<nav class="wf-navstrip wf-scroll">
    <span class="bg-white rounded-2 d-flex align-items-center px-2 py-1" style="flex:none">
        <img src="{{ asset('assets/img/webefy-logo.png') }}" alt="Webefy Today" style="height:16px;width:auto">
    </span>
    <a href="{{ route('admin.overview') }}" class="wf-navstrip__link {{ $active === 'overview' ? 'is-active' : '' }}">Overview</a>
    <a href="{{ route('admin.tenants') }}"  class="wf-navstrip__link {{ $active === 'tenants'  ? 'is-active' : '' }}">Tenants</a>
    <a href="{{ route('admin.billing') }}"  class="wf-navstrip__link {{ $active === 'billing'  ? 'is-active' : '' }}">Billing</a>
    <a href="{{ route('admin.settings') }}" class="wf-navstrip__link {{ $active === 'settings' ? 'is-active' : '' }}">Settings</a>
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="wf-navstrip__link border-0">Logout</button>
    </form>
</nav>
