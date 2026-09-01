{{-- "New Tenant" modal — triggered with data-bs-toggle="modal" data-bs-target="#wfNewTenant". --}}
<div class="modal fade" id="wfNewTenant" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:568px">
        <div class="modal-content" style="background:#12122A;border:1px solid rgba(255,255,255,0.12);border-radius:22px;overflow:hidden;color:#fff">
            <div style="height:4px;background:linear-gradient(90deg,#EC008C,#A21CAF,#2B4EC8)"></div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="fw-bold" style="font-size:23px;letter-spacing:-0.02em">New Tenant</div>
                        <div style="font-size:13.5px;color:#9A9AB8">Creates the business and its first Tenant Admin user.</div>
                    </div>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="width:30px;height:30px;padding:0;border-radius:9px;border:1px solid rgba(255,255,255,0.14);color:#A6A6C0">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.tenants.store') }}">
                    @csrf
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Business Name</label>
                    <input name="business_name" value="{{ old('business_name') }}" required class="form-control mb-3" placeholder="Sarah's HVAC" style="border-radius:11px;height:46px">

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Owner Name <span style="color:#7A7A99">(optional)</span></label>
                    <input name="owner_name" value="{{ old('owner_name') }}" class="form-control mb-3" placeholder="Sarah Nguyen" style="border-radius:11px;height:46px">

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Owner Email</label>
                    <input name="owner_email" type="email" value="{{ old('owner_email') }}" required class="form-control mb-3" placeholder="sarah@sarahshvac.com" style="border-radius:11px;height:46px">

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Industry</label>
                    <select name="industry" class="form-select mb-3" style="border-radius:11px;height:46px">
                        @foreach (['HVAC', 'Dental', 'Pool Cleaning', 'Salon', 'Roofing', 'Med Spa', 'Other'] as $ind)
                            <option value="{{ $ind }}" @selected(old('industry') === $ind)>{{ $ind }}</option>
                        @endforeach
                    </select>

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Booking Slug</label>
                    <div class="input-group mb-1">
                        <span class="input-group-text wf-affix" style="border-radius:11px 0 0 11px">/book/</span>
                        <input name="booking_slug" value="{{ old('booking_slug') }}" required class="form-control wf-mono" placeholder="sarahshvac" style="border-radius:0 11px 11px 0;height:46px;color:#E48FCB">
                    </div>
                    <div style="font-size:11.5px;color:#8B8BA8">Public URL: {{ rtrim(\App\Models\Setting::get('booking_domain', config('app.url')), '/') }}/book/&lt;slug&gt;</div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn fw-semibold" data-bs-dismiss="modal" style="height:44px;border-radius:11px;border:1px solid rgba(255,255,255,0.16);color:#C6C6DC;font-size:14.5px">Cancel</button>
                        <button type="submit" class="btn wf-btn-brand" style="height:44px;border-radius:11px;font-size:14.5px">Create Tenant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($errors->any() && old('business_name') !== null)
    <script>document.addEventListener('DOMContentLoaded', function () { new bootstrap.Modal('#wfNewTenant').show(); });</script>
@endif
