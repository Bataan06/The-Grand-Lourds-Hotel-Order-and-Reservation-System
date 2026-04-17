@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #2d0057; font-weight: 800; }
    .breadcrumb-item a { color: #7b2ff7; text-decoration: none; }
    .card { border: none; border-radius: 18px; box-shadow: 0 5px 25px rgba(74,0,128,0.08); }
    .card-header-violet { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 18px 18px 0 0 !important; padding: 16px 22px; font-weight: 600; }
    .form-control:focus, .form-select:focus { box-shadow: none; border-color: #7b2ff7; }
    .form-label { font-weight: 600; color: #333; font-size: 0.9rem; }
    .amenity-item { background: #f3e5f5; border-radius: 8px; padding: 5px 10px; margin-bottom: 5px; color: #4a0080; font-size: 0.8rem; display: flex; gap: 8px; }

    .price-tier-btn { border: 2px solid #e1bee7; border-radius: 12px; padding: 14px 20px; cursor: pointer; text-align: center; background: #faf5ff; transition: all 0.2s; flex: 1; }
    .price-tier-btn:hover { border-color: #7b2ff7; background: #f3e5f5; }
    .price-tier-btn.active { border-color: #4a0080; background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; }
    .price-tier-btn .amt { font-size: 1.3rem; font-weight: 800; color: #4a0080; display: block; }
    .price-tier-btn.active .amt { color: white; }
    .price-tier-btn .lbl { font-size: 0.72rem; color: #9b59b6; }
    .price-tier-btn.active .lbl { color: rgba(255,255,255,0.8); }

    .set-card { border: 2px solid #e1bee7; border-radius: 12px; padding: 14px; cursor: pointer; transition: all 0.2s; background: #faf5ff; }
    .set-card:hover { border-color: #7b2ff7; background: #f3e5f5; }
    .set-card.active { border-color: #4a0080; background: #ede7f6; }
    .set-card .set-label { font-weight: 700; color: #4a0080; margin-bottom: 6px; }
    .set-card.active .set-label { color: #2d0057; }
    .menu-item { font-size: 0.82rem; color: #555; padding: 2px 0; }
    .menu-item.choice { color: #9b59b6; font-style: italic; }
    .price-panel { display: none; }
    .price-panel.active { display: block; }

    /* Additional Charges */
    .addon-section-header { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9b59b6; margin: 14px 0 8px; }
    .addon-card { border: 1.5px solid #e9d5ff; border-radius: 10px; padding: 12px 14px; background: #faf5ff; transition: all 0.2s; cursor: pointer; margin-bottom: 8px; }
    .addon-card:hover { border-color: #a78bfa; background: #f3e8ff; }
    .addon-card.selected { border-color: #7b2ff7; background: #ede7f6; }
    .addon-title { font-weight: 700; color: #2d0057; font-size: 0.85rem; }
    .addon-price { font-weight: 800; color: #7b2ff7; font-size: 0.85rem; white-space: nowrap; }
    .addon-desc  { font-size: 0.73rem; color: #9ca3af; margin-top: 2px; }
    .addon-check { width: 17px; height: 17px; accent-color: #7b2ff7; cursor: pointer; flex-shrink: 0; margin-top: 2px; }
    .addon-sub-select { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 5px 8px; font-size: 0.8rem; color: #4a0080; background: white; outline: none; margin-top: 7px; width: 100%; display: none; }
    .addon-sub-select.show { display: block; }
    .addon-qty-wrap { display: none; align-items: center; gap: 8px; margin-top: 7px; }
    .addon-qty-wrap.show { display: flex; }
    .addon-qty-wrap input { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; color: #4a0080; width: 70px; outline: none; }

    /* Total Summary */
    .total-box { background: linear-gradient(135deg, #4a0080, #7b2ff7); border-radius: 14px; padding: 18px; color: white; margin-bottom: 16px; }
    .total-row { display: flex; justify-content: space-between; font-size: 0.83rem; opacity: 0.85; margin-bottom: 5px; }
    .total-row.grand { font-size: 1.05rem; font-weight: 800; opacity: 1; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 10px; margin-top: 6px; }

    .btn-reserve { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 10px; padding: 14px; font-weight: 700; font-size: 1rem; width: 100%; transition: all 0.3s; }
    .btn-reserve:hover { opacity: 0.9; color: white; }
    .section-label { font-weight: 700; color: #4a0080; font-size: 0.85rem; border-left: 3px solid #7b2ff7; padding-left: 10px; margin-bottom: 14px; }
</style>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.events.index') }}">Events</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.events.venues', $package->event_id) }}">{{ $package->event->name }}</a></li>
        <li class="breadcrumb-item active">Book Package</li>
    </ol>
</nav>

<h2 class="page-title mb-4"><i class="fas fa-calendar-plus me-2"></i> Reservation Form</h2>

@php
    $tiers  = $package->price_tiers;
    $prices = array_keys($tiers);
    $allSoups = $allDesserts = $allDrinks = [];
    foreach ($tiers as $tierSets) {
        foreach ($tierSets as $setData) {
            $allSoups    = array_unique(array_merge($allSoups,    $setData['soup_choices']));
            $allDesserts = array_unique(array_merge($allDesserts, $setData['dessert_choices']));
            $allDrinks   = array_unique(array_merge($allDrinks,   $setData['drink_choices']));
        }
    }

    // Complete additional charges
    $addons = [
        // ── Decorations & Setup ──
        [
            'key'         => 'grazing_table',
            'group'       => 'Decorations & Setup',
            'label'       => 'Grazing Table',
            'desc'        => 'Beautifully arranged grazing table',
            'type'        => 'pax',
            'pax_options' => ['50 pax' => 10000, '100 pax' => 15000, '150 pax' => 20000, '200 pax' => 25000, '250 pax' => 30000],
        ],
        [
            'key'   => 'upgraded_setup',
            'group' => 'Decorations & Setup',
            'label' => 'Upgraded Set Up — Couple Minds Events Studio',
            'desc'  => 'Premium event styling and decoration',
            'type'  => 'fixed',
            'price' => 15000,
        ],
        [
            'key'   => 'led_wall',
            'group' => 'Decorations & Setup',
            'label' => 'LED Wall',
            'desc'  => 'Large LED wall backdrop display',
            'type'  => 'fixed',
            'price' => 15000,
        ],
        [
            'key'   => 'sweet_buffet',
            'group' => 'Decorations & Setup',
            'label' => 'Sweet Buffet / Fruits',
            'desc'  => 'Dessert and fruit display station',
            'type'  => 'fixed',
            'price' => 5000,
        ],
        // ── Food & Beverage ──
        [
            'key'         => 'shabu_station',
            'group'       => 'Food & Beverage',
            'label'       => 'Shabu-Shabu Station',
            'desc'        => 'Interactive shabu-shabu dining station',
            'type'        => 'pax',
            'pax_options' => ['100 pax' => 12500, '150 pax' => 15000, '200 pax' => 17500, '250 pax' => 20000],
        ],
        [
            'key'   => 'lechon_baboy',
            'group' => 'Food & Beverage',
            'label' => 'Lechon Baboy',
            'desc'  => '₱1,000.00 per piece',
            'type'  => 'qty',
            'price' => 1000,
        ],
        [
            'key'   => 'food_cart',
            'group' => 'Food & Beverage',
            'label' => 'Food Cart',
            'desc'  => 'Additional food cart station',
            'type'  => 'fixed',
            'price' => 1500,
        ],
        [
            'key'   => 'liquor_bottle',
            'group' => 'Food & Beverage',
            'label' => 'Liquor (per Bottle)',
            'desc'  => '₱200.00 per bottle',
            'type'  => 'qty',
            'price' => 200,
        ],
        [
            'key'   => 'liquor_case',
            'group' => 'Food & Beverage',
            'label' => 'Liquor (per Case)',
            'desc'  => '₱500.00 per case',
            'type'  => 'qty',
            'price' => 500,
        ],
        // ── Entertainment ──
        [
            'key'   => 'full_band',
            'group' => 'Entertainment',
            'label' => 'Full Band',
            'desc'  => 'Live band performance for your event',
            'type'  => 'fixed',
            'price' => 3000,
        ],
        [
            'key'   => 'outside_stylist',
            'group' => 'Entertainment',
            'label' => 'Outside Stylist',
            'desc'  => 'External event stylist fee',
            'type'  => 'fixed',
            'price' => 2000,
        ],
        // ── Photography & Media ──
        [
            'key'   => 'photography',
            'group' => 'Photography & Media',
            'label' => 'Photography',
            'desc'  => 'Professional photography coverage',
            'type'  => 'fixed',
            'price' => 30000,
        ],
        [
            'key'   => 'photo_booth',
            'group' => 'Photography & Media',
            'label' => 'Photo Booth',
            'desc'  => 'Fun photo booth with props',
            'type'  => 'fixed',
            'price' => 4500,
        ],
        [
            'key'   => 'photo_booth_electricity',
            'group' => 'Photography & Media',
            'label' => 'Photo Booth — Electricity Charge',
            'desc'  => 'Electricity charge for external photo booth',
            'type'  => 'fixed',
            'price' => 1000,
        ],
        // ── Other ──
        [
            'key'   => 'exceeding_hour',
            'group' => 'Other',
            'label' => 'Exceeding Hour',
            'desc'  => 'Additional charge per hour beyond venue time',
            'type'  => 'fixed',
            'price' => 3000,
        ],
    ];

    // Group addons
    $grouped = [];
    foreach ($addons as $addon) {
        $grouped[$addon['group']][] = $addon;
    }
@endphp

<div class="row">
    {{-- LEFT: Package Info + Summary --}}
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-box-open me-2"></i> Package Summary</div>
            <div class="card-body p-4">
                <p><strong style="color:#4a0080;">Event:</strong> {{ $package->event->name }}</p>
                <p><strong style="color:#4a0080;">Venue:</strong> {{ $package->venue->name }}</p>
                <p><strong style="color:#4a0080;">Pax Range:</strong> {{ $package->pax_range }} guests</p>
                <hr>
                <p class="section-label">Package Inclusions</p>
                @foreach($package->amenities as $amenity)
                    <div class="amenity-item">
                        <i class="fas fa-check" style="color:#7b2ff7;font-size:0.7rem;margin-top:3px;flex-shrink:0;"></i>
                        {{ $amenity }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Price Summary --}}
        <div class="card mt-3">
            <div class="card-header-violet"><i class="fas fa-receipt me-2"></i> Price Summary</div>
            <div class="card-body p-4">
                <div class="total-box">
                    <div class="total-row">
                        <span>Package (per pax)</span>
                        <span id="summary-price-pax">₱0</span>
                    </div>
                    <div class="total-row">
                        <span>No. of Guests</span>
                        <span id="summary-pax">0</span>
                    </div>
                    <div class="total-row">
                        <span>Package Subtotal</span>
                        <span id="summary-subtotal">₱0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Additional Charges</span>
                        <span id="summary-addons">₱0.00</span>
                    </div>
                    <div class="total-row grand">
                        <span>TOTAL</span>
                        <span id="summary-total">₱0.00</span>
                    </div>
                </div>
                <p style="font-size:0.72rem;color:#9ca3af;text-align:center;">* Final amount upon confirmation</p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Form --}}
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-calendar-check me-2"></i> Complete Your Reservation</div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form id="reservationForm" action="{{ route('user.events.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id"       value="{{ $package->id }}">
                    <input type="hidden" name="price_per_pax"    id="hidden_price" value="{{ old('price_per_pax', $prices[0]) }}">
                    <input type="hidden" name="food_set"         id="hidden_set"   value="{{ old('food_set', '') }}">
                    <input type="hidden" name="additional_total" id="hidden_addon_total" value="0">

                    {{-- Event Details --}}
                    <p class="section-label"><i class="fas fa-calendar me-1"></i> Event Details</p>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Event Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                value="{{ old('event_date') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Event Time <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="time" name="event_time_start" class="form-control" value="{{ old('event_time_start') }}">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="pax_count" id="pax_count" class="form-control"
                                min="{{ $package->pax_min }}" max="{{ $package->pax_max }}"
                                placeholder="{{ $package->pax_min }}–{{ $package->pax_max }} pax"
                                value="{{ old('pax_count') }}" required oninput="updateTotal()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name of Celebrant / Couple <span class="text-danger">*</span></label>
                            <input type="text" name="celebrant_name" class="form-control"
                                placeholder="e.g. Maria Santos"
                                value="{{ old('celebrant_name') }}" required>
                        </div>
                    </div>

                    {{-- Price Tier --}}
                    <p class="section-label"><i class="fas fa-tag me-1"></i> Choose Price per Pax <span class="text-danger">*</span></p>
                    <div class="d-flex gap-3 mb-4">
                        @foreach($prices as $pi => $price)
                        <div class="price-tier-btn {{ $pi === 0 ? 'active' : '' }}"
                            onclick="selectPrice({{ $price }}, this)">
                            <span class="amt">₱{{ number_format($price) }}</span>
                            <span class="lbl">/pax</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Food Set --}}
                    <p class="section-label"><i class="fas fa-utensils me-1"></i> Choose Food Menu Set <span class="text-danger">*</span></p>
                    <div id="set-error" class="text-danger small mb-2" style="display:none;">Please select a food menu set.</div>
                    @foreach($prices as $pi => $price)
                    @php $sets = $tiers[$price]; @endphp
                    <div class="price-panel {{ $pi === 0 ? 'active' : '' }}" id="tier-{{ $price }}">
                        <div class="row mb-3">
                            @foreach($sets as $setKey => $setData)
                            <div class="col-md-6 mb-3">
                                <div class="set-card" onclick="selectSet('{{ $setKey }}', {{ $price }}, this)">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="set-label">Set {{ $setKey }}</span>
                                        <span style="background:#4a0080;color:white;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">₱{{ number_format($price) }}/pax</span>
                                    </div>
                                    @foreach($setData['items'] as $item)
                                        <div class="menu-item {{ str_contains($item,'Choice of') ? 'choice' : '' }}">
                                            {{ str_contains($item,'Choice of') ? '✦ '.$item : '• '.$item }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    {{-- Soup, Dessert, Drink --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-bowl-food me-1" style="color:#7b2ff7;"></i> Choice of Soup <span class="text-danger">*</span></label>
                            <select name="soup_choice" class="form-select" required>
                                <option value="">-- Select Soup --</option>
                                @foreach($allSoups as $soup)
                                    <option value="{{ $soup }}" {{ old('soup_choice') == $soup ? 'selected' : '' }}>{{ $soup }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-ice-cream me-1" style="color:#7b2ff7;"></i> Choice of Dessert <span class="text-danger">*</span></label>
                            <select name="dessert_choice" class="form-select" required>
                                <option value="">-- Select Dessert --</option>
                                @foreach($allDesserts as $dessert)
                                    <option value="{{ $dessert }}" {{ old('dessert_choice') == $dessert ? 'selected' : '' }}>{{ $dessert }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-glass-water me-1" style="color:#7b2ff7;"></i> Choice of Drink <span class="text-danger">*</span></label>
                            <select name="drink_choice" class="form-select" required>
                                <option value="">-- Select Drink --</option>
                                @foreach($allDrinks as $drink)
                                    <option value="{{ $drink }}" {{ old('drink_choice') == $drink ? 'selected' : '' }}>{{ $drink }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ADDITIONAL CHARGES --}}
                    <p class="section-label"><i class="fas fa-plus-circle me-1"></i> Additional Charges / Corkage Fee <span class="text-muted fw-normal" style="font-size:0.75rem;">(optional)</span></p>
                    <p style="font-size:0.78rem;color:#9ca3af;margin-bottom:12px;">Select any add-ons you'd like to include. These will be added to your total.</p>

                    @foreach($grouped as $groupName => $groupAddons)
                    <div class="addon-section-header"><i class="fas fa-chevron-right me-1" style="font-size:10px;"></i> {{ $groupName }}</div>
                    @foreach($groupAddons as $addon)
                    <div class="addon-card" id="addon-card-{{ $addon['key'] }}">
                        <div class="d-flex align-items-start gap-3">
                            <input type="checkbox"
                                   class="addon-check"
                                   id="chk-{{ $addon['key'] }}"
                                   name="addons[{{ $addon['key'] }}][selected]"
                                   value="1"
                                   onchange="toggleAddon('{{ $addon['key'] }}', '{{ $addon['type'] }}')">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <label class="addon-title" for="chk-{{ $addon['key'] }}" style="cursor:pointer;">
                                        {{ $addon['label'] }}
                                    </label>
                                    <span class="addon-price" id="price-display-{{ $addon['key'] }}">
                                        @if($addon['type'] === 'pax')
                                            ₱{{ number_format(array_values($addon['pax_options'])[0]) }}
                                        @elseif($addon['type'] === 'qty')
                                            ₱{{ number_format($addon['price']) }}/{{ str_contains(strtolower($addon['label']), 'bottle') ? 'bottle' : (str_contains(strtolower($addon['label']), 'case') ? 'case' : 'pc') }}
                                        @else
                                            ₱{{ number_format($addon['price']) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="addon-desc">{{ $addon['desc'] }}</div>

                                @if($addon['type'] === 'pax')
                                <select class="addon-sub-select"
                                        id="pax-select-{{ $addon['key'] }}"
                                        name="addons[{{ $addon['key'] }}][pax]"
                                        onchange="updateTotal()">
                                    @foreach($addon['pax_options'] as $label => $price)
                                    <option value="{{ $price }}">{{ $label }} — ₱{{ number_format($price) }}</option>
                                    @endforeach
                                </select>

                                @elseif($addon['type'] === 'qty')
                                <div class="addon-qty-wrap" id="qty-wrap-{{ $addon['key'] }}">
                                    <span style="font-size:0.8rem;color:#6b7280;">Qty:</span>
                                    <input type="number" min="1" value="1"
                                           id="qty-{{ $addon['key'] }}"
                                           name="addons[{{ $addon['key'] }}][qty]"
                                           oninput="updateTotal()">
                                    <input type="hidden" name="addons[{{ $addon['key'] }}][price]" value="{{ $addon['price'] }}">
                                </div>

                                @else
                                <input type="hidden" name="addons[{{ $addon['key'] }}][price]" value="{{ $addon['price'] }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endforeach

                    {{-- Special Requests --}}
                    <div class="mb-4 mt-3">
                        <label class="form-label">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="special_requests" class="form-control" rows="2"
                            placeholder="Any special notes or requests?">{{ old('special_requests') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-reserve">
                        <i class="fas fa-paper-plane me-2"></i> Submit Reservation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Addon config for JS total computation
const addonConfig = {
    @foreach($addons as $addon)
    '{{ $addon['key'] }}': { type: '{{ $addon['type'] }}', price: {{ $addon['type'] !== 'pax' ? $addon['price'] : 0 }} },
    @endforeach
};

function selectPrice(price, btn) {
    document.querySelectorAll('.price-tier-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('hidden_price').value = price;
    document.querySelectorAll('.price-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tier-' + price).classList.add('active');
    document.getElementById('hidden_set').value = '';
    document.querySelectorAll('.set-card').forEach(c => c.classList.remove('active'));
    const firstCard = document.querySelector('#tier-' + price + ' .set-card');
    if (firstCard) firstCard.click();
    updateTotal();
}

function selectSet(set, price, card) {
    const panel = document.getElementById('tier-' + price);
    panel.querySelectorAll('.set-card').forEach(c => c.classList.remove('active'));
    card.classList.add('active');
    document.getElementById('hidden_set').value = set;
    document.getElementById('set-error').style.display = 'none';
}

function toggleAddon(key, type) {
    const chk  = document.getElementById('chk-' + key);
    const card = document.getElementById('addon-card-' + key);

    if (chk.checked) {
        card.classList.add('selected');
        if (type === 'pax') {
            const sel = document.getElementById('pax-select-' + key);
            if (sel) sel.classList.add('show');
        } else if (type === 'qty') {
            const wrap = document.getElementById('qty-wrap-' + key);
            if (wrap) wrap.classList.add('show');
        }
    } else {
        card.classList.remove('selected');
        if (type === 'pax') {
            const sel = document.getElementById('pax-select-' + key);
            if (sel) sel.classList.remove('show');
        } else if (type === 'qty') {
            const wrap = document.getElementById('qty-wrap-' + key);
            if (wrap) wrap.classList.remove('show');
        }
    }
    updateTotal();
}

function updateTotal() {
    const pricePerPax = parseInt(document.getElementById('hidden_price').value) || 0;
    const pax         = parseInt(document.getElementById('pax_count').value) || 0;
    const subtotal    = pricePerPax * pax;
    let   addonTotal  = 0;

    for (const [key, cfg] of Object.entries(addonConfig)) {
        const chk = document.getElementById('chk-' + key);
        if (!chk || !chk.checked) continue;

        if (cfg.type === 'pax') {
            const sel = document.getElementById('pax-select-' + key);
            if (sel) addonTotal += parseInt(sel.value) || 0;
        } else if (cfg.type === 'qty') {
            const qty = document.getElementById('qty-' + key);
            const q   = parseInt(qty ? qty.value : 1) || 1;
            addonTotal += cfg.price * q;
        } else {
            addonTotal += cfg.price;
        }
    }

    const grand = subtotal + addonTotal;
    document.getElementById('summary-price-pax').textContent = '₱' + pricePerPax.toLocaleString();
    document.getElementById('summary-pax').textContent       = pax;
    document.getElementById('summary-subtotal').textContent  = '₱' + subtotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('summary-addons').textContent    = '₱' + addonTotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('summary-total').textContent     = '₱' + grand.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('hidden_addon_total').value      = addonTotal;
}

document.addEventListener('DOMContentLoaded', function () {
    const firstActivePanel = document.querySelector('.price-panel.active');
    if (firstActivePanel) {
        const firstCard = firstActivePanel.querySelector('.set-card');
        if (firstCard) firstCard.click();
    }
    updateTotal();

    document.getElementById('reservationForm').addEventListener('submit', function (e) {
        const foodSet = document.getElementById('hidden_set').value;
        if (!foodSet) {
            e.preventDefault();
            document.getElementById('set-error').style.display = 'block';
            document.querySelector('.price-panel.active').scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>
@endsection