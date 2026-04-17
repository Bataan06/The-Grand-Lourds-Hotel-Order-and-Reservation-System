<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .form-control { border: 1.5px solid #e9d5ff; border-radius: 7px; font-size: 13px; padding: 9px 13px; }
        .form-control:focus { border-color: #7b2ff7; box-shadow: none; }
        .form-control.is-invalid { border-color: #dc2626; }
        .form-control.is-valid { border-color: #10b981; }
        .form-label { font-size: 11px; font-weight: 700; color: #6b21a8; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
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
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Validation styles */
        .pax-hint { font-size: 11px; margin-top: 5px; display: none; }
        .pax-hint.show { display: block; }
        .pax-hint.error { color: #dc2626; }
        .pax-hint.success { color: #10b981; }
        .pax-hint.info { color: #6b21a8; }
        .no-pkg-warning { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 7px; padding: 10px 13px; font-size: 12px; color: #92400e; margin-top: 6px; display: none; }
        .no-pkg-warning.show { display: block; }
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

    {{-- LEFT --}}
    <div>
        <div style="margin-bottom:20px;">
            <h4 style="font-family:'Cormorant Garamond',serif;color:#2d0057;font-size:1.6rem;margin-bottom:4px;">
                {{ $selectedEvent->name }} Packages
            </h4>
            <p style="color:#9ca3af;font-size:12px;">Select your preferred package and price tier below.</p>
        </div>

        @php
            $pkgsQuery = $selectedEvent->packages()->where('is_active', true)->with('venue')->orderBy('pax_min');
            if (!empty($selectedPackageId)) {
                $pkgsQuery->where('id', $selectedPackageId);
            }
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
                        <select name="soup_choice" class="form-control" style="font-size:12px;">
                            <option value="">-- Select --</option>
                            <option>Cream of Mushroom</option>
                            <option>Pumpkin Soup</option>
                            <option>Sweet Corn with Crab Meat</option>
                            <option>Nido Soup with Quail Egg</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Choice of Dessert *</label>
                        <select name="dessert_choice" class="form-control" style="font-size:12px;">
                            <option value="">-- Select --</option>
                            <option>Fruit Salad</option>
                            <option>Buko Pandan Salad</option>
                            <option>Coffee Jelly</option>
                            <option>Almond Lychee Jelly</option>
                            <option>Butchi</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Choice of Drink *</label>
                        <select name="drink_choice" class="form-control" style="font-size:12px;">
                            <option value="">-- Select --</option>
                            <option>Glass of Coke</option>
                            <option>Glass of Iced Tea</option>
                            <option>Glass of Cucumber Juice</option>
                            <option>Glass of Blue Lemonade</option>
                            <option>Glass of Pink Lemonade</option>
                        </select>
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

        {{-- Additional Charges --}}
        <div class="details-section">
            <h6><i class="fas fa-plus-circle me-2" style="color:#7b2ff7;"></i> Additional Charges / Corkage Fee <span style="font-weight:400;color:#9ca3af;">(optional)</span></h6>
            @php $addons = [
                ['key'=>'grazing_table',   'label'=>'Grazing Table',                         'price'=>10000, 'type'=>'fixed'],
                ['key'=>'upgraded_setup',  'label'=>'Upgraded Set Up (Couple Minds Events)',  'price'=>15000, 'type'=>'fixed'],
                ['key'=>'led_wall',        'label'=>'LED Wall',                              'price'=>15000, 'type'=>'fixed'],
                ['key'=>'sweet_buffet',    'label'=>'Sweet Buffet / Fruits',                 'price'=>5000,  'type'=>'fixed'],
                ['key'=>'shabu_station',   'label'=>'Shabu-Shabu Station (per pax)',         'price'=>120,   'type'=>'pax'],
                ['key'=>'food_cart',       'label'=>'Food Cart',                             'price'=>3500,  'type'=>'fixed'],
                ['key'=>'full_band',       'label'=>'Full Band',                             'price'=>15000, 'type'=>'fixed'],
                ['key'=>'outside_stylist', 'label'=>'Outside Stylist',                       'price'=>5000,  'type'=>'fixed'],
                ['key'=>'photography',     'label'=>'Photography',                           'price'=>8000,  'type'=>'fixed'],
                ['key'=>'photo_booth',     'label'=>'Photo Booth',                           'price'=>5000,  'type'=>'fixed'],
                ['key'=>'photo_booth_elec','label'=>'Photo Booth Electricity Charge',        'price'=>1000,  'type'=>'fixed'],
                ['key'=>'exceeding_hour',  'label'=>'Exceeding Hour',                        'price'=>3000,  'type'=>'fixed'],
                ['key'=>'lechon_baboy',    'label'=>'Lechon Baboy',                          'price'=>8000,  'type'=>'qty'],
                ['key'=>'liquor_bottle',   'label'=>'Liquor (per Bottle)',                   'price'=>500,   'type'=>'qty'],
                ['key'=>'liquor_case',     'label'=>'Liquor (per Case)',                     'price'=>5000,  'type'=>'qty'],
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
        </div>

        {{-- Guest Details --}}
        <div class="details-section">
            <h6><i class="fas fa-user me-2" style="color:#7b2ff7;"></i> Your Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="guest_name" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" name="guest_phone" id="guestPhone" class="form-control" placeholder="09XX-XXX-XXXX" required
                           oninput="validatePhone(this)">
                    <div class="pax-hint error" id="phoneError">
                        <i class="fas fa-exclamation-circle me-1"></i> Please enter a valid 11-digit PH number (e.g. 09171234567)
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="guest_email" class="form-control" placeholder="your@email.com" required>
                </div>
            </div>
        </div>

        {{-- Event Details --}}
        <div class="details-section">
            <h6><i class="fas fa-calendar me-2" style="color:#7b2ff7;"></i> Event Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name of Celebrant / Couple *</label>
                    <input type="text" name="celebrant_name" class="form-control" placeholder="e.g. Maria Santos" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Number of Guests *</label>
                    <input type="number" name="pax_count" id="paxCount" class="form-control"
                           placeholder="e.g. 100" min="1" required oninput="validatePax(); calcTotal()">
                    {{-- Hint shown after package selected --}}
                    <div class="pax-hint info" id="paxInfo">
                        <i class="fas fa-info-circle me-1"></i> <span id="paxInfoText"></span>
                    </div>
                    <div class="pax-hint error" id="paxError">
                        <i class="fas fa-exclamation-circle me-1"></i> <span id="paxErrorText"></span>
                    </div>
                    <div class="pax-hint success" id="paxSuccess">
                        <i class="fas fa-check-circle me-1"></i> <span id="paxSuccessText"></span>
                    </div>
                    <div class="no-pkg-warning" id="noPkgWarning">
                        <i class="fas fa-exclamation-triangle me-1"></i> Please select a package first before entering the number of guests.
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Event Date *</label>
                    <input type="date" name="event_date" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Event Time *</label>
                    <select name="event_time_start" class="form-control" required>
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
                </div>
                <div class="col-12">
                    <div style="background:#fef3c7;border:1.5px solid #f59e0b;border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:14px;">
                        <input type="checkbox" name="is_pencil" id="isPencil" value="1"
                               style="width:18px;height:18px;accent-color:#f59e0b;flex-shrink:0;">
                        <div>
                            <label for="isPencil" style="font-size:12px;font-weight:700;color:#92400e;cursor:pointer;margin:0;">
                                ✏️ Pencil Reservation (Tentative)
                            </label>
                            <div style="font-size:11px;color:#78350f;margin-top:2px;">
                                Check this if you are not yet sure about your booking. Our staff will contact you to confirm. Slot is held temporarily.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Special Requests <span style="color:#9ca3af;text-transform:none;">(optional)</span></label>
                    <textarea name="special_requests" class="form-control" rows="2" placeholder="Any special requests..."></textarea>
                </div>
            </div>
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
let selPaxMin = 0, selPaxMax = 0;

const pkgData = {};
@foreach($pkgsList as $pkg)
pkgData[{{ $pkg->id }}] = {
    venueName: "{{ addslashes($pkg->venue->name) }}",
    paxRange: "{{ $pkg->pax_range }}",
    paxMin: {{ $pkg->pax_min }},
    paxMax: {{ $pkg->pax_max }},
    tiers: @json($pkg->price_tiers ?? [])
};
@endforeach

function selectTier(pkgId, price, venueName, eventName, eventId, paxMin, paxMax) {
    document.querySelectorAll('.price-tier-row').forEach(r => r.classList.remove('selected'));
    document.querySelectorAll('.select-btn').forEach(b => { b.textContent = 'Select'; b.style.background = '#4a0080'; });
    document.getElementById('tier-' + pkgId + '-' + price).classList.add('selected');
    const btn = document.getElementById('tier-btn-' + pkgId + '-' + price);
    btn.textContent = '✓ Selected'; btn.style.background = '#10b981';

    selPkgId = pkgId; selPrice = price; selFoodSet = null;
    selPaxMin = paxMin; selPaxMax = paxMax;

    document.getElementById('packageIdInput').value = pkgId;
    document.getElementById('priceInput').value = price;
    document.getElementById('foodSetInput').value = '';

    // Show pax range hint
    const infoEl = document.getElementById('paxInfo');
    const infoText = document.getElementById('paxInfoText');
    const rangeText = paxMin === paxMax
        ? `This package is for exactly ${paxMin} guests.`
        : `This package accepts ${paxMin} to ${paxMax} guests.`;
    infoText.textContent = rangeText;
    infoEl.classList.add('show');
    document.getElementById('noPkgWarning').classList.remove('show');

    const pkg = pkgData[pkgId];
    const tierKey = String(price);
    const sets = (pkg && pkg.tiers && pkg.tiers[tierKey]) ? pkg.tiers[tierKey] : {};
    const grid = document.getElementById('fsgrid-' + pkgId);
    grid.innerHTML = '';
    Object.keys(sets).forEach(setKey => {
        const items = sets[setKey].items || [];
        const div = document.createElement('div');
        div.className = 'food-set-card';
        div.id = 'fscard-' + pkgId + '-' + setKey;
        div.onclick = () => selectFoodSet(pkgId, setKey);
        div.innerHTML = `<div class="fs-badge">Set ${setKey}</div><ul>${items.map(i=>`<li>• ${i}</li>`).join('')}<li>• Choice of Soup</li><li>• Choice of Dessert</li><li>• Choice of Drink</li></ul>`;
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

    // Re-validate pax if already entered
    validatePax();
    calcTotal();
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
    const errorText = document.getElementById('paxErrorText');
    const successEl = document.getElementById('paxSuccess');
    const successText = document.getElementById('paxSuccessText');
    const noPkgWarn = document.getElementById('noPkgWarning');

    if (!selPkgId) {
        if (pax > 0) noPkgWarn.classList.add('show');
        return;
    }

    noPkgWarn.classList.remove('show');

    if (pax === 0) {
        paxInput.classList.remove('is-invalid','is-valid');
        errorEl.classList.remove('show');
        successEl.classList.remove('show');
        return;
    }

    const hasMax = selPaxMin !== selPaxMax; // true if range like 50-90 or 200-300

    if (pax < selPaxMin) {
        paxInput.classList.add('is-invalid');
        paxInput.classList.remove('is-valid');
        errorText.textContent = `Minimum is ${selPaxMin} guests for this package.`;
        errorEl.classList.add('show');
        successEl.classList.remove('show');
        document.getElementById('submitBtn').disabled = true;
    } else if (hasMax && pax > selPaxMax) {
        paxInput.classList.add('is-invalid');
        paxInput.classList.remove('is-valid');
        errorText.textContent = `Maximum is ${selPaxMax} guests for this package. Please choose a different package.`;
        errorEl.classList.add('show');
        successEl.classList.remove('show');
        document.getElementById('submitBtn').disabled = true;
    } else {
        paxInput.classList.remove('is-invalid');
        paxInput.classList.add('is-valid');
        errorEl.classList.remove('show');
        successText.textContent = `${pax} guests — looks good!`;
        successEl.classList.add('show');
        document.getElementById('submitBtn').disabled = false;
    }
}

function validatePhone(input) {
    const val = input.value.replace(/\D/g,'');
    const errorEl = document.getElementById('phoneError');
    if (val.length > 0 && (val.length !== 11 || !val.startsWith('09'))) {
        input.classList.add('is-invalid');
        errorEl.classList.add('show');
    } else {
        input.classList.remove('is-invalid');
        errorEl.classList.remove('show');
    }
}

function toggleQty(cb) {
    const key = cb.dataset.key;
    const qtyEl = document.getElementById('qty-' + key);
    if (qtyEl) qtyEl.style.display = cb.checked ? 'inline-block' : 'none';
}

function calcTotal() {
    const pax = parseInt(document.getElementById('paxCount').value) || 0;
    const pkgTotal = selPrice * pax;
    let addonTotal = 0;
    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(cb => {
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
    document.querySelectorAll('.addon-row input[type=checkbox]:checked').forEach(cb => {
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

// Form submission validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const pax = parseInt(document.getElementById('paxCount').value) || 0;
    let valid = true;

    if (!selPkgId) {
        alert('Please select a package first.');
        valid = false;
    } else if (pax < selPaxMin) {
        alert(`Minimum number of guests is ${selPaxMin} for this package.`);
        valid = false;
    } else if (selPaxMin !== selPaxMax && pax > selPaxMax) {
        alert(`Maximum number of guests is ${selPaxMax} for this package.`);
        valid = false;
    }

    if (!valid) e.preventDefault();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>