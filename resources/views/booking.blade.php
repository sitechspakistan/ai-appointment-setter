@extends('layouts.master')

@section('title', 'Book an appointment · Sarah\'s HVAC')

@section('content')
@php($slug = $slug ?? 'sarahshvac')

<div class="wf-book">

    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="wf-logo-tile" style="width:44px;height:44px;border-radius:13px;font-size:20px">W</span>
        <div class="fw-bold" style="font-size:21px;letter-spacing:-0.02em;color:#3A3A52">
            Sarah's HVAC &middot; <span style="color:#0B0B18">powered by Webefy</span>
        </div>
    </div>

    <div class="wf-book__card" data-wf-wizard>
        <div class="px-4 px-md-5 pt-4 pt-md-5 pb-4">
            <div class="fw-bold mb-2" style="font-size:13px;letter-spacing:0.12em;color:#7A2BC0">BOOK A REPAIR</div>
            <div class="fw-bold" style="font-size:36px;line-height:1.1;letter-spacing:-0.03em">AC not cooling? We're out today.</div>
            <div class="mt-3" style="font-size:17px;line-height:1.5;color:#4A4A63">Tell us what's going on and grab a time that works — we'll confirm it in minutes.</div>
        </div>

        <div class="px-4 px-md-5 pb-4 pb-md-5" style="border-top:1px solid #EDEDF3">
            <div class="wf-book__progress">
                <span class="is-on"></span>
                <span></span>
            </div>

            {{-- Step 1 --}}
            <div class="wf-step is-active" data-step="1">
                <div class="fw-bold mb-3" style="font-size:16px;color:#2B2B40">What do you need help with?</div>
                <div class="row g-3 mb-4" data-wf-pick-group>
                    @foreach ([['AC Repair', '❄️'], ['Heating Issue', '🔥'], ['Tune-Up', '🛠️'], ['New Install / Quote', '📋']] as $i => $service)
                        <div class="col-12 col-sm-6">
                            <div class="wf-choice {{ $i === 0 ? 'is-picked' : '' }}">
                                <div style="font-size:26px;line-height:1">{{ $service[1] }}</div>
                                <div class="fw-bold mt-4" style="font-size:17px">{{ $service[0] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="fw-bold mb-2" style="font-size:16px;color:#2B2B40">Anything we should know? <span style="color:#8A8AA0">(optional)</span></div>
                <textarea class="form-control mb-4" rows="2" placeholder="e.g. Not cooling since yesterday, making a rattling noise..." style="border-radius:14px;border-color:#E4E4EE;font-size:16px;padding:14px 16px;resize:none"></textarea>

                <button type="button" class="btn wf-btn-brand w-100" data-wf-step-to="2" style="height:60px;border-radius:15px;font-size:19px">Continue &rarr;</button>
            </div>

            {{-- Step 2 --}}
            <div class="wf-step" data-step="2">
                <div class="fw-bold mb-3" style="font-size:16px;color:#2B2B40">Tomorrow, September 3</div>
                <div class="row g-3 mb-4" data-wf-slot-group>
                    @foreach ([['9:00 AM', true], ['10:30 AM', false], ['12:00 PM', true], ['2:00 PM', false], ['3:30 PM', false], ['5:00 PM', false]] as $slot)
                        <div class="col-6">
                            <div class="wf-slot {{ $slot[1] ? 'is-taken' : '' }} {{ $slot[0] === '10:30 AM' ? 'is-picked' : '' }}">{{ $slot[0] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="fw-bold mb-2" style="font-size:16px;color:#2B2B40">Your details</div>
                <input class="form-control mb-3" placeholder="Full name" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">
                <input class="form-control mb-3" type="email" placeholder="Email address" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">
                <input class="form-control mb-4" placeholder="Phone number" style="height:56px;border-radius:14px;border-color:#E4E4EE;font-size:16px">

                <button type="button" class="btn wf-btn-brand w-100" data-wf-step-to="3" style="height:60px;border-radius:15px;font-size:19px">Review Booking &rarr;</button>
                <div class="text-center mt-3 fw-bold" data-wf-step-to="1" style="font-size:16px;color:#2B2B40;cursor:pointer">&larr; Back</div>
            </div>

            {{-- Step 3 --}}
            <div class="wf-step" data-step="3">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="d-flex align-items-center justify-content-center text-white fw-bold" style="width:46px;height:46px;border-radius:14px;font-size:20px;background:linear-gradient(140deg,#EC008C,#3F2BD4)">&check;</span>
                    <div>
                        <div class="fw-bold" style="font-size:22px;letter-spacing:-0.02em">You're on the books</div>
                        <div style="font-size:14.5px;color:#6B6B85">We'll text a confirmation to your phone in a minute.</div>
                    </div>
                </div>

                <div class="rounded-4 mb-4" style="border:1px solid #EDEDF3">
                    <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px;border-bottom:1px solid #F2F2F7"><span style="color:#8A8AA0">Service</span><span class="fw-bold">AC Repair</span></div>
                    <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px;border-bottom:1px solid #F2F2F7"><span style="color:#8A8AA0">When</span><span class="fw-bold">Wed, Sep 3 &middot; 10:30 AM</span></div>
                    <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px;border-bottom:1px solid #F2F2F7"><span style="color:#8A8AA0">Technician window</span><span class="fw-bold">2-hour arrival window</span></div>
                    <div class="d-flex justify-content-between px-3 py-3" style="font-size:14.5px"><span style="color:#8A8AA0">Contact</span><span class="fw-bold">Marcus Reed</span></div>
                </div>

                <div class="wf-note p-3 mb-4">A WhatsApp reminder goes out 3 hours before your slot. Reply YES to confirm or CHANGE to pick a new time.</div>

                <button type="button" class="btn w-100 fw-semibold" data-wf-step-to="1" style="height:52px;border-radius:14px;border:1px solid #E0E0EA;color:#4A4A63;font-size:15.5px">Book another appointment</button>
            </div>
        </div>
    </div>

    @include('includes.footer')
</div>
@endsection
