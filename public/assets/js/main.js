/* =========================================================
   Webefy Appointment Setter — main.js
   Small vanilla helpers for the static portal. No framework.
   All behaviour is opt-in via data-* attributes.
   ========================================================= */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initCopyButtons();
        initBookingWizard();
        initServicePicker();
        initSlotPicker();
        initFilterTabs();
        initRowFilter();
        initEmbedModal();
        initConfirmSubmit();
    });

    /* ---- data-wf-copy: copy a value / element text to clipboard ---- */
    function initCopyButtons() {
        document.querySelectorAll('[data-wf-copy]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var raw = btn.getAttribute('data-wf-copy');
                var text = raw;
                if (raw && raw.charAt(0) === '#') {
                    var el = document.querySelector(raw);
                    text = el ? (el.innerText || el.textContent) : '';
                }
                var done = function () {
                    var label = btn.getAttribute('data-wf-copy-label') || 'Copied ✓';
                    var original = btn.textContent;
                    btn.textContent = label;
                    setTimeout(function () { btn.textContent = original; }, 1600);
                };
                if (navigator.clipboard && text) {
                    navigator.clipboard.writeText(text.trim()).then(done).catch(done);
                } else {
                    done();
                }
            });
        });
    }

    /* ---- public booking page: 3-step wizard ---- */
    function initBookingWizard() {
        var wizard = document.querySelector('[data-wf-wizard]');
        if (!wizard) return;

        var steps = wizard.querySelectorAll('.wf-step');
        var bars = wizard.querySelectorAll('.wf-book__progress span');

        function show(n) {
            steps.forEach(function (s) {
                s.classList.toggle('is-active', Number(s.getAttribute('data-step')) === n);
            });
            bars.forEach(function (b, i) { b.classList.toggle('is-on', i < n); });
        }

        wizard.querySelectorAll('[data-wf-step-to]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                show(Number(btn.getAttribute('data-wf-step-to')));
                wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        show(1);
    }

    /* ---- pick-one card groups (booking services) ---- */
    function initServicePicker() {
        document.querySelectorAll('[data-wf-pick-group]').forEach(function (group) {
            group.querySelectorAll('.wf-choice').forEach(function (choice) {
                choice.addEventListener('click', function () {
                    group.querySelectorAll('.wf-choice').forEach(function (c) {
                        c.classList.remove('is-picked');
                    });
                    choice.classList.add('is-picked');
                });
            });
        });
    }

    /* ---- pick-one time slots ---- */
    function initSlotPicker() {
        document.querySelectorAll('[data-wf-slot-group]').forEach(function (group) {
            group.querySelectorAll('.wf-slot').forEach(function (slot) {
                if (slot.classList.contains('is-taken')) return;
                slot.addEventListener('click', function () {
                    group.querySelectorAll('.wf-slot').forEach(function (s) {
                        s.classList.remove('is-picked');
                    });
                    slot.classList.add('is-picked');
                });
            });
        });
    }

    /* ---- data-wf-tab / data-wf-tab-panel: lightweight in-page tabs ---- */
    function initFilterTabs() {
        document.querySelectorAll('[data-wf-tabs]').forEach(function (scope) {
            var tabs = scope.querySelectorAll('[data-wf-tab]');
            var panels = document.querySelectorAll('[data-wf-tab-panel]');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var target = tab.getAttribute('data-wf-tab');
                    tabs.forEach(function (t) { t.classList.remove('is-active'); });
                    tab.classList.add('is-active');
                    panels.forEach(function (p) {
                        p.classList.toggle('is-active', p.getAttribute('data-wf-tab-panel') === target);
                    });
                });
            });
        });
    }

    /* ---- #wfEmbed modal: fill business name + iframe snippet from the trigger ---- */
    function initEmbedModal() {
        var modal = document.getElementById('wfEmbed');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            if (!trigger) return;

            var business = trigger.getAttribute('data-embed-business') || 'This business';
            var url = trigger.getAttribute('data-embed-url') || '';

            var nameEl = modal.querySelector('#wfEmbedBusiness');
            var snippetEl = modal.querySelector('#wfEmbedSnippet');
            if (nameEl) nameEl.textContent = business;
            if (snippetEl) {
                snippetEl.textContent =
                    '<iframe\n' +
                    '  src="' + url + '"\n' +
                    '  width="100%" height="720"\n' +
                    '  style="border:0;border-radius:16px"\n' +
                    '  title="Book an appointment — ' + business + '"\n' +
                    '  loading="lazy"></iframe>';
            }
        });
    }

    /* ---- data-wf-confirm: ask before submitting a form ---- */
    function initConfirmSubmit() {
        document.querySelectorAll('form[data-wf-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!window.confirm(form.getAttribute('data-wf-confirm'))) {
                    e.preventDefault();
                }
            });
        });
    }

    /* ---- data-wf-row-filter="#tbody": show/hide rows by [data-status] ---- */
    function initRowFilter() {
        document.querySelectorAll('[data-wf-row-filter]').forEach(function (bar) {
            var body = document.querySelector(bar.getAttribute('data-wf-row-filter'));
            if (!body) return;
            var buttons = bar.querySelectorAll('[data-filter]');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var value = btn.getAttribute('data-filter');
                    buttons.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');
                    body.querySelectorAll('tr').forEach(function (row) {
                        var match = value === 'All' || row.getAttribute('data-status') === value;
                        row.style.display = match ? '' : 'none';
                    });
                });
            });
        });
    }
})();
