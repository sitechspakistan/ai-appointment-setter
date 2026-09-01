{{-- Super Admin sidebar. Pass ['active' => 'overview'|'tenants'|'billing'|'settings']. --}}
@php($active = $active ?? 'overview')
@php($creditsTotal = (int) ($agency['reminder_credits_total'] ?? 10000))
@php($creditsUsed = (int) ($agency['reminder_credits_used'] ?? 0))
@php($creditsPct = $creditsTotal > 0 ? min(100, round($creditsUsed / $creditsTotal * 100)) : 0)

<aside class="wf-sidebar">
    <div class="bg-white rounded-3 d-flex justify-content-center align-items-center p-2 mb-4">
        <img src="{{ asset('assets/img/webefy-logo.png') }}" alt="{{ $agency['agency_name'] ?? 'Webefy Today' }}" style="height:26px;width:auto">
    </div>

    <div class="wf-eyebrow px-2 pb-2">AGENCY CONTROL</div>

    <a href="{{ route('admin.overview') }}" class="wf-sidebar__link {{ $active === 'overview' ? 'is-active' : '' }}">Overview</a>
    <a href="{{ route('admin.tenants') }}"  class="wf-sidebar__link {{ $active === 'tenants'  ? 'is-active' : '' }}">Tenants</a>
    <a href="{{ route('admin.billing') }}"  class="wf-sidebar__link {{ $active === 'billing'  ? 'is-active' : '' }}">Billing</a>
    <a href="{{ route('admin.settings') }}" class="wf-sidebar__link {{ $active === 'settings' ? 'is-active' : '' }}">Settings</a>

    <div class="mt-auto">
        <div class="wf-card p-3 mb-3" style="border-radius:16px">
            <div style="font-size:12px;color:var(--wf-dark-mute)">Reminder credits</div>
            <div class="fw-bold my-1" style="font-size:20px">{{ number_format($creditsUsed) }}
                <span style="font-size:12px;font-weight:500;color:var(--wf-dark-mute)">/ {{ number_format($creditsTotal) }}</span>
            </div>
            <div class="wf-meter"><div class="wf-meter__fill" style="width:{{ $creditsPct }}%"></div></div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="wf-sidebar__link w-100 border-0 bg-transparent text-start">Logout</button>
        </form>
    </div>
</aside>
