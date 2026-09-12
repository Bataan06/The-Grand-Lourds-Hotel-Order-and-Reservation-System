@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .form-card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.08); background:white; padding:28px; margin-bottom:20px; }
    .section-label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#a78bfa; margin-bottom:12px; margin-top:4px; }
    .form-label { font-weight:600; color:#4a0080; font-size:0.83rem; margin-bottom:4px; }
    .form-control, .form-select { border:1.5px solid #e9d5ff; border-radius:8px; font-size:0.85rem; padding:9px 12px; transition: border-color 0.2s, box-shadow 0.2s; }
    .form-control:focus, .form-select:focus { border-color:#7b2ff7; box-shadow:0 0 0 3px rgba(123,47,247,0.1); outline:none; }

    /* Validation states */
    .form-control.is-valid, .form-select.is-valid { border-color: #10b981 !important; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='%2310b981' d='M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Ccircle cx='6' cy='6' r='5' fill='none' stroke='%23ef4444' stroke-width='1.5'/%3E%3Cpath stroke='%23ef4444' stroke-width='1.5' stroke-linecap='round' d='M6 3.5v3M6 8.5v.5'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; }
    .field-error { color: #ef4444; font-size: 0.74rem; font-weight: 600; margin-top: 4px; display: none; }
    .field-error.show { display: block; }

    /* Step validation indicator */
    .step-indicator { display: flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; margin-bottom: 10px; }
    .step-indicator.step-pending  { background: #fef3c7; color: #92400e; }
    .step-indicator.step-done     { background: #d1fae5; color: #065f46; }
    .step-indicator.step-required { background: #fee2e2; color: #991b1b; }

    .btn-save { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border:none; border-radius:8px; padding:11px 28px; font-weight:600; font-size:0.9rem; }
    .btn-save:hover { opacity:0.9; color:white; }
    .btn-save:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-back { background:#ede7f6; color:#4a0080; border:none; border-radius:8px; padding:11px 20px; font-weight:600; font-size:0.9rem; text-decoration:none; display:inline-block; }
    .pkg-card { border:2px solid #e9d5ff; border-radius:12px; padding:14px 16px; cursor:pointer; transition:all 0.2s; margin-bottom:10px; }
    .pkg-card:hover { border-color:#a78bfa; background:#faf5ff; }
    .pkg-card.selected { border-color:#4a0080; background:#f0e6ff; }
    .pkg-card .pkg-venue { font-weight:700; color:#2d0057; font-size:0.88rem; }
    .pkg-card .pkg-range { font-size:0.75rem; color:#9b59b6; }
    .tier-btn { border:1.5px solid #e9d5ff; border-radius:8px; padding:8px 16px; cursor:pointer; font-size:0.82rem; font-weight:600; color:#4a0080; background:white; transition:all 0.2s; margin:3px; }
    .tier-btn:hover { border-color:#7b2ff7; background:#faf5ff; }
    .tier-btn.selected { border-color:#4a0080; background:#4a0080; color:white; }
    .food-set-card { border:2px solid #e9d5ff; border-radius:12px; padding:14px; cursor:pointer; transition:all 0.2s; height:100%; }
    .food-set-card:hover { border-color:#a78bfa; background:#faf5ff; }
    .food-set-card.selected { border-color:#4a0080; background:#f0e6ff; }
    .food-set-card .fs-badge { background:#4a0080; color:white; font-size:10px; font-weight:700; padding:2px 10px; border-radius:20px; display:inline-block; margin-bottom:8px; }
    .food-set-card ul { list-style:none; padding:0; margin:0; }
    .food-set-card ul li { font-size:11px; color:#374151; padding:2px 0; display:flex; gap:6px; align-items:flex-start; }
    .food-set-card ul li::before { content:'•'; color:#7c3aed; flex-shrink:0; }
    .summary-box { background:linear-gradient(135deg,#2d0057,#4a0080); border-radius:14px; padding:20px; color:white; position:sticky; top:80px; }
    .sum-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid rgba(255,255,255,0.1); font-size:0.82rem; }
    .sum-row .s-label { opacity:0.75; }
    .sum-row .s-val { font-weight:700; text-align:right; max-width:60%; word-break:break-word; }
    .sum-total { font-size:1.5rem; font-weight:800; margin-top:10px; }
    .addon-row { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:0.5px solid #f5f0ff; }
    .addon-row:last-child { border:none; }
    .addon-row input[type=checkbox] { accent-color:#7b2ff7; width:15px; height:15px; flex-shrink:0; }
    .addon-row label { font-size:0.82rem; color:#374151; flex:1; cursor:pointer; margin:0; }
    .addon-row .addon-price { font-size:0.78rem; font-weight:700; color:#4a0080; white-space:nowrap; }
    .conflict-warning { background:#fef3c7; border:2px solid #f59e0b; border-radius:10px; padding:12px 16px; font-size:0.82rem; color:#92400e; display:none; margin-top:8px; }
    .conflict-warning.show { display:block; }

    /* Selection required error for card-pickers */
    .selection-error { color:#ef4444; font-size:0.74rem; font-weight:600; margin-top:6px; display:none; }
    .selection-error.show { display:block; }

    /* Qty validation */
    .qty-input.is-invalid { border-color: #ef4444 !important; }

    /* Keep the booking flow in the intended order without changing any form fields. */
    #walkInMainColumn { display:flex; flex-direction:column; }
    #guestInfoCard { order:1; }
    #eventDetailsCard { order:2; }
    #eventPackageCard { order:3; }
    #additionalChargesCard { order:4; }
</style>

<script id="eventsJson" type="application/json">
    {!! json_encode($eventsData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-walking me-2"></i> Walk-in Booking</h2>
    <a href="{{ route('staff.guest-bookings.index') }}" class="btn-back">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3" style="border-radius:10px;">
    <strong>Please fix the following:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('staff.walk-in.store') }}" method="POST" id="walkInForm" novalidate>
@csrf
<input type="hidden" name="event_id"      id="eventIdInput">
<input type="hidden" name="package_id"    id="packageIdInput">
<input type="hidden" name="price_per_pax" id="priceInput">
<input type="hidden" name="food_set"      id="foodSetInput">
<input type="hidden" name="addon_total"   id="addonTotalInput" value="0">

<div class="row g-4">
<div class="col-lg-8" id="walkInMainColumn">

    {{-- ══ GUEST INFO ══ --}}
    <div class="form-card" id="guestInfoCard">
        <div class="section-label"><i class="fas fa-user me-1"></i> Guest Information</div>
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="guest_name" id="guestName" class="form-control"
                       placeholder="Guest full name"
                       value="{{ old('guest_name') }}"
                       minlength="2" maxlength="100" required>
                <div class="field-error" id="err-guestName">Please enter the guest's full name (at least 2 characters).</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="guest_phone" id="guestPhone" class="form-control"
                       placeholder="09XX-XXX-XXXX"
                       value="{{ old('guest_phone') }}"
                       maxlength="15" required>
                <div class="field-error" id="err-guestPhone">Please enter a valid PH phone number (e.g. 09171234567).</div>
            </div>

            <div class="col-12">
                <label class="form-label">Email Address <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                <input type="email" name="guest_email" id="guestEmail" class="form-control"
                       placeholder="guest@email.com"
                       value="{{ old('guest_email') }}"
                       maxlength="150">
                <div class="field-error" id="err-guestEmail">Please enter a valid email address.</div>
            </div>

        </div>
    </div>

    {{-- ══ EVENT & PACKAGE ══ --}}
    <div class="form-card" id="eventPackageCard">
        <div class="section-label"><i class="fas fa-calendar-alt me-1"></i> Event & Package</div>
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="form-label">Event Type <span class="text-danger">*</span></label>
                <select class="form-select" id="eventSelect" required>
                    <option value="">— Select Event —</option>
                    @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
                <div class="field-error" id="err-eventSelect">Please select an event type.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Celebrant / Couple Name <span class="text-danger">*</span></label>
                <input type="text" name="celebrant_name" id="celebrantName" class="form-control"
                       placeholder="e.g. Maria Santos"
                       value="{{ old('celebrant_name') }}"
                       minlength="2" maxlength="100" required>
                <div class="field-error" id="err-celebrantName">Please enter the celebrant or couple name.</div>
            </div>

        </div>

        <div id="packagesWrap" style="display:none;">
            <label class="form-label">Select Package / Venue <span class="text-danger">*</span></label>
            <div id="packagesList"></div>
            <div class="selection-error" id="err-package">Please select a package / venue.</div>
        </div>

        <div id="tiersWrap" style="display:none;margin-top:14px;">
            <label class="form-label">Price per Person <span class="text-danger">*</span></label>
            <div id="tiersList"></div>
            <div class="selection-error" id="err-tier">Please select a price tier.</div>
        </div>

        <div id="foodSetsWrap" style="display:none;margin-top:14px;">
            <label class="form-label">Select Food Set <span class="text-danger">*</span></label>
            <div class="row g-2" id="foodSetsList"></div>
            <div class="selection-error" id="err-foodSet">Please select a food set.</div>
        </div>

        <div id="choicesWrap" style="display:none;margin-top:14px;">
            <div class="row g-3">
                <div class="col-md-4" id="soupWrap">
                    <label class="form-label">Choice of Soup <span class="text-danger">*</span></label>
                    <select name="soup_choice" id="soupSelect" class="form-select">
                        <option value="">-- Select --</option>
                    </select>
                    <div class="field-error" id="err-soupSelect">Please choose a soup.</div>
                </div>
                <div class="col-md-4" id="dessertWrap">
                    <label class="form-label">Choice of Dessert <span class="text-danger">*</span></label>
                    <select name="dessert_choice" id="dessertSelect" class="form-select">
                        <option value="">-- Select --</option>
                    </select>
                    <div class="field-error" id="err-dessertSelect">Please choose a dessert.</div>
                </div>
                <div class="col-md-4" id="drinkWrap">
                    <label class="form-label">Choice of Drink <span class="text-danger">*</span></label>
                    <select name="drink_choice" id="drinkSelect" class="form-select">
                        <option value="">-- Select --</option>
                    </select>
                    <div class="field-error" id="err-drinkSelect">Please choose a drink.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ EVENT DETAILS ══ --}}
    <div class="form-card" id="eventDetailsCard">
        <div class="section-label"><i class="fas fa-calendar-check me-1"></i> Event Details</div>
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Number of Guests <span class="text-danger">*</span></label>
                <input type="number" name="pax_count" id="paxCount" class="form-control"
                       placeholder="e.g. 100"
                       min="1" max="10000"
                       value="{{ old('pax_count') }}" required>
                <div class="field-error" id="err-paxCount">Please enter a valid number of guests (minimum 1).</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                <input type="date" name="event_date" id="eventDate" class="form-control"
                       value="{{ old('event_date') }}" required>
                <div class="field-error" id="err-eventDate">Please select a future event date.</div>
                <div class="conflict-warning" id="conflictWarn">
                    ⚠️ Warning: May existing booking na sa venue at oras na ito.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Event Time <span class="text-danger">*</span></label>
                <select name="event_time_start" id="eventTimeSelect" class="form-select" required>
                    <option value="">— Select Time —</option>
                    @foreach(['08:00'=>'8:00 AM','08:30'=>'8:30 AM','09:00'=>'9:00 AM','09:30'=>'9:30 AM',
                              '10:00'=>'10:00 AM','10:30'=>'10:30 AM','11:00'=>'11:00 AM','11:30'=>'11:30 AM',
                              '12:00'=>'12:00 PM','12:30'=>'12:30 PM','13:00'=>'1:00 PM','13:30'=>'1:30 PM',
                              '14:00'=>'2:00 PM','14:30'=>'2:30 PM','15:00'=>'3:00 PM','15:30'=>'3:30 PM',
                              '16:00'=>'4:00 PM','16:30'=>'4:30 PM','17:00'=>'5:00 PM'] as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="field-error" id="err-eventTimeSelect">Please select an event time.</div>
            </div>

            <div class="col-12">
                <label class="form-label">Special Requests <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                <textarea name="special_requests" id="specialRequests" class="form-control"
                          rows="2" maxlength="500"
                          placeholder="Any special requests...">{{ old('special_requests') }}</textarea>
                <div style="font-size:0.72rem;color:#9ca3af;margin-top:3px;text-align:right;">
                    <span id="reqCharCount">0</span>/500
                </div>
            </div>

        </div>
    </div>

    {{-- ══ ADD-ONS ══ --}}
    <div class="form-card" id="additionalChargesCard">
        <div class="section-label"><i class="fas fa-plus-circle me-1"></i> Additional Charges <span style="font-weight:400;color:#9ca3af;">(optional)</span></div>
        @php $addons = [
            ['key'=>'grazing_table',   'label'=>'Grazing Table',                        'price'=>10000,'type'=>'fixed'],
            ['key'=>'upgraded_setup',  'label'=>'Upgraded Set Up (Couple Minds Events)', 'price'=>15000,'type'=>'fixed'],
            ['key'=>'led_wall',        'label'=>'LED Wall',                             'price'=>15000,'type'=>'fixed'],
            ['key'=>'sweet_buffet',    'label'=>'Sweet Buffet / Fruits',                'price'=>5000, 'type'=>'fixed'],
            ['key'=>'shabu_station',   'label'=>'Shabu-Shabu Station (per pax)',        'price'=>120,  'type'=>'pax'],
            ['key'=>'food_cart',       'label'=>'Food Cart',                            'price'=>3500, 'type'=>'fixed'],
            ['key'=>'full_band',       'label'=>'Full Band',                            'price'=>15000,'type'=>'fixed'],
            ['key'=>'outside_stylist', 'label'=>'Outside Stylist',                      'price'=>5000, 'type'=>'fixed'],
            ['key'=>'photography',     'label'=>'Photography',                          'price'=>8000, 'type'=>'fixed'],
            ['key'=>'photo_booth',     'label'=>'Photo Booth',                          'price'=>5000, 'type'=>'fixed'],
            ['key'=>'photo_booth_elec','label'=>'Photo Booth Electricity Charge',       'price'=>1000, 'type'=>'fixed'],
            ['key'=>'exceeding_hour',  'label'=>'Exceeding Hour',                       'price'=>3000, 'type'=>'fixed'],
            ['key'=>'lechon_baboy',    'label'=>'Lechon Baboy',                         'price'=>8000, 'type'=>'qty'],
            ['key'=>'liquor_bottle',   'label'=>'Liquor (per Bottle)',                  'price'=>500,  'type'=>'qty'],
            ['key'=>'liquor_case',     'label'=>'Liquor (per Case)',                    'price'=>5000, 'type'=>'qty'],
        ]; @endphp
        @foreach($addons as $a)
        <div class="addon-row">
            <input type="checkbox" name="addons[{{ $a['key'] }}][selected]" id="a-{{ $a['key'] }}" value="1"
                   data-price="{{ $a['price'] }}" data-type="{{ $a['type'] }}" data-key="{{ $a['key'] }}">
            <label for="a-{{ $a['key'] }}">{{ $a['label'] }}</label>
            <span class="addon-price">₱{{ number_format($a['price']) }}</span>
            @if($a['type']==='qty')
            <input type="number" name="addons[{{ $a['key'] }}][qty]" value="1" min="1" max="999"
                   id="qty-{{ $a['key'] }}"
                   class="qty-input"
                   style="width:55px;border:1.5px solid #e9d5ff;border-radius:6px;padding:4px 7px;font-size:12px;display:none;">
            @endif
            <input type="hidden" name="addons[{{ $a['key'] }}][price]" value="{{ $a['price'] }}">
        </div>
        @endforeach
    </div>

</div>

{{-- ══ SUMMARY ══ --}}
<div class="col-lg-4">
    <div class="summary-box">
        <div style="font-size:0.75rem;opacity:0.7;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Booking Summary</div>
        <div class="sum-row"><span class="s-label">Event</span><span class="s-val" id="sumEvent">—</span></div>
        <div class="sum-row"><span class="s-label">Venue</span><span class="s-val" id="sumVenue">—</span></div>
        <div class="sum-row"><span class="s-label">Food Set</span><span class="s-val" id="sumFoodSet">—</span></div>
        <div class="sum-row"><span class="s-label">Price/pax</span><span class="s-val" id="sumPpax">—</span></div>
        <div class="sum-row"><span class="s-label">Guests</span><span class="s-val" id="sumPax">0</span></div>
        <div class="sum-row"><span class="s-label">Package Total</span><span class="s-val" id="sumPkgTotal">₱0.00</span></div>
        <div class="sum-row"><span class="s-label">Add-ons</span><span class="s-val" id="sumAddons">₱0.00</span></div>
        <div style="margin-top:12px;">
            <div style="font-size:0.72rem;opacity:0.7;">Estimated Total</div>
            <div class="sum-total" id="sumTotal">₱0.00</div>
        </div>
        <div style="margin-top:16px;">
            <button type="submit" id="submitBtn" class="btn-save w-100">
                <i class="fas fa-save me-2"></i> Save Walk-in Booking
            </button>
        </div>
        <div style="margin-top:10px;font-size:0.72rem;opacity:0.6;text-align:center;">
            Booking will be saved as Pending
        </div>
    </div>
</div>

</div>
</form>

<script>
var eventsData   = JSON.parse(document.getElementById('eventsJson').textContent);
var selPrice     = 0;
var selPkg       = null;
var currentPkgId = null;
var currentPrice = null;

// ══════════════════════════════════════════════════════
// VALIDATION HELPERS
// ══════════════════════════════════════════════════════
function setValid(el) {
    el.classList.remove('is-invalid');
    el.classList.add('is-valid');
}
function setInvalid(el) {
    el.classList.remove('is-valid');
    el.classList.add('is-invalid');
}
function clearState(el) {
    el.classList.remove('is-valid', 'is-invalid');
}
function showErr(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('show');
}
function hideErr(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('show');
}
function showSelErr(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('show');
}
function hideSelErr(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('show');
}

// ── Phone validation (PH numbers) ───────────────────
function isValidPhone(val) {
    return /^(09|\+639)\d{9}$/.test(val.replace(/[-\s]/g, ''));
}

// ── Email validation ─────────────────────────────────
function isValidEmail(val) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
}

// ── Is future date? ──────────────────────────────────
function isFutureDate(val) {
    if (!val) return false;
    var today = new Date(); today.setHours(0,0,0,0);
    return new Date(val) >= today;
}

// ══════════════════════════════════════════════════════
// REAL-TIME FIELD VALIDATORS
// ══════════════════════════════════════════════════════

// Guest Name
var guestNameEl = document.getElementById('guestName');
guestNameEl.addEventListener('input', function() { validateGuestName(true); });
guestNameEl.addEventListener('blur',  function() { validateGuestName(true); });
function validateGuestName(show) {
    var val = guestNameEl.value.trim();
    if (val.length >= 2) { setValid(guestNameEl); hideErr('err-guestName'); return true; }
    if (show || val.length > 0) { setInvalid(guestNameEl); showErr('err-guestName'); }
    return false;
}

// Phone
var guestPhoneEl = document.getElementById('guestPhone');
guestPhoneEl.addEventListener('input', function() { validatePhone(true); });
guestPhoneEl.addEventListener('blur',  function() { validatePhone(true); });
function validatePhone(show) {
    var val = guestPhoneEl.value.trim();
    if (val && isValidPhone(val)) { setValid(guestPhoneEl); hideErr('err-guestPhone'); return true; }
    if (show || val.length > 0) { setInvalid(guestPhoneEl); showErr('err-guestPhone'); }
    return false;
}

// Email (optional — only validate if filled)
var guestEmailEl = document.getElementById('guestEmail');
guestEmailEl.addEventListener('input', function() { validateEmail(true); });
guestEmailEl.addEventListener('blur',  function() { validateEmail(true); });
function validateEmail(show) {
    var val = guestEmailEl.value.trim();
    if (!val) { clearState(guestEmailEl); hideErr('err-guestEmail'); return true; } // optional
    if (isValidEmail(val)) { setValid(guestEmailEl); hideErr('err-guestEmail'); return true; }
    if (show) { setInvalid(guestEmailEl); showErr('err-guestEmail'); }
    return false;
}

// Event select
var eventSelectEl = document.getElementById('eventSelect');
eventSelectEl.addEventListener('change', function() {
    validateEventSelect();
    loadPackages(this.value);
});
function validateEventSelect() {
    if (eventSelectEl.value) { setValid(eventSelectEl); hideErr('err-eventSelect'); return true; }
    setInvalid(eventSelectEl); showErr('err-eventSelect');
    return false;
}

// Celebrant name
var celebrantEl = document.getElementById('celebrantName');
celebrantEl.addEventListener('input', function() { validateCelebrant(true); });
celebrantEl.addEventListener('blur',  function() { validateCelebrant(true); });
function validateCelebrant(show) {
    var val = celebrantEl.value.trim();
    if (val.length >= 2) { setValid(celebrantEl); hideErr('err-celebrantName'); return true; }
    if (show || val.length > 0) { setInvalid(celebrantEl); showErr('err-celebrantName'); }
    return false;
}

// Pax count
var paxEl = document.getElementById('paxCount');
paxEl.addEventListener('input', function() { validatePax(true); calcTotal(); });
paxEl.addEventListener('blur',  function() { validatePax(true); });
function validatePax(show) {
    var val = parseInt(paxEl.value);
    if (val >= 1 && val <= 10000) { setValid(paxEl); hideErr('err-paxCount'); return true; }
    if (show || paxEl.value.length > 0) { setInvalid(paxEl); showErr('err-paxCount'); }
    return false;
}

// Event date
var eventDateEl = document.getElementById('eventDate');
eventDateEl.addEventListener('change', function() { validateDate(); checkConflict(); });
function validateDate() {
    if (isFutureDate(eventDateEl.value)) { setValid(eventDateEl); hideErr('err-eventDate'); return true; }
    setInvalid(eventDateEl); showErr('err-eventDate');
    return false;
}
// Set min date to today
(function() {
    var today = new Date();
    var yyyy  = today.getFullYear();
    var mm    = String(today.getMonth()+1).padStart(2,'0');
    var dd    = String(today.getDate()).padStart(2,'0');
    eventDateEl.min = yyyy + '-' + mm + '-' + dd;
})();

// Event time
var eventTimeEl = document.getElementById('eventTimeSelect');
eventTimeEl.addEventListener('change', function() { validateTime(); checkConflict(); });
function validateTime() {
    if (eventTimeEl.value) { setValid(eventTimeEl); hideErr('err-eventTimeSelect'); return true; }
    setInvalid(eventTimeEl); showErr('err-eventTimeSelect');
    return false;
}

// Special requests char counter
var reqEl = document.getElementById('specialRequests');
reqEl.addEventListener('input', function() {
    document.getElementById('reqCharCount').textContent = this.value.length;
});

// Choice dropdowns
['soupSelect','dessertSelect','drinkSelect'].forEach(function(id) {
    var el = document.getElementById(id);
    el.addEventListener('change', function() {
        var wrap = document.getElementById(id.replace('Select','Wrap'));
        if (wrap && wrap.style.display !== 'none') {
            if (el.value) { setValid(el); hideErr('err-' + id); }
            else          { setInvalid(el); showErr('err-' + id); }
        }
    });
});

// Qty inputs
document.querySelectorAll('.qty-input').forEach(function(el) {
    el.addEventListener('input', function() {
        var val = parseInt(this.value);
        if (val >= 1 && val <= 999) { this.classList.remove('is-invalid'); }
        else                        { this.classList.add('is-invalid'); }
        calcTotal();
    });
});

// ══════════════════════════════════════════════════════
// PACKAGE / TIER / FOOD SET LOGIC
// ══════════════════════════════════════════════════════
function loadPackages(eventId) {
    document.getElementById('eventIdInput').value   = eventId;
    document.getElementById('packageIdInput').value = '';
    document.getElementById('priceInput').value     = '';
    document.getElementById('foodSetInput').value   = '';
    selPrice = 0; selPkg = null; currentPkgId = null; currentPrice = null;

    var event = eventsData[eventId];
    if (!event) return;

    document.getElementById('sumEvent').textContent   = event.name;
    document.getElementById('sumVenue').textContent   = '—';
    document.getElementById('sumFoodSet').textContent = '—';
    document.getElementById('sumPpax').textContent    = '—';

    var list = document.getElementById('packagesList');
    list.innerHTML = '';
    event.packages.forEach(function(pkg) {
        var div = document.createElement('div');
        div.className = 'pkg-card';
        div.id = 'pkg-' + pkg.id;
        div.innerHTML = '<div class="pkg-venue">' + pkg.venueName + '</div>' +
                        '<div class="pkg-range"><i class="fas fa-users me-1"></i>' + pkg.paxRange + ' pax</div>';
        div.addEventListener('click', function() {
            selectPackage(pkg);
            hideSelErr('err-package');
        });
        list.appendChild(div);
    });

    show('packagesWrap');
    hide('tiersWrap');
    hide('foodSetsWrap');
    hide('choicesWrap');
    calcTotal();
}

function selectPackage(pkg) {
    selPkg = pkg; selPrice = 0; currentPkgId = pkg.id; currentPrice = null;
    document.getElementById('packageIdInput').value   = pkg.id;
    document.getElementById('priceInput').value       = '';
    document.getElementById('foodSetInput').value     = '';
    document.getElementById('sumVenue').textContent   = pkg.venueName;
    document.getElementById('sumFoodSet').textContent = '—';
    document.getElementById('sumPpax').textContent    = '—';

    document.querySelectorAll('.pkg-card').forEach(function(c) { c.classList.remove('selected'); });
    document.getElementById('pkg-' + pkg.id).classList.add('selected');
    hideSelErr('err-package');

    var tiersList = document.getElementById('tiersList');
    tiersList.innerHTML = '';
    var tiers = pkg.tiers || {};
    Object.keys(tiers).forEach(function(price) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tier-btn';
        btn.textContent = '₱' + Number(price).toLocaleString() + '/pax';
        btn.addEventListener('click', function() {
            selectTier(pkg, price, this);
            hideSelErr('err-tier');
        });
        tiersList.appendChild(btn);
    });

    show('tiersWrap');
    hide('foodSetsWrap');
    hide('choicesWrap');
    calcTotal();
}

function selectTier(pkg, price, btn) {
    selPrice = Number(price); currentPrice = price;
    document.getElementById('priceInput').value       = price;
    document.getElementById('foodSetInput').value     = '';
    document.getElementById('sumPpax').textContent    = '₱' + Number(price).toLocaleString() + '/pax';
    document.getElementById('sumFoodSet').textContent = '—';

    document.querySelectorAll('.tier-btn').forEach(function(b) { b.classList.remove('selected'); });
    btn.classList.add('selected');
    hideSelErr('err-tier');

    var tiers = pkg.tiers || {};
    var sets  = tiers[price] || {};
    var foodSetsList = document.getElementById('foodSetsList');
    foodSetsList.innerHTML = '';

    Object.keys(sets).forEach(function(setKey) {
        var setData = sets[setKey];
        var items   = setData.items || [];
        var col  = document.createElement('div');
        col.className = 'col-md-6 mb-2';
        var card = document.createElement('div');
        card.className = 'food-set-card';
        card.id = 'fscard-' + setKey;
        card.dataset.setkey = setKey;
        var itemsHtml = items.map(function(i) { return '<li>' + i + '</li>'; }).join('');
        card.innerHTML = '<div class="fs-badge">Set ' + setKey + '</div><ul>' + itemsHtml + '</ul>';
        (function(sk, sd) {
            card.addEventListener('click', function() {
                onFoodSetClick(sk, sd);
                hideSelErr('err-foodSet');
            });
        })(setKey, setData);
        col.appendChild(card);
        foodSetsList.appendChild(col);
    });

    if (Object.keys(sets).length > 0) { show('foodSetsWrap'); } else { hide('foodSetsWrap'); }
    hide('choicesWrap');
    calcTotal();
}

function onFoodSetClick(setKey, setData) {
    document.getElementById('foodSetInput').value     = setKey;
    document.getElementById('sumFoodSet').textContent = 'Set ' + setKey;

    document.querySelectorAll('.food-set-card').forEach(function(c) { c.classList.remove('selected'); });
    var card = document.getElementById('fscard-' + setKey);
    if (card) card.classList.add('selected');
    hideSelErr('err-foodSet');

    var soups    = setData.soup_choices    || [];
    var desserts = setData.dessert_choices || [];
    var drinks   = setData.drink_choices   || [];

    fillSelect('soupSelect',    soups);
    fillSelect('dessertSelect', desserts);
    fillSelect('drinkSelect',   drinks);

    document.getElementById('soupWrap').style.display    = soups.length    ? '' : 'none';
    document.getElementById('dessertWrap').style.display = desserts.length ? '' : 'none';
    document.getElementById('drinkWrap').style.display   = drinks.length   ? '' : 'none';

    // Clear validation states on choice dropdowns when food set changes
    ['soupSelect','dessertSelect','drinkSelect'].forEach(function(id) {
        clearState(document.getElementById(id));
        hideErr('err-' + id);
    });

    if (soups.length || desserts.length || drinks.length) { show('choicesWrap'); }
    else { hide('choicesWrap'); }
}

function fillSelect(selectId, options) {
    var sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">-- Select --</option>';
    options.forEach(function(opt) {
        var o = document.createElement('option');
        o.value = opt; o.textContent = opt;
        sel.appendChild(o);
    });
}

// ══════════════════════════════════════════════════════
// ADD-ONS
// ══════════════════════════════════════════════════════
document.querySelectorAll('.addon-row input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var key   = this.dataset.key;
        var qtyEl = document.getElementById('qty-' + key);
        if (qtyEl) {
            qtyEl.style.display = this.checked ? 'inline-block' : 'none';
            if (!this.checked) qtyEl.classList.remove('is-invalid');
        }
        calcTotal();
    });
});

// ══════════════════════════════════════════════════════
// CALC TOTAL
// ══════════════════════════════════════════════════════
function calcTotal() {
    var pax      = parseInt(paxEl.value) || 0;
    var pkgTotal = selPrice * pax;
    var addonTotal = 0;

    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(function(cb) {
        var price = parseInt(cb.dataset.price);
        var type  = cb.dataset.type;
        var key   = cb.dataset.key;
        if (type === 'pax') {
            addonTotal += price * pax;
        } else if (type === 'qty') {
            var qtyEl2 = document.getElementById('qty-' + key);
            addonTotal += price * (parseInt(qtyEl2 ? qtyEl2.value : 1) || 1);
        } else {
            addonTotal += price;
        }
    });

    var grand = pkgTotal + addonTotal;
    document.getElementById('sumPax').textContent      = pax;
    document.getElementById('sumPkgTotal').textContent = '₱' + pkgTotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('sumAddons').textContent   = '₱' + addonTotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('sumTotal').textContent    = '₱' + grand.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('addonTotalInput').value   = addonTotal;
}

// ══════════════════════════════════════════════════════
// CONFLICT CHECK
// ══════════════════════════════════════════════════════
function checkConflict() {
    var date  = eventDateEl.value;
    var time  = eventTimeEl.value;
    var pkgId = document.getElementById('packageIdInput').value;
    if (!date || !pkgId) return;
    fetch('/staff/walk-in/check-conflict?date=' + date + '&time=' + time + '&package_id=' + pkgId)
        .then(function(r) { return r.json(); })
        .then(function(d) {
            document.getElementById('conflictWarn').classList.toggle('show', d.conflict);
        }).catch(function(){});
}

// ══════════════════════════════════════════════════════
// FORM SUBMIT — FULL VALIDATION
// ══════════════════════════════════════════════════════
document.getElementById('walkInForm').addEventListener('submit', function(e) {
    var valid = true;

    // Text fields
    if (!validateGuestName(true))  valid = false;
    if (!validatePhone(true))      valid = false;
    if (!validateEmail(true))      valid = false;
    if (!validateCelebrant(true))  valid = false;
    if (!validatePax(true))        valid = false;
    if (!validateDate())           valid = false;
    if (!validateTime())           valid = false;

    // Event select
    if (!validateEventSelect())    valid = false;

    // Package selection
    if (!document.getElementById('packageIdInput').value) {
        showSelErr('err-package'); valid = false;
    }

    // Tier / price selection
    if (!document.getElementById('priceInput').value) {
        showSelErr('err-tier'); valid = false;
    }

    // Food set selection
    if (document.getElementById('foodSetsWrap').style.display !== 'none' &&
        !document.getElementById('foodSetInput').value) {
        showSelErr('err-foodSet'); valid = false;
    }

    // Choice dropdowns (only validate visible ones)
    ['soupSelect','dessertSelect','drinkSelect'].forEach(function(id) {
        var wrap = document.getElementById(id.replace('Select','Wrap'));
        var el   = document.getElementById(id);
        if (wrap && wrap.style.display !== 'none' && !el.value) {
            setInvalid(el); showErr('err-' + id); valid = false;
        }
    });

    // Qty inputs for checked add-ons
    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(function(cb) {
        if (cb.dataset.type === 'qty') {
            var qtyEl = document.getElementById('qty-' + cb.dataset.key);
            if (qtyEl) {
                var v = parseInt(qtyEl.value);
                if (!v || v < 1) { qtyEl.classList.add('is-invalid'); valid = false; }
            }
        }
    });

    if (!valid) {
        e.preventDefault();
        // Scroll to first error
        var firstErr = document.querySelector('.is-invalid, .selection-error.show, .field-error.show');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// ── Helpers ──────────────────────────────────────────
function show(id) { document.getElementById(id).style.display = 'block'; }
function hide(id) { document.getElementById(id).style.display = 'none'; }
</script>
@endsection
