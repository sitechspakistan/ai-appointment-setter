{{-- Super Admin sidebar. Pass ['active' => 'overview'|'tenants'|'billing'|'settings']. --}}
@php($active = $active ?? 'overview')

<aside class="wf-sidebar">
    <div class="bg-white rounded-3 d-flex justify-content-center align-items-center p-2 mb-4">
        <img src="{{ asset('assets/img/webefy-logo.png') }}" alt="Webefy Today" style="height:26px;width:auto">
    </div>

    <div class="wf-eyebrow px-2 pb-2">AGENCY CONTROL</div>

    <a href="{{ route('admin.overview') }}" class="wf-sidebar__link {{ $active === 'overview' ? 'is-active' : '' }}">Overview</a>
    <a href="{{ route('admin.tenants') }}"  class="wf-sidebar__link {{ $active === 'tenants'  ? 'is-active' : '' }}">Tenants</a>
    <a href="{{ route('admin.billing') }}"  class="wf-sidebar__link {{ $active === 'billing'  ? 'is-active' : '' }}">Billing</a>
    <a href="{{ route('admin.settings') }}" class="wf-sidebar__link {{ $active === 'settings' ? 'is-active' : '' }}">Settings</a>

    <div class="mt-auto">
        <div class="wf-card p-3 mb-3" style="border-radius:16px">
            <div style="font-size:12px;color:var(--wf-dark-mute)">Reminder credits</div>
            <div class="fw-bold my-1" style="font-size:20px">8,412 <span style="font-size:12px;font-weight:500;color:var(--wf-dark-mute)">/ 10,000</span></div>
            <div class="wf-meter"><div class="wf-meter__fill" style="width:84%"></div></div>
        </div>

        <a href="{{ route('login') }}" class="wf-sidebar__link">Logout</a>
    </div>
</aside>
