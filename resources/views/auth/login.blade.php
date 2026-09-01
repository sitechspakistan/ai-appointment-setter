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

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600;color:#2B2B40">Email</label>
                <input type="email" name="email" class="form-control form-control-lg wf-field-lg" placeholder="you@business.com">
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <label class="form-label" style="font-size:13px;font-weight:600;color:#2B2B40">Password</label>
                    <a href="{{ route('password.request') }}" style="font-size:12px;font-weight:600">Forgot password?</a>
                </div>
                <input type="password" name="password" class="form-control form-control-lg wf-field-lg" placeholder="&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;">
            </div>
            <button type="submit" class="btn wf-btn-brand w-100" style="height:52px;border-radius:13px;font-size:16px">Sign in</button>
        </form>

        <div class="d-flex align-items-center gap-2 mt-4 p-3" style="border-radius:12px;background:#F4F1FB;border:1px solid #E6DEF7">
            <div style="width:8px;height:8px;border-radius:2px;flex:none;background:linear-gradient(135deg,#EC008C,#2B4EC8)"></div>
            <div style="font-size:12.5px;color:#5A4A78;line-height:1.45">Role is detected on sign-in — Webefy staff land on the control tower, business owners on their own dashboard.</div>
        </div>

        <div class="d-flex flex-column gap-2 mt-3 pt-3" style="border-top:1px solid #EDEDF3">
            <div class="wf-eyebrow">DEMO SIGN-IN</div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.overview') }}" class="btn btn-sm flex-fill fw-semibold text-white" style="border-radius:10px;background:#0B0B18;font-size:12.5px;padding:9px">Super Admin</a>
                <a href="{{ route('tenant.overview') }}" class="btn btn-sm flex-fill fw-semibold" style="border-radius:10px;border:1px solid #DEDEE8;color:#2B2B40;font-size:12.5px;padding:9px">Sarah's HVAC</a>
            </div>
            <a href="{{ route('booking') }}" class="btn btn-sm w-100 fw-semibold" style="border-radius:10px;border:1px dashed #DEDEE8;color:#7A2BC0;font-size:12.5px;padding:9px">View public booking page &rarr;</a>
        </div>
    </div>
</div>
@endsection
