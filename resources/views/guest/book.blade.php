<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Book an Event — The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #f5f5f5; }
        .gl-nav { display: flex; align-items: center; justify-content: space-between; padding: 12px 32px; background: #2d0057; position: sticky; top: 0; z-index: 300; }
        .gl-nav .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .gl-nav .logo-img { width: 34px; height: 34px; object-fit: contain; }
        .gl-nav .brand-name { font-family: 'Cormorant Garamond',serif; font-size: 15px; font-weight: 600; color: #f0e6ff; }
        .gl-nav .brand-sub { font-size: 8px; color: #a78bfa; letter-spacing: 2px; text-transform: uppercase; }
        .back-link { color: rgba(255,255,255,0.5); font-size: 12px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .back-link:hover { color: white; }
        .page-wrap { max-width: 1140px; margin: 0 auto; padding: 24px 16px; display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
        @media (max-width: 900px) { .page-wrap { grid-template-columns: 1fr; } .sticky-summary { position: relative !important; top: auto !important; } }

        /* Step indicator */
        .step-bar { display: flex; align-items: center; gap: 0; margin-bottom: 20px; }
        .step-item { display: flex; align-items: center; gap: 8px; flex: 1; }
        .step-item:last-child { flex: none; }
        .step-circle { width: 28px; height: 28px; border-radius: 50%; background: #e9d5ff; color: #4a0080; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .step-circle.active { background: #4a0080; color: white; }
        .step-circle.done { background: #10b981; color: white; }
        .step-label { font-size: 11px; font-weight: 600; color: #9ca3af; white-space: nowrap; }
        .step-label.active { color: #4a0080; }
        .step-divider { flex: 1; height: 2px; background: #e9d5ff; margin: 0 8px; }

        .pkg-section { background: white; border-radius: 10px; margin-bottom: 16px; overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,0.07); }
        .pkg-section-header { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
        .pkg-section-header .pkg-title { font-family: 'Cormorant Garamond',serif; font-size: 1.15rem; color: #2d0057; font-weight: 700; }
        .pkg-section-header .pkg-sub { font-size: 11px; color: #9ca3af; }
        .pkg-pax-badge { background: #f0e6ff; color: #4a0080; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .inclusions-wrap { padding: 14px 20px; background: #faf5ff; border-bottom: 1px solid #f0f0f0; }
        .inclusions-wrap .inc-title { font-size: 11px; font-weight: 700; color: #6b21a8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .inc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; }
        .inc-item { font-size: 12px; color: #374151; display: flex; gap: 7px; align-items: flex-start; padding: 3px 0; }
        .inc-item i { color: #7c3aed; font-size: 10px; margin-top: 3px; flex-shrink: 0; }
        @media (max-width: 576px) { .inc-grid { grid-template-columns: 1fr; } }
        .price-tiers { padding: 16px 20px; }
        .price-tier-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border: 1.5px solid #e9d5ff; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; background: white; }
        .price-tier-row:hover { border-color: #7c3aed; background: #faf5ff; }
        .price-tier-row.selected { border-color: #4a0080; background: #f0e6ff; }
        .price-tier-row .tier-price { font-size: 1.2rem; font-weight: 800; color: #4a0080; }
        .price-tier-row .tier-label { font-size: 11px; color: #9ca3af; }
        .select-btn { background: #4a0080; color: white; border: none; border-radius: 6px; padding: 7px 18px; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; }
        .select-btn:hover { background: #3b0764; }
        .food-sets-wrap { padding: 14px 20px; border-top: 1px solid #f0f0f0; display: none; }
        .food-set-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        @media (max-width: 576px) { .food-set-grid { grid-template-columns: repeat(2,1fr); } }
        .food-set-card { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 10px; cursor: pointer; transition: all 0.2s; }
        .food-set-card:hover { border-color: #7c3aed; }
        .food-set-card.selected { border-color: #4a0080; background: #f0e6ff; }
        .food-set-card .fs-badge { background: #4a0080; color: white; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px; }
        .food-set-card ul { list-style: none; padding: 0; margin: 0; }
        .food-set-card ul li { font-size: 10px; color: #374151; padding: 1px 0; }
        .choices-wrap { padding: 12px 20px; border-top: 1px solid #f0f0f0; background: #fffbff; display: none; }
        .details-section { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 1px 8px rgba(0,0,0,0.07); margin-bottom: 16px; }
        .details-section h6 { font-size: 13px; font-weight: 700; color: #2d0057; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; }
        .form-control { border: 1.5px solid #e9d5ff; border-radius: 7px; font-size: 13px; padding: 9px 13px; transition: border-color 0.2s; }
        .form-control:focus { border-color: #7b2ff7; box-shadow: none; }
        .form-control.is-invalid { border-color: #dc2626; background-color: #fff5f5; }
        .form-control.is-valid { border-color: #10b981; }
        .form-label { font-size: 11px; font-weight: 700; color: #6b21a8; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        .field-error { font-size: 11px; color: #dc2626; margin-top: 4px; display: none; align-items: center; gap: 4px; }
        .field-error.show { display: flex; }
        .addon-row { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 0.5px solid #f5f0ff; }
        .addon-row:last-child { border: none; }
        .addon-row input[type=checkbox] { accent-color: #7b2ff7; width: 15px; height: 15px; flex-shrink: 0; }
        .addon-row label { font-size: 12px; color: #374151; flex: 1; cursor: pointer; margin: 0; }
        .addon-row .addon-price { font-size: 11px; font-weight: 700; color: #4a0080; white-space: nowrap; }
        .addon-row input[type=number] { width: 60px; border: 1.5px solid #e9d5ff; border-radius: 6px; padding: 4px 7px; font-size: 12px; }
        .sticky-summary { position: sticky; top: 68px; }
        .summary-box { background: white; border-radius: 10px; box-shadow: 0 1px 8px rgba(0,0,0,0.1); overflow: hidden; }
        .summary-box .sum-header { background: linear-gradient(135deg,#2d0057,#4a0080); color: white; padding: 14px 18px; font-family: 'Cormorant Garamond',serif; font-size: 1.1rem; }
        .summary-box .sum-body { padding: 16px 18px; }
        .sum-row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 0.5px solid #f0f0f0; font-size: 12px; }
        .sum-row:last-child { border: none; }
        .sum-row .s-label { color: #9ca3af; }
        .sum-row .s-val { font-weight: 600; color: #2d0057; text-align: right; }
        .sum-total-box { background: #f0e6ff; border-radius: 8px; padding: 12px 14px; margin-top: 12px; }
        .sum-total-box .total-label { font-size: 10px; color: #9b59b6; text-transform: uppercase; letter-spacing: 1px; }
        .sum-total-box .total-val { font-size: 1.4rem; font-weight: 800; color: #4a0080; }
        .sum-note { font-size: 10px; color: #9ca3af; margin-top: 6px; }
        .btn-submit { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 13px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; margin-top: 12px; }
        .btn-submit:hover { opacity: 0.9; }
        .pax-hint { font-size: 11px; margin-top: 5px; display: none; align-items: center; gap: 4px; }
        .pax-hint.show { display: flex; }
        .pax-hint.error { color: #dc2626; }
        .pax-hint.success { color: #10b981; }
        .pax-hint.info { color: #6b21a8; }
        .no-pkg-warning { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 7px; padding: 10px 13px; font-size: 12px; color: #92400e; margin-top: 6px; display: none; }
        .no-pkg-warning.show { display: block; }

        /* Section number badge */
        .sec-num { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #4a0080; color: white; border-radius: 50%; font-size: 11px; font-weight: 700; margin-right: 8px; flex-shrink: 0; }
    </style>
</head>
<body>

<nav class="gl-nav">
    <a class="brand" href="/">
        <img src="{{ asset('images/logo.png') }}" class="logo-img" alt="Logo">
        <div><div class="brand-name">The Grand Lourds Hotel</div><div class="brand-sub">Calasiao · Pangasinan</div></div>
    </a>
    <a href="/" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Website</a>
</nav>

<form action="{{ route('guest.book.store') }}" method="POST" id="bookingForm" novalidate>
@csrf
<input type="hidden" name="event_id" id="eventIdInput" value="{{ $selectedEvent->id ?? '' }}">
<input type="hidden" name="package_id" id="packageIdInput">
<input type="hidden" name="price_per_pax" id="priceInput">
<input type="hidden" name="food_set" id="foodSetInput">
<input type="hidden" name="addon_total" id="addonTotalInput" value="0">

<div class="page-wrap">
    <div>
        {{-- Page Title --}}
        <div style="margin-bottom:20px;">
            <h4 style="font-family:'Cormorant Garamond',serif;color:#2d0057;font-size:1.6rem;margin-bottom:4px;">
                Book — {{ $selectedEvent->name }}
            </h4>
            <p style="color:#9ca3af;font-size:12px;">Fill in your details and choose your preferred package below.</p>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- SECTION 1: YOUR DETAILS                               --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="details-section">
            <h6><span class="sec-num">1</span><i class="fas fa-user me-2" style="color:#7b2ff7;"></i> Your Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="guest_name" id="guestName" class="form-control"
                           placeholder="Enter your full name" required
                           oninput="clearErr(this, 'nameError')">
                    <div class="field-error" id="nameError"><i class="fas fa-exclamation-circle"></i> Please enter your full name.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" name="guest_phone" id="guestPhone" class="form-control"
                           placeholder="09XX-XXX-XXXX" required maxlength="11"
                           oninput="this.value=this.value.replace(/[^0-9]/g,''); onPhoneInput(this);">
                    <div class="field-error" id="phoneError"><i class="fas fa-exclamation-circle"></i> Please enter a valid 11-digit PH number starting with 09.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="guest_email" id="guestEmail" class="form-control"
                           placeholder="your@email.com" required
                           oninput="clearErr(this, 'emailError')">
                    <div class="field-error" id="emailError"><i class="fas fa-exclamation-circle"></i> Please enter a valid email address.</div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- SECTION 2: EVENT DETAILS                              --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="details-section">
            <h6><span class="sec-num">2</span><i class="fas fa-calendar me-2" style="color:#7b2ff7;"></i> Event Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name of Celebrant / Couple *</label>
                    <input type="text" name="celebrant_name" id="celebrantName" class="form-control"
                           placeholder="e.g. Maria Santos" required
                           oninput="clearErr(this, 'celebrantError')">
                    <div class="field-error" id="celebrantError"><i class="fas fa-exclamation-circle"></i> Please enter the celebrant or couple's name.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Number of Guests *</label>
                    <input type="number" name="pax_count" id="paxCount" class="form-control"
                           placeholder="e.g. 100" min="1" required
                           oninput="this.value=this.value.replace(/[^0-9]/g,''); validatePax(); calcTotal();"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    <div class="pax-hint info" id="paxInfo"><i class="fas fa-info-circle"></i> <span id="paxInfoText"></span></div>
                    <div class="pax-hint error" id="paxError"><i class="fas fa-exclamation-circle"></i> <span id="paxErrorText"></span></div>
                    <div class="pax-hint success" id="paxSuccess"><i class="fas fa-check-circle"></i> <span id="paxSuccessText"></span></div>
                    <div class="no-pkg-warning" id="noPkgWarning">
                        <i class="fas fa-exclamation-triangle me-1"></i> Please select a package first (below).
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Event Date *</label>
                    <input type="date" name="event_date" id="eventDate" class="form-control"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                           onchange="clearErr(this, 'dateError')">
                    <div class="field-error" id="dateError"><i class="fas fa-exclamation-circle"></i> Please select an event date.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Event Time *</label>
                    <select name="event_time_start" id="eventTime" class="form-control" required
                            onchange="clearErr(this, 'timeError')">
                        <option value="">-- Select Time --</option>
                        <option value="08:00">8:00 AM</option>
                        <option value="08:30">8:30 AM</option>
                        <option value="09:00">9:00 AM</option>
                        <option value="09:30">9:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                    </select>
                    <div class="field-error" id="timeError"><i class="fas fa-exclamation-circle"></i> Please select an event time.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Special Requests <span style="color:#9ca3af;text-transform:none;">(optional)</span></label>
                    <textarea name="special_requests" class="form-control" rows="2" placeholder="Any special requests..."></textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- SECTION 3: PACKAGE & FOOD SET SELECTION               --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div style="margin-bottom:12px;">
            <h5 style="font-family:'Cormorant Garamond',serif;color:#2d0057;font-size:1.2rem;margin-bottom:4px;">
                <span class="sec-num">3</span> Choose Package & Food Set
            </h5>
            <p style="color:#9ca3af;font-size:12px;margin-left:30px;">Select your preferred venue, price tier, and food menu.</p>
        </div>

        @php
            $pkgsQuery = $selectedEvent->packages()->where('is_active', true)->with('venue')->orderBy('pax_min');
            if (!empty($selectedPackageId)) { $pkgsQuery->where('id', $selectedPackageId); }
            $pkgsList = $pkgsQuery->get();
        @endphp

        @forelse($pkgsList as $pkg)
        @php $tiers = $pkg->price_tiers ?? []; @endphp
        <div class="pkg-section">
            <div class="pkg-section-header">
                <div>
                    <div class="pkg-title">{{ $pkg->venue->name }}</div>
                    <div class="pkg-sub">{{ $selectedEvent->name }}</div>
                </div>
                <span class="pkg-pax-badge"><i class="fas fa-users me-1"></i>{{ $pkg->pax_range }} pax</span>
            </div>

            @if(!empty($pkg->amenities))
            <div class="inclusions-wrap">
                <div class="inc-title"><i class="fas fa-check-circle me-1"></i> What's Included</div>
                <div class="inc-grid">
                    @foreach($pkg->amenities as $inc)
                    <div class="inc-item"><i class="fas fa-check"></i> {{ $inc }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="price-tiers">
                <div style="font-size:11px;font-weight:700;color:#6b21a8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Choose Price per Person</div>
                @foreach($tiers as $price => $sets)
                <div class="price-tier-row" id="tier-{{ $pkg->id }}-{{ $price }}"
                     onclick="selectTier({{ $pkg->id }}, {{ $price }}, '{{ addslashes($pkg->venue->name) }}', '{{ addslashes($selectedEvent->name) }}', {{ $selectedEvent->id }}, {{ $pkg->pax_min }}, {{ $pkg->pax_max }})">
                    <div>
                        <div class="tier-price">₱{{ number_format($price) }}<span style="font-size:12px;font-weight:400;color:#9ca3af;">/pax</span></div>
                        <div class="tier-label">Food sets A, B, C, D included</div>
                    </div>
                    <button type="button" class="select-btn" id="tier-btn-{{ $pkg->id }}-{{ $price }}">Select</button>
                </div>
                @endforeach
            </div>

            <div class="food-sets-wrap" id="foodsets-{{ $pkg->id }}">
                <div style="font-size:12px;font-weight:700;color:#2d0057;margin-bottom:10px;"><i class="fas fa-utensils me-1"></i> Choose Food Menu Set</div>
                <div class="food-set-grid" id="fsgrid-{{ $pkg->id }}"></div>
            </div>

            <div class="choices-wrap" id="choices-{{ $pkg->id }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Choice of Soup *</label>
                        <select name="soup_choice" id="soupChoice" class="form-control" style="font-size:12px;" onchange="clearErr(this, 'soupError')">
                            <option value="">-- Select --</option>
                            <option>Cream of Mushroom</option>
                            <option>Cream of Crab Meat</option>
                            <option>Sweet Corn Soup</option>
                            <option>Pumpkin Soup</option>
                            <option>Nido Soup with Quail Egg</option>
                        </select>
                        <div class="field-error" id="soupError"><i class="fas fa-exclamation-circle"></i> Please select your choice of soup.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Choice of Dessert *</label>
                        <select name="dessert_choice" id="dessertChoice" class="form-control" style="font-size:12px;" onchange="clearErr(this, 'dessertError')">
                            <option value="">-- Select --</option>
                            <option>Fruit Salad</option>
                            <option>Buko Pandan Salad</option>
                            <option>Almond Lychee Jelly</option>
                            <option>Coffee Jelly</option>
                            <option>Butchi (Classic, Ube, Cheese, or Lotus Peanut Filling)</option>
                        </select>
                        <div class="field-error" id="dessertError"><i class="fas fa-exclamation-circle"></i> Please select your choice of dessert.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Choice of Drink *</label>
                        <select name="drink_choice" id="drinkChoice" class="form-control" style="font-size:12px;" onchange="clearErr(this, 'drinkError')">
                            <option value="">-- Select --</option>
                            <option>Glass of Coke</option>
                            <option>Glass of Iced Tea</option>
                            <option>Glass of Blue Lemonade</option>
                            <option>Glass of Cucumber Juice</option>
                            <option>Glass of Pink Lemonade</option>
                        </select>
                        <div class="field-error" id="drinkError"><i class="fas fa-exclamation-circle"></i> Please select your choice of drink.</div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="background:white;border-radius:10px;padding:30px;text-align:center;color:#9ca3af;">
            <i class="fas fa-box-open fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
            No packages available for this event yet.
        </div>
        @endforelse

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- SECTION 4: ADDITIONAL CHARGES                         --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="details-section">
            <h6><span class="sec-num">4</span><i class="fas fa-plus-circle me-2" style="color:#7b2ff7;"></i> Additional Charges / Corkage Fee <span style="font-weight:400;color:#9ca3af;">(optional)</span></h6>

            {{-- Grazing Table --}}
            <div class="addon-row" style="flex-wrap:wrap;">
                <input type="checkbox" name="addons[grazing_table][selected]" id="a-grazing_table" value="1"
                       data-key="grazing_table" onchange="toggleTierAddon('grazing_table', this.checked); calcTotal()">
                <label for="a-grazing_table" style="font-size:12px;color:#374151;flex:1;cursor:pointer;margin:0;">Grazing Table</label>
                <span class="addon-price" id="price-grazing_table" style="color:#9ca3af;">Select pax</span>
                <input type="hidden" name="addons[grazing_table][price]" id="hidden-grazing_table" value="0">
                <div id="tier-grazing_table" style="display:none;width:100%;padding:6px 0 4px 28px;">
                    <select onchange="selectTierAddon('grazing_table', this.value)" class="form-control" style="font-size:12px;max-width:240px;">
                        <option value="">— Select Pax —</option>
                        <option value="10000">50 pax — ₱10,000</option>
                        <option value="15000">100 pax — ₱15,000</option>
                        <option value="20000">150 pax — ₱20,000</option>
                        <option value="25000">200 pax — ₱25,000</option>
                        <option value="30000">250 pax — ₱30,000</option>
                    </select>
                </div>
            </div>

            {{-- Shabu-Shabu Station --}}
            <div class="addon-row" style="flex-wrap:wrap;">
                <input type="checkbox" name="addons[shabu_station][selected]" id="a-shabu_station" value="1"
                       data-key="shabu_station" onchange="toggleTierAddon('shabu_station', this.checked); calcTotal()">
                <label for="a-shabu_station" style="font-size:12px;color:#374151;flex:1;cursor:pointer;margin:0;">Shabu-Shabu Station</label>
                <span class="addon-price" id="price-shabu_station" style="color:#9ca3af;">Select pax</span>
                <input type="hidden" name="addons[shabu_station][price]" id="hidden-shabu_station" value="0">
                <div id="tier-shabu_station" style="display:none;width:100%;padding:6px 0 4px 28px;">
                    <select onchange="selectTierAddon('shabu_station', this.value)" class="form-control" style="font-size:12px;max-width:240px;">
                        <option value="">— Select Pax —</option>
                        <option value="12500">100 pax — ₱12,500</option>
                        <option value="15000">150 pax — ₱15,000</option>
                        <option value="17500">200 pax — ₱17,500</option>
                        <option value="20000">250 pax — ₱20,000</option>
                    </select>
                </div>
            </div>

            {{-- Regular addons --}}
            @php $addons = [
                ['key'=>'upgraded_setup',  'label'=>'Upgraded Set Up by Couple Minds Events Studio','price'=>15000,'type'=>'fixed'],
                ['key'=>'photography',     'label'=>'Photography (starts at)',                       'price'=>30000,'type'=>'fixed'],
                ['key'=>'photo_booth',     'label'=>'Photo Booth (starts at)',                       'price'=>4500, 'type'=>'fixed'],
                ['key'=>'led_wall',        'label'=>'LED Wall',                                      'price'=>15000,'type'=>'fixed'],
                ['key'=>'lechon_baboy',    'label'=>'Lechon Baboy (per pc)',                         'price'=>1000, 'type'=>'qty'],
                ['key'=>'sweet_buffet',    'label'=>'Sweet Buffet / Fruits',                         'price'=>5000, 'type'=>'fixed'],
                ['key'=>'outside_stylist', 'label'=>'Outside Stylist',                               'price'=>2000, 'type'=>'fixed'],
                ['key'=>'full_band',       'label'=>'Full Band',                                     'price'=>3000, 'type'=>'fixed'],
                ['key'=>'liquor_bottle',   'label'=>'Liquor (per Bottle)',                           'price'=>200,  'type'=>'qty'],
                ['key'=>'liquor_case',     'label'=>'Liquor (per Case)',                             'price'=>500,  'type'=>'qty'],
                ['key'=>'food_cart',       'label'=>'Food Cart',                                     'price'=>1500, 'type'=>'fixed'],
                ['key'=>'photo_booth_elec','label'=>'Photo Booth Electricity Charge',                'price'=>1000, 'type'=>'fixed'],
                ['key'=>'exceeding_hour',  'label'=>'Exceeding Hour',                                'price'=>3000, 'type'=>'fixed'],
            ]; @endphp
            @foreach($addons as $a)
            <div class="addon-row">
                <input type="checkbox" name="addons[{{ $a['key'] }}][selected]" id="a-{{ $a['key'] }}" value="1"
                       data-price="{{ $a['price'] }}" data-type="{{ $a['type'] }}" data-key="{{ $a['key'] }}" onchange="toggleQty(this); calcTotal()">
                <label for="a-{{ $a['key'] }}">{{ $a['label'] }}</label>
                <span class="addon-price">₱{{ number_format($a['price']) }}</span>
                @if($a['type']==='qty')
                <input type="number" name="addons[{{ $a['key'] }}][qty]" value="1" min="1" onchange="calcTotal()"
                       id="qty-{{ $a['key'] }}" style="width:55px;display:none;">
                @endif
                <input type="hidden" name="addons[{{ $a['key'] }}][price]" value="{{ $a['price'] }}">
            </div>
            @endforeach
            <div id="packageAdditionalOptions"></div>
        </div>

    </div>

    {{-- RIGHT: Sticky Summary --}}
    <div class="sticky-summary">
        <div class="summary-box">
            <div class="sum-header">Booking Summary</div>
            <div class="sum-body">
                <div class="sum-row"><span class="s-label">Event</span><span class="s-val">{{ $selectedEvent->name }}</span></div>
                <div class="sum-row"><span class="s-label">Venue</span><span class="s-val" id="sumVenue">—</span></div>
                <div class="sum-row"><span class="s-label">Pax Range</span><span class="s-val" id="sumPaxRange">—</span></div>
                <div class="sum-row"><span class="s-label">Price/pax</span><span class="s-val" id="sumPpax">—</span></div>
                <div class="sum-row"><span class="s-label">No. of Guests</span><span class="s-val" id="sumGuests">0</span></div>
                <div class="sum-row"><span class="s-label">Food Set</span><span class="s-val" id="sumFoodSet">—</span></div>
                <div class="sum-row"><span class="s-label">Package Total</span><span class="s-val" id="sumPkgTotal">₱0.00</span></div>
                <div id="sumAddonsList"></div>
                <div class="sum-row"><span class="s-label">Add-ons Total</span><span class="s-val" id="sumAddons">₱0.00</span></div>
                <div class="sum-total-box">
                    <div class="total-label">Estimated Total</div>
                    <div class="total-val" id="sumTotal">₱0.00</div>
                </div>
                <div class="sum-note">* Final amount upon staff confirmation</div>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-calendar-check me-2"></i> Submit Booking Request
                </button>
                <div style="margin-top:12px;padding:10px;background:#fef3c7;border-radius:7px;font-size:11px;color:#92400e;">
                    <i class="fas fa-phone me-1"></i> Our staff will call you to confirm your booking.
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
let selPkgId = null, selPrice = 0, selFoodSet = null;
let selPaxMin = 0, selPaxMax = 0, selEffectiveMax = null;

const allPkgs = @json($pkgsList->map(fn($p) => ['id' => $p->id, 'pax_min' => $p->pax_min, 'pax_max' => $p->pax_max])->sortBy('pax_min')->values());

const pkgData = {};
@foreach($pkgsList as $pkg)
pkgData[{{ $pkg->id }}] = {
    venueName: "{{ addslashes($pkg->venue->name) }}",
    paxRange: "{{ $pkg->pax_range }}",
    paxMin: {{ $pkg->pax_min }},
    paxMax: {{ $pkg->pax_max }},
    tiers: @json($pkg->price_tiers ?? []),
    additionalOptions: @json($pkg->additional_options ?? [])
};
@endforeach

function clearErr(el, errId) {
    el.classList.remove('is-invalid');
    document.getElementById(errId).classList.remove('show');
}

function showErr(el, errId) {
    el.classList.add('is-invalid');
    document.getElementById(errId).classList.add('show');
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.focus();
}

function onPhoneInput(input) {
    const val = input.value;
    if (val.length > 0) {
        const valid = val.length === 11 && val.startsWith('09');
        if (valid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            document.getElementById('phoneError').classList.remove('show');
        } else {
            input.classList.remove('is-valid');
        }
    } else {
        input.classList.remove('is-invalid', 'is-valid');
        document.getElementById('phoneError').classList.remove('show');
    }
}

function getEffectiveMax(paxMin, paxMax) {
    if (paxMin !== paxMax) return paxMax;
    const sorted = allPkgs.slice().sort((a, b) => a.pax_min - b.pax_min);
    const idx = sorted.findIndex(p => p.pax_min === paxMin);
    if (idx !== -1 && idx < sorted.length - 1) return sorted[idx + 1].pax_min - 1;
    return null;
}

function selectTier(pkgId, price, venueName, eventName, eventId, paxMin, paxMax) {
    document.querySelectorAll('.price-tier-row').forEach(r => r.classList.remove('selected'));
    document.querySelectorAll('.select-btn').forEach(b => { b.textContent = 'Select'; b.style.background = '#4a0080'; });
    document.getElementById('tier-' + pkgId + '-' + price).classList.add('selected');
    const btn = document.getElementById('tier-btn-' + pkgId + '-' + price);
    btn.textContent = '✓ Selected'; btn.style.background = '#10b981';

    selPkgId = pkgId; selPrice = price; selFoodSet = null;
    selPaxMin = paxMin; selPaxMax = paxMax;
    selEffectiveMax = getEffectiveMax(paxMin, paxMax);

    document.getElementById('packageIdInput').value = pkgId;
    document.getElementById('priceInput').value = price;
    document.getElementById('foodSetInput').value = '';

    const infoEl = document.getElementById('paxInfo');
    let rangeText = paxMin !== paxMax
        ? `This package accepts ${paxMin} to ${paxMax} guests.`
        : selEffectiveMax !== null
            ? `This package accepts ${paxMin} to ${selEffectiveMax} guests.`
            : `This package requires at least ${paxMin} guests.`;
    document.getElementById('paxInfoText').textContent = rangeText;
    infoEl.classList.add('show');
    document.getElementById('noPkgWarning').classList.remove('show');

    const pkg = pkgData[pkgId];
    renderPackageAdditionalOptions(pkg ? pkg.additionalOptions : []);
    const sets = (pkg && pkg.tiers && pkg.tiers[String(price)]) ? pkg.tiers[String(price)] : {};
    const grid = document.getElementById('fsgrid-' + pkgId);
    grid.innerHTML = '';

    Object.keys(sets).forEach(setKey => {
        const setData = sets[setKey];
        const items = setData.items || [];
        const div = document.createElement('div');
        div.className = 'food-set-card';
        div.id = 'fscard-' + pkgId + '-' + setKey;

        // ✅ Use closure to avoid JSON in onclick
        (function(sk) {
            div.addEventListener('click', function() { selectFoodSet(pkgId, sk); });
        })(setKey);

        div.innerHTML = `<div class="fs-badge">Set ${setKey}</div><ul>${items.map(i=>`<li>• ${i}</li>`).join('')}</ul>`;
        grid.appendChild(div);
    });

    document.querySelectorAll('[id^="foodsets-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('[id^="choices-"]').forEach(el => el.style.display = 'none');
    if (Object.keys(sets).length > 0) {
        document.getElementById('foodsets-' + pkgId).style.display = 'block';
        document.getElementById('choices-' + pkgId).style.display = 'block';
    }

    document.getElementById('sumVenue').textContent = venueName;
    document.getElementById('sumPaxRange').textContent = pkg ? pkg.paxRange + ' pax' : '—';
    document.getElementById('sumPpax').textContent = '₱' + Number(price).toLocaleString() + '/pax';
    document.getElementById('sumFoodSet').textContent = '—';

    validatePax();
    calcTotal();
}

function renderPackageAdditionalOptions(options) {
    const container = document.getElementById('packageAdditionalOptions');
    if (!container) return;
    container.innerHTML = '';
    (options || []).forEach(option => {
        const key = option.key || 'additional_' + Math.random().toString(36).slice(2);
        const price = Number(option.price) || 0;
        const type = ['fixed', 'qty', 'pax'].includes(option.type) ? option.type : 'fixed';
        const row = document.createElement('div');
        row.className = 'addon-row';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox'; checkbox.name = `addons[${key}][selected]`;
        checkbox.id = `a-${key}`; checkbox.value = '1';
        checkbox.dataset.price = price; checkbox.dataset.type = type; checkbox.dataset.key = key;
        checkbox.addEventListener('change', function () { toggleQty(this); calcTotal(); });

        const label = document.createElement('label');
        label.htmlFor = checkbox.id; label.textContent = option.label || 'Additional charge';
        const amount = document.createElement('span');
        amount.className = 'addon-price';
        amount.textContent = `₱${price.toLocaleString('en-PH')}${type === 'pax' ? '/pax' : ''}`;
        const hidden = document.createElement('input');
        hidden.type = 'hidden'; hidden.name = `addons[${key}][price]`; hidden.value = price;
        row.append(checkbox, label, amount);
        if (type === 'qty') {
            const quantity = document.createElement('input');
            quantity.type = 'number'; quantity.name = `addons[${key}][qty]`; quantity.id = `qty-${key}`;
            quantity.value = '1'; quantity.min = '1'; quantity.style.cssText = 'width:55px;display:none;';
            quantity.addEventListener('change', calcTotal); row.appendChild(quantity);
        }
        row.appendChild(hidden);
        container.appendChild(row);
    });
}

function selectFoodSet(pkgId, setKey) {
    selFoodSet = setKey;
    document.getElementById('foodSetInput').value = setKey;
    document.querySelectorAll('[id^="fscard-' + pkgId + '-"]').forEach(c => c.classList.remove('selected'));
    document.getElementById('fscard-' + pkgId + '-' + setKey).classList.add('selected');
    document.getElementById('sumFoodSet').textContent = 'Set ' + setKey;
}

function validatePax() {
    const paxInput = document.getElementById('paxCount');
    const pax = parseInt(paxInput.value) || 0;
    const errorEl = document.getElementById('paxError');
    const successEl = document.getElementById('paxSuccess');

    if (!selPkgId) {
        if (pax > 0) document.getElementById('noPkgWarning').classList.add('show');
        return false;
    }
    document.getElementById('noPkgWarning').classList.remove('show');

    if (pax === 0) {
        paxInput.classList.remove('is-invalid','is-valid');
        errorEl.classList.remove('show'); successEl.classList.remove('show');
        return false;
    }
    if (pax < selPaxMin) {
        paxInput.classList.add('is-invalid'); paxInput.classList.remove('is-valid');
        document.getElementById('paxErrorText').textContent = `Minimum is ${selPaxMin} guests for this package.`;
        errorEl.classList.add('show'); successEl.classList.remove('show');
        return false;
    }
    if (selEffectiveMax !== null && pax > selEffectiveMax) {
        paxInput.classList.add('is-invalid'); paxInput.classList.remove('is-valid');
        document.getElementById('paxErrorText').textContent = `Maximum is ${selEffectiveMax} guests for this package.`;
        errorEl.classList.add('show'); successEl.classList.remove('show');
        return false;
    }
    paxInput.classList.remove('is-invalid'); paxInput.classList.add('is-valid');
    errorEl.classList.remove('show');
    document.getElementById('paxSuccessText').textContent = `${pax} guests — looks good!`;
    successEl.classList.add('show');
    return true;
}

function toggleQty(cb) {
    const qtyEl = document.getElementById('qty-' + cb.dataset.key);
    if (qtyEl) qtyEl.style.display = cb.checked ? 'inline-block' : 'none';
}

const tierAddonPrices = { grazing_table: 0, shabu_station: 0 };

function toggleTierAddon(key, checked) {
    const tierDiv = document.getElementById('tier-' + key);
    const priceSpan = document.getElementById('price-' + key);
    if (checked) {
        tierDiv.style.display = 'block';
        priceSpan.style.color = '#9ca3af';
        priceSpan.textContent = 'Select pax';
    } else {
        tierDiv.style.display = 'none';
        tierAddonPrices[key] = 0;
        document.getElementById('hidden-' + key).value = 0;
        priceSpan.style.color = '#9ca3af';
        priceSpan.textContent = 'Select pax';
        const sel = tierDiv.querySelector('select');
        if (sel) sel.value = '';
    }
    calcTotal();
}

function selectTierAddon(key, value) {
    const price = parseInt(value) || 0;
    tierAddonPrices[key] = price;
    document.getElementById('hidden-' + key).value = price;
    const priceSpan = document.getElementById('price-' + key);
    if (price > 0) {
        priceSpan.style.color = '#4a0080';
        priceSpan.textContent = '₱' + price.toLocaleString('en-PH');
    } else {
        priceSpan.style.color = '#9ca3af';
        priceSpan.textContent = 'Select pax';
    }
    calcTotal();
}

function calcTotal() {
    const pax = parseInt(document.getElementById('paxCount').value) || 0;
    const pkgTotal = selPrice * pax;
    let addonTotal = 0;

    const grazingCb = document.getElementById('a-grazing_table');
    if (grazingCb && grazingCb.checked) addonTotal += tierAddonPrices['grazing_table'];
    const shabuCb = document.getElementById('a-shabu_station');
    if (shabuCb && shabuCb.checked) addonTotal += tierAddonPrices['shabu_station'];

    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(cb => {
        if (!cb.dataset.price) return;
        const price = parseInt(cb.dataset.price);
        const type = cb.dataset.type;
        const key = cb.name.match(/addons\[(.+?)\]/)[1];
        if (type === 'pax') addonTotal += price * pax;
        else if (type === 'qty') {
            const qtyEl = document.querySelector(`input[name="addons[${key}][qty]"]`);
            addonTotal += price * (parseInt(qtyEl?.value) || 1);
        } else addonTotal += price;
    });

    const grand = pkgTotal + addonTotal;
    const addonsList = document.getElementById('sumAddonsList');
    addonsList.innerHTML = '';

    if (grazingCb && grazingCb.checked && tierAddonPrices['grazing_table'] > 0) {
        const row = document.createElement('div');
        row.className = 'sum-row';
        row.innerHTML = `<span class="s-label" style="font-size:11px;">+ Grazing Table</span><span class="s-val" style="font-size:11px;color:#7b2ff7;">₱${tierAddonPrices['grazing_table'].toLocaleString('en-PH')}</span>`;
        addonsList.appendChild(row);
    }
    if (shabuCb && shabuCb.checked && tierAddonPrices['shabu_station'] > 0) {
        const row = document.createElement('div');
        row.className = 'sum-row';
        row.innerHTML = `<span class="s-label" style="font-size:11px;">+ Shabu-Shabu Station</span><span class="s-val" style="font-size:11px;color:#7b2ff7;">₱${tierAddonPrices['shabu_station'].toLocaleString('en-PH')}</span>`;
        addonsList.appendChild(row);
    }
    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(cb => {
        if (!cb.dataset.price) return;
        const label = cb.closest('.addon-row').querySelector('label').textContent.trim();
        const row = document.createElement('div');
        row.className = 'sum-row';
        row.innerHTML = `<span class="s-label" style="font-size:11px;">+ ${label}</span><span class="s-val" style="font-size:11px;color:#7b2ff7;"></span>`;
        addonsList.appendChild(row);
    });

    document.getElementById('sumGuests').textContent = pax;
    document.getElementById('sumPkgTotal').textContent = '₱' + pkgTotal.toLocaleString('en-PH',{minimumFractionDigits:2});
    document.getElementById('sumAddons').textContent = '₱' + addonTotal.toLocaleString('en-PH',{minimumFractionDigits:2});
    document.getElementById('sumTotal').textContent = '₱' + grand.toLocaleString('en-PH',{minimumFractionDigits:2});
    document.getElementById('addonTotalInput').value = addonTotal;
}

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // 1. Name
    const nameEl = document.getElementById('guestName');
    if (!nameEl.value.trim()) { showErr(nameEl, 'nameError'); return; }
    else clearErr(nameEl, 'nameError');

    // 2. Phone
    const phoneEl = document.getElementById('guestPhone');
    if (!phoneEl.value || phoneEl.value.length !== 11 || !phoneEl.value.startsWith('09')) {
        showErr(phoneEl, 'phoneError'); return;
    } else clearErr(phoneEl, 'phoneError');

    // 3. Email
    const emailEl = document.getElementById('guestEmail');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailEl.value.trim())) { showErr(emailEl, 'emailError'); return; }
    else clearErr(emailEl, 'emailError');

    // 4. Celebrant
    const celebrantEl = document.getElementById('celebrantName');
    if (!celebrantEl.value.trim()) { showErr(celebrantEl, 'celebrantError'); return; }
    else clearErr(celebrantEl, 'celebrantError');

    // 5. Pax
    const paxEl = document.getElementById('paxCount');
    if (!parseInt(paxEl.value) || !validatePax()) {
        paxEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        paxEl.focus(); return;
    }

    // 6. Date
    const dateEl = document.getElementById('eventDate');
    if (!dateEl.value) { showErr(dateEl, 'dateError'); return; }
    else clearErr(dateEl, 'dateError');

    // 7. Time
    const timeEl = document.getElementById('eventTime');
    if (!timeEl.value) { showErr(timeEl, 'timeError'); return; }
    else clearErr(timeEl, 'timeError');

    // 8. Package
    if (!selPkgId) {
        alert('Please select a package and price tier (Section 3).');
        document.querySelector('.pkg-section').scrollIntoView({ behavior: 'smooth' });
        return;
    }

    // 9. Food set
    if (!selFoodSet) {
        alert('Please select a food menu set (Section 3).');
        return;
    }

    // 10. Soup
    const soupEl = document.getElementById('soupChoice');
    if (soupEl && !soupEl.value) { showErr(soupEl, 'soupError'); return; }
    else if (soupEl) clearErr(soupEl, 'soupError');

    // 11. Dessert
    const dessertEl = document.getElementById('dessertChoice');
    if (dessertEl && !dessertEl.value) { showErr(dessertEl, 'dessertError'); return; }
    else if (dessertEl) clearErr(dessertEl, 'dessertError');

    // 12. Drink
    const drinkEl = document.getElementById('drinkChoice');
    if (drinkEl && !drinkEl.value) { showErr(drinkEl, 'drinkError'); return; }
    else if (drinkEl) clearErr(drinkEl, 'drinkError');

    this.submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
