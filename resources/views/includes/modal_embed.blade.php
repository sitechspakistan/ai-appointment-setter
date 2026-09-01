{{-- "Get Embed Code" modal. Trigger buttons carry:
       data-bs-toggle="modal" data-bs-target="#wfEmbed"
       data-embed-business="Sarah's HVAC"
       data-embed-url="https://ai-appointment.webefytoday.com/book/sarahshvac"
--}}
<div class="modal fade" id="wfEmbed" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:568px">
        <div class="modal-content" style="background:#12122A;border:1px solid rgba(255,255,255,0.12);border-radius:22px;overflow:hidden;color:#fff">
            <div style="height:4px;background:linear-gradient(90deg,#2B4EC8,#A21CAF,#EC008C)"></div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="fw-bold" style="font-size:23px;letter-spacing:-0.02em">Embed Code</div>
                        <div style="font-size:13.5px;color:#9A9AB8"><span id="wfEmbedBusiness">This business</span> &middot; paste into any page of the client site.</div>
                    </div>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="width:30px;height:30px;padding:0;border-radius:9px;border:1px solid rgba(255,255,255,0.14);color:#A6A6C0">&times;</button>
                </div>

                <div class="wf-eyebrow mb-2" style="color:#7A7A99">IFRAME SNIPPET</div>
<pre id="wfEmbedSnippet" class="p-3 rounded-3 mb-3 wf-mono" style="background:#08081A;border:1px solid rgba(255,255,255,0.12);font-size:12px;line-height:1.85;color:#C6C6DC;white-space:pre-wrap;margin:0"></pre>

                <div class="p-3 rounded-3 mb-4" style="background:rgba(43,78,200,0.14);border:1px solid rgba(43,78,200,0.38);font-size:12.5px;color:#BFCBF5;line-height:1.45">
                    Auto-resizes on mobile. Bookings made in the embed appear in the tenant dashboard instantly.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn fw-semibold" data-bs-dismiss="modal" style="height:44px;border-radius:11px;border:1px solid rgba(255,255,255,0.16);color:#C6C6DC;font-size:14.5px">Close</button>
                    <button type="button" class="btn wf-btn-brand" style="height:44px;border-radius:11px;font-size:14.5px" data-wf-copy="#wfEmbedSnippet" data-wf-copy-label="Copied &check;">Copy Code</button>
                </div>
            </div>
        </div>
    </div>
</div>
