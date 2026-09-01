{{--
    Header user dropdown — shows the signed-in name/email and a Logout link.
    Params:
      label       initials shown in the avatar (e.g. "WT", "SH", "S")
      name        full name shown in the dropdown header
      email       email shown under the name
      shape       'square' (default) | 'circle'
      theme       'light' (default) | 'dark'   — matches the surrounding top bar
      avatarStyle optional extra inline style for a square avatar (e.g. gradient)
--}}
@php
    $shape = $shape ?? 'square';
    $theme = $theme ?? 'light';
@endphp

<div class="dropdown">
    <button type="button" class="btn p-0 border-0 wf-usermenu-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
        @if ($shape === 'circle')
            <span class="d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;border-radius:50%;background:#F0EDF7;color:#7A2BC0;font-size:13px">{{ $label }}</span>
        @else
            <span class="wf-avatar" style="width:38px;height:38px;border-radius:10px;font-size:13px;{{ $avatarStyle ?? '' }}">{{ $label }}</span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end {{ $theme === 'dark' ? 'wf-menu-dark' : '' }}">
        <li class="px-3 py-2">
            <div class="fw-bold" style="font-size:13.5px">{{ $name }}</div>
            <div style="font-size:12px;opacity:.6">{{ $email }}</div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('login') }}">Logout</a></li>
    </ul>
</div>
