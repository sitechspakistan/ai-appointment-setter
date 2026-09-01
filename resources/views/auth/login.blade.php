@extends('layouts.master')

@section('title', 'Sign in · Webefy Appointment Setter')

@section('content')
<div class="wf-auth">
    <div class="wf-auth__glow"></div>

    <div class="wf-auth__card p-4 p-md-5 shadow-lg">
        <div class="d-flex flex-column align-items-center gap-3 mb-4">
            <img src="{{ asset('assets/img/webefy-logo.png') }}" alt="Webefy Today" style="height:36px;width:auto">
            <div class="text-center" style="font-size:15px;color:#5A5A75">One login. Your dashboard, automatically.</div>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="border-radius:12px;font-size:13px">{{ session('status') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600;color:#2B2B40">Email</label>
                <input type="email" name="email" id="loginEmail" value="{{ old('email') }}" required autofocus
                       class="form-control form-control-lg wf-field-lg @error('email') is-invalid @enderror" placeholder="you@business.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <label class="form-label" style="font-size:13px;font-weight:600;color:#2B2B40">Password</label>
                    <a href="{{ route('password.request') }}" style="font-size:12px;font-weight:600">Forgot password?</a>
                </div>
                <input type="password" name="password" id="loginPassword" required
                       class="form-control form-control-lg wf-field-lg" placeholder="&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;">
            </div>
            <button type="submit" class="btn wf-btn-brand w-100" style="height:52px;border-radius:13px;font-size:16px">Sign in</button>
        </form>

        <div class="d-flex align-items-center gap-2 mt-4 p-3" style="border-radius:12px;background:#F4F1FB;border:1px solid #E6DEF7">
            <div style="width:8px;height:8px;border-radius:2px;flex:none;background:linear-gradient(135deg,#EC008C,#2B4EC8)"></div>
            <div style="font-size:12.5px;color:#5A4A78;line-height:1.45">Role is detected on sign-in — Webefy staff land on the control tower, business owners on their own dashboard.</div>
        </div>

        @php($demoSlug = \App\Models\Tenant::query()->value('booking_slug'))
        @if ($demoSlug)
            <div class="mt-3 pt-3 text-center" style="border-top:1px solid #EDEDF3">
                <a href="{{ route('booking', $demoSlug) }}" style="font-size:12.5px;font-weight:600">View public booking page &rarr;</a>
            </div>
        @endif
    </div>
</div>
@endsection
