@extends('layouts.master')

@section('title', 'Book an appointment · '.$tenant->business_name)

@section('content')
@php($booked = session('booked'))

<div class="wf-book">

    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="wf-logo-tile" style="width:44px;height:44px;border-radius:13px;font-size:20px">{{ \Illuminate\Support\Str::substr($tenant->business_name, 0, 1) }}</span>
        <div class="fw-bold" style="font-size:21px;letter-spacing:-0.02em;color:#3A3A52">
            {{ $tenant->business_name }} &middot; <span style="color:#0B0B18">powered by Webefy</span>
        </div>
    </div>

    <div class="wf-book__card" data-wf-wizard>
        <div class="px-4 px-md-5 pt-4 pt-md-5 pb-4">
            <div class="fw-bold mb-2" style="font-size:13px;letter-spacing:0.12em;color:#7A2BC0">BOOK AN APPOINTMENT</div>
            <div class="fw-bold" style="font-size:34px;line-height:1.1;letter-spacing:-0.03em">Grab a time that works for you.</div>
            <div class="mt-3" style="font-size:17px;line-height:1.5;color:#4A4A63">Tell us what you need and pick a slot — we'll confirm it in minutes.</div>
        </div>

        <div class="px-4 px-md-5 pb-4 pb-md-5" style="border-top:1px solid #EDEDF3">

            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius:12px">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="wf-book__progress">
                <span class="is-on"></span>
                <span class="{{ $booked ? 'is-on' : '' }}"></span>
            </div>

            @if ($booked)
                {{-- Confirmation --}}
                <div class="wf-step is-active" data-step="1">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="d-flex align-items-center justify-content-center text-white fw-bold" style="width:46px;height:46px;border-radius:14px;font-size:20px;background:linear-gradient(140deg,#EC008C,#3F2BD4)">&check;</span>
                        <div>
                            <div class="fw-bold" style="font-size:22px;letter-spacing:-0.02em">You're on the books</div>
                            <div style="font-size:14.5px;color:#6B6B85">We'll text a confirmation to your phone shortly.</div>
                        </div>
                    </div>
                    <div class="rounded-4 mb-4" style="border:1px solid #EDEDF3">
                        <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px;border-bottom:1px solid #F2F2F7"><span style="color:#8A8AA0">Service</span><span class="fw-bold">{{ $booked['service'] }}</span></div>
                        <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px;border-bottom:1px solid #F2F2F7"><span style="color:#8A8AA0">When</span><span class="fw-bold">{{ $booked['when'] }}</span></div>
                        <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px"><span style="color:#8A8AA0">Contact</span><span class="fw-bold">{{ $booked['name'] }}</span></div>
                    </div>
                    <div class="wf-note p-3 mb-4">A WhatsApp reminder goes out before your slot. Reply YES to confirm or CHANGE to pick a new time.</div>
                    <a href="{{ route('booking', $tenant->booking_slug) }}" class="btn w-100 fw-semibold" style="height:52px;border-radius:14px;border:1px solid #E0E0EA;color:#4A4A63;font-size:15.5px;line-height:38px">Book another appointment</a>
                </div>
            @else
                <form method="POST" action="{{ route('booking.store', $tenant->booking_slug) }}">
                    @csrf

                    {{-- Step 1 --}}
                    <div class="wf-step is-active" data-step="1">
                        <div class="fw-bold mb-3" style="font-size:16px;color:#2B2B40">What do you need help with?</div>

                        @if ($services->isNotEmpty())
                            <div class="row g-3 mb-4" data-wf-pick-group>
                                @foreach ($services as $i => $s)
                                    <div class="col-12 col-sm-6">
                                        <label class="wf-choice d-block {{ old('service_id', $services->first()->id) == $s->id ? 'is-picked' : '' }}">
                                            <input type="radio" name="service_id" value="{{ $s->id }}" class="d-none" @checked(old('service_id', $services->first()->id) == $s->id)>
                                            @if($s->icon)<div style="font-size:26px;line-height:1">{{ $s->icon }}</div>@endif
                                            <div class="fw-bold mt-4" style="font-size:17px">{{ $s->name }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <input name="service_name" value="{{ old('service_name') }}" required class="form-control mb-4" placeholder="Describe what you need" style="border-radius:14px;border-color:#E4E4EE;font-size:16px;height:56px">
                        @endif

                        <div class="fw-bold mb-2" style="font-size:16px;color:#2B2B40">Anything we should know? <span style="color:#8A8AA0">(optional)</span></div>
                        <textarea name="notes" rows="2" class="form-control mb-4" placeholder="e.g. Started yesterday, making a rattling noise..." style="border-radius:14px;border-color:#E4E4EE;font-size:16px;padding:14px 16px;resize:none">{{ old('notes') }}</textarea>

                        <button type="button" class="btn wf-btn-brand w-100" data-wf-step-to="2" style="height:60px;border-radius:15px;font-size:19px">Continue &rarr;</button>
                    </div>

                    {{-- Step 2 --}}
                    <div class="wf-step" data-step="2">
                        <input type="hidden" name="appointment_date" value="{{ old('appointment_date', $slotDate->toDateString()) }}">
                        <div class="fw-bold mb-3" style="font-size:16px;color:#2B2B40">{{ $slotDate->format('l, F j') }}</div>
                        <div class="row g-3 mb-4" data-wf-slot-group>
                            @foreach ($slots as $slot)
                                <div class="col-6">
                                    <label class="wf-slot d-block {{ $slot['taken'] ? 'is-taken' : '' }} {{ old('appointment_time') === $slot['value'] ? 'is-picked' : '' }}">
                                        <input type="radio" name="appointment_time" value="{{ $slot['value'] }}" class="d-none" @disabled($slot['taken']) @checked(old('appointment_time') === $slot['value'])>
                                        {{ $slot['label'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="fw-bold mb-2" style="font-size:16px;color:#2B2B40">Your details</div>
                        <input name="customer_name" value="{{ old('customer_name') }}" required class="form-control mb-3" placeholder="Full name" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">
                        <input name="customer_email" type="email" value="{{ old('customer_email') }}" class="form-control mb-3" placeholder="Email address (optional)" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">
                        <input name="customer_phone" value="{{ old('customer_phone') }}" required class="form-control mb-4" placeholder="Phone number" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">

                        <button type="submit" class="btn wf-btn-brand w-100" style="height:60px;border-radius:15px;font-size:19px">Confirm Booking &rarr;</button>
                        <div class="text-center mt-3 fw-bold" data-wf-step-to="1" style="font-size:16px;color:#2B2B40;cursor:pointer">&larr; Back</div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-4 fw-semibold" style="font-size:15px;color:#9A9AB0">Powered by <span style="color:var(--wf-purple)">Webefy</span></div>
</div>
@endsection
