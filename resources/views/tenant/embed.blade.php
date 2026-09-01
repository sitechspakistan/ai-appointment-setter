@extends('layouts.portal')

@section('title', 'Embed Code · '.$tenant->business_name)

@section('sidebar')
    @include('includes.tenant_sidebar', ['active' => 'embed'])
@endsection

@section('topbar')
    @include('includes.tenant_header', ['active' => 'embed'])
@endsection

@section('page')
<div class="mb-4">
    <div class="fw-bold" style="font-size:34px;letter-spacing:-0.025em">Embed Code</div>
    <div style="font-size:14.5px;color:#6B6B85">Put your booking page on your own website in one paste.</div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="p-4 rounded-4" style="background:#0B0B18;color:#fff">
            <div class="wf-eyebrow mb-2" style="color:#7A7A99">IFRAME SNIPPET</div>
<pre id="embedSnippet" class="p-3 rounded-3 mb-4 wf-mono" style="background:#08081A;border:1px solid rgba(255,255,255,0.12);font-size:12.5px;line-height:1.9;color:#C6C6DC;white-space:pre-wrap;margin:0">{{ $tenant->embedSnippet() }}</pre>

            <div class="wf-eyebrow mb-2" style="color:#7A7A99">OR SHARE THE HOSTED PAGE</div>
            <div class="d-flex align-items-center justify-content-between gap-2 p-2 rounded-3 mb-4" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.14)">
                <span class="ps-2 wf-mono" id="embedHostedUrl" style="font-size:12.5px;color:#E48FCB;word-break:break-all">{{ $tenant->bookingUrl() }}</span>
                <button class="btn btn-sm fw-bold text-white" data-wf-copy="#embedHostedUrl" data-wf-copy-label="Copied &check;" style="border-radius:8px;background:rgba(255,255,255,0.12);font-size:12.5px">Copy</button>
            </div>

            <button class="btn wf-btn-brand w-100" style="height:46px;font-size:14.5px" data-wf-copy="#embedSnippet" data-wf-copy-label="Copied &check;">Copy Code</button>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="wf-card p-4 h-100">
            <div class="fw-bold mb-3" style="font-size:16px">How it behaves</div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex gap-2"><span style="width:8px;height:8px;border-radius:2px;margin-top:6px;flex:none;background:#EC008C"></span><div style="font-size:13.5px;color:#4A4A63;line-height:1.55">Loads on any site — WordPress, Wix, Squarespace, plain HTML.</div></div>
                <div class="d-flex gap-2"><span style="width:8px;height:8px;border-radius:2px;margin-top:6px;flex:none;background:#A21CAF"></span><div style="font-size:13.5px;color:#4A4A63;line-height:1.55">Bookings made in the embed appear in your dashboard instantly.</div></div>
                <div class="d-flex gap-2"><span style="width:8px;height:8px;border-radius:2px;margin-top:6px;flex:none;background:#2B4EC8"></span><div style="font-size:13.5px;color:#4A4A63;line-height:1.55">Reminders and confirmation calls run the same as phone bookings.</div></div>
            </div>

            <div class="wf-note p-3 mt-4">Paste the snippet into a Custom HTML / Embed block on your site.</div>

            <a href="{{ route('booking', $tenant->booking_slug) }}" target="_blank" class="btn w-100 fw-semibold mt-3" style="height:44px;border-radius:11px;border:1px solid #E0E0EA;color:#4A4A63;font-size:13.5px">Preview the booking page &rarr;</a>
        </div>
    </div>
</div>
@endsection
