{{-- "New Tenant" modal — triggered with data-bs-toggle="modal" data-bs-target="#wfNewTenant". --}}
<div class="modal fade" id="wfNewTenant" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:568px">
        <div class="modal-content" style="background:#12122A;border:1px solid rgba(255,255,255,0.12);border-radius:22px;overflow:hidden;color:#fff">
            <div style="height:4px;background:linear-gradient(90deg,#EC008C,#A21CAF,#2B4EC8)"></div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="fw-bold" style="font-size:23px;letter-spacing:-0.02em">New Tenant</div>
                        <div style="font-size:13.5px;color:#9A9AB8">Creates the business, its booking page and reminder workflows.</div>
                    </div>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="width:30px;height:30px;padding:0;border-radius:9px;border:1px solid rgba(255,255,255,0.14);color:#A6A6C0">&times;</button>
                </div>

                <form action="#" method="POST">
                    @csrf
                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Business Name</label>
                    <input class="form-control mb-3" placeholder="Sarah's HVAC" style="border-radius:11px;height:46px">

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Owner Email</label>
                    <input class="form-control mb-3" type="email" placeholder="sarah@sarahshvac.com" style="border-radius:11px;height:46px">

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Industry</label>
                    <select class="form-select mb-3" style="border-radius:11px;height:46px">
                        <option>HVAC</option><option>Dental</option><option>Pool Cleaning</option>
                        <option>Salon</option><option>Roofing</option><option>Med Spa</option><option>Other&hellip;</option>
                    </select>

                    <label class="form-label" style="font-size:12.5px;color:#C6C6DC;font-weight:600">Booking Slug</label>
                    <div class="input-group mb-1">
                        <span class="input-group-text wf-affix" style="border-radius:11px 0 0 11px">/book/</span>
                        <input class="form-control wf-mono" placeholder="sarahshvac" style="border-radius:0 11px 11px 0;height:46px;color:#E48FCB">
                    </div>
                    <div style="font-size:11.5px;color:#8B8BA8">Public URL: ai-appointment.webefytoday.com/book/sarahshvac</div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn fw-semibold" data-bs-dismiss="modal" style="height:44px;border-radius:11px;border:1px solid rgba(255,255,255,0.16);color:#C6C6DC;font-size:14.5px">Cancel</button>
                        <button type="submit" class="btn wf-btn-brand" style="height:44px;border-radius:11px;font-size:14.5px">Create Tenant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
