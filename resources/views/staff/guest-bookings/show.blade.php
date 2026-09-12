@extends('layouts.app')

@section('title', 'Booking #' . $booking->reference_no)

@section('content')

@php
    $charges = is_array($booking->additional_charges)
        ? $booking->additional_charges
        : json_decode($booking->additional_charges, true);
    $charges = $charges ?? [];

    $allAddons = [
        ['key'=>'grazing_table',   'label'=>'Grazing Table',                               'price'=>10000],
        ['key'=>'shabu_station',   'label'=>'Shabu-Shabu Station',                         'price'=>12500],
        ['key'=>'upgraded_setup',  'label'=>'Upgraded Set Up (Couple Minds Events Studio)', 'price'=>15000],
        ['key'=>'photography',     'label'=>'Photography (starts at)',                      'price'=>30000],
        ['key'=>'photo_booth',     'label'=>'Photo Booth (starts at)',                      'price'=>4500],
        ['key'=>'led_wall',        'label'=>'LED Wall',                                     'price'=>15000],
        ['key'=>'lechon_baboy',    'label'=>'Lechon Baboy (per pc)',                        'price'=>1000],
        ['key'=>'sweet_buffet',    'label'=>'Sweet Buffet / Fruits',                        'price'=>5000],
        ['key'=>'outside_stylist', 'label'=>'Outside Stylist',                              'price'=>2000],
        ['key'=>'full_band',       'label'=>'Full Band',                                    'price'=>3000],
        ['key'=>'liquor_bottle',   'label'=>'Liquor (per Bottle)',                          'price'=>200],
        ['key'=>'liquor_case',     'label'=>'Liquor (per Case)',                            'price'=>500],
        ['key'=>'food_cart',       'label'=>'Food Cart',                                    'price'=>1500],
        ['key'=>'photo_booth_elec','label'=>'Photo Booth Electricity Charge',               'price'=>1000],
        ['key'=>'exceeding_hour',  'label'=>'Exceeding Hour',                               'price'=>3000],
    ];

    $proofColors = [
        'none'      => ['bg'=>'#f5f5f5', 'color'=>'#9ca3af', 'label'=>'No Proof Yet'],
        'submitted' => ['bg'=>'#fef3c7', 'color'=>'#d97706', 'label'=>'Proof Submitted'],
        'verified'  => ['bg'=>'#d1fae5', 'color'=>'#065f46', 'label'=>'Verified'],
        'rejected'  => ['bg'=>'#fee2e2', 'color'=>'#991b1b', 'label'=>'Rejected'],
    ];
    $proofStatus = $booking->payment_proof_status ?? 'none';
    $proofInfo   = $proofColors[$proofStatus] ?? $proofColors['none'];

    $packageTiers = $booking->package?->price_tiers ?? [];
    $priceKey = (string) (int) $booking->price_per_pax;
    $selectedMenu = $packageTiers[$priceKey][$booking->food_set] ?? [];
    $kitchenItems = $selectedMenu['items'] ?? [];
    $kitchenChoices = [];
    foreach (explode('|', $booking->special_requests ?? '') as $requestPart) {
        if (preg_match('/^\\s*(Soup|Dessert|Drink):\\s*(.+)$/i', trim($requestPart), $match)) {
            $kitchenChoices[ucfirst(strtolower($match[1]))] = trim($match[2]);
        }
    }

    $methodIcons = [
        'cash'  => '💵',
        'gcash' => '📱',
        'card'  => '💳',
        'bank'  => '🏦',
        'maya'  => '💜',
    ];
    $methodLabels = [
        'cash'  => 'Cash',
        'gcash' => 'GCash',
        'card'  => 'Credit / Debit Card',
        'bank'  => 'Bank Transfer',
        'maya'  => 'Maya',
    ];
@endphp

<style>
/* ── Shared print base ── */
@media print {
    body * { visibility: hidden; }
    #printable, #printable * { visibility: visible; }
    #printable { position: absolute; top: 0; left: 0; width: 100%; padding: 10px; }
    .no-print { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #e9d5ff !important; margin-bottom: 8px !important; page-break-inside: avoid; }
    .card-body { padding: 10px 14px !important; }
    h6 { font-size: 11px !important; margin-bottom: 6px !important; }
    * { font-size: 11px; }
    @page { margin: 8mm; size: A4; }

    /* Stack columns for single-page print */
    .row { display: block !important; }
    .col-lg-8, .col-lg-4 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; display: block !important; }
    .col-md-4 { width: 33.33% !important; display: inline-block !important; vertical-align: top; }

    /* ── EVENT-ONLY: hide payment block, booking summary card, and payment signature ── */
    body.print-event-only .payment-print-section { display: none !important; }
    body.print-event-only .signature-block-payment { display: none !important; }
    body.print-event-only .booking-summary-card { display: none !important; }

    /* ── FULL: show everything ── */
    body.print-full .payment-print-section { display: block !important; }
    body.print-full .booking-summary-card { display: block !important; }

    body.print-kitchen #printable > :not(.kitchen-print-section) { display: none !important; }
    body.print-kitchen .kitchen-print-section { display: block !important; }
}

.kitchen-print-section { display:none; }

.edit-field { border:1.5px solid #e9d5ff; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit; width:100%; background:white; transition:border-color 0.2s; }
.edit-field:focus { border-color:#7b2ff7; outline:none; box-shadow:0 0 0 3px rgba(123,47,247,0.1); }
.edit-field:disabled { background:#f9fafb; color:#9ca3af; }
.edit-select { border:1.5px solid #e9d5ff; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit; width:100%; background:white; }
.edit-select:focus { border-color:#7b2ff7; outline:none; }
.field-label { font-size:11px; color:#9ca3af; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
.addon-row-edit { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:0.5px solid #f5f0ff; flex-wrap:wrap; }
.addon-row-edit:last-child { border:none; }
.edit-card { border-radius:14px; border:1px solid #e9d5ff !important; }

/* Print dropdown button */
.print-dropdown { position:relative; display:inline-block; }
.print-dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 6px);
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(74,0,128,0.18);
    border: 1px solid #e9d5ff;
    min-width: 270px;
    z-index: 999;
    overflow: hidden;
}
.print-dropdown-menu.show { display: block; }
.print-dropdown-item {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px;
    font-size: 13px; font-weight: 600; color: #2d0a4e;
    cursor: pointer;
    border-bottom: 1px solid #f5f0ff;
    transition: background 0.15s;
    text-decoration: none;
}
.print-dropdown-item:last-child { border-bottom: none; }
.print-dropdown-item:hover { background: #f5f0ff; }
.print-dropdown-item .pi-icon { width: 30px; height: 30px; border-radius: 8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
.print-dropdown-item .pi-desc { font-size:10px; color:#9ca3af; font-weight:400; display:block; margin-top:1px; }
</style>

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        @php
            $cameFromDashboard = request('return_to') === 'dashboard';
            $backUrl = $cameFromDashboard
                ? route('staff.dashboard')
                : route('staff.guest-bookings.index');
        @endphp
        <a href="{{ $backUrl }}" class="text-decoration-none" style="color:#9ca3af;font-size:13px;">
            <i class="fas fa-arrow-left me-1"></i> Back to {{ $cameFromDashboard ? 'Dashboard' : 'Bookings' }}
        </a>
        <h4 class="fw-bold mb-0 mt-1" style="color:#2d0a4e;">{{ $booking->reference_no }}</h4>
    </div>
    <div class="d-flex align-items-center gap-2">
        @php
            $colors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','completed'=>'#6b7280','cancelled'=>'#ef4444'];
            $color = $colors[$booking->status] ?? '#6b7280';
        @endphp
        <span style="background:{{ $color }}20;color:{{ $color }};padding:6px 16px;border-radius:20px;font-size:13px;font-weight:700;text-transform:capitalize;">
            {{ $booking->status }}
        </span>
        <button onclick="toggleEditMode()" id="editBtn" class="btn btn-sm"
                style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:600;">
            <i class="fas fa-pen me-1"></i> Edit
        </button>

        {{-- ── Print Dropdown ── --}}
        <div class="print-dropdown" id="printDropdownWrap">
            <button onclick="togglePrintMenu()" class="btn btn-sm"
                    style="background:linear-gradient(135deg,#6d28d9,#a855f7);color:white;border-radius:8px;font-weight:600;">
                <i class="fas fa-print me-1"></i> Print <i class="fas fa-chevron-down ms-1" style="font-size:10px;"></i>
            </button>
            <div class="print-dropdown-menu" id="printDropdownMenu">
                <div class="print-dropdown-item" onclick="doPrint('event')">
                    <div class="pi-icon" style="background:#ede9fe;">📋</div>
                    <div>
                        Event Details Only
                        <span class="pi-desc">For the event coordinator — no payment info</span>
                    </div>
                </div>
                <div class="print-dropdown-item" onclick="doPrint('full')">
                    <div class="pi-icon" style="background:#d1fae5;">💳</div>
                    <div>
                        Full Booking + Payment
                        <span class="pi-desc">For guest &amp; accounting — includes payment summary</span>
                    </div>
                </div>
                <div class="print-dropdown-item" onclick="doPrint('kitchen')">
                    <div class="pi-icon" style="background:#fef3c7;color:#92400e;"><i class="fas fa-utensils"></i></div>
                    <div>Kitchen / Food Preparation<span class="pi-desc">Menu, Food Set, and meal choices</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="printable">

<section class="kitchen-print-section">
    <div style="border-bottom:3px solid #4a0080;padding-bottom:10px;margin-bottom:16px;">
        <div style="font-size:20px;font-weight:800;color:#2d0057;">KITCHEN FOOD PREPARATION SLIP</div>
        <div style="font-size:12px;color:#6b7280;">The Grand Lourds Hotel · Ref. {{ $booking->reference_no }}</div>
    </div>
    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;font-size:13px;">
        <tr><td style="padding:5px 0;color:#6b7280;width:150px;">Event / Celebrant</td><td style="font-weight:700;">{{ $booking->event->name }} — {{ $booking->celebrant_name }}</td></tr>
        <tr><td style="padding:5px 0;color:#6b7280;">Schedule</td><td style="font-weight:700;">{{ $booking->event_date->format('F d, Y') }} · {{ $booking->event_time_start ? \Carbon\Carbon::parse($booking->event_time_start)->format('g:i A') : 'TBD' }}</td></tr>
        <tr><td style="padding:5px 0;color:#6b7280;">Guests</td><td style="font-weight:800;font-size:16px;">{{ number_format($booking->pax_count) }} pax</td></tr>
        <tr><td style="padding:5px 0;color:#6b7280;">Food Set</td><td style="font-weight:800;font-size:16px;">Set {{ $booking->food_set }} · ₱{{ number_format($booking->price_per_pax) }}/pax</td></tr>
    </table>
    <div style="background:#f5f0ff;border-left:5px solid #4a0080;padding:12px 16px;margin-bottom:15px;">
        <div style="font-weight:800;color:#4a0080;margin-bottom:7px;">MENU TO PREPARE</div>
        @forelse($kitchenItems as $item)<div style="padding:3px 0;font-size:14px;">□ {{ $item }}</div>
        @empty<div style="font-size:13px;color:#6b7280;">Menu items are not configured for this Food Set.</div>
        @endforelse
    </div>
    <div style="border:1px solid #e9d5ff;border-radius:8px;padding:12px 16px;">
        <div style="font-weight:800;color:#4a0080;margin-bottom:7px;">GUEST MEAL CHOICES</div>
        @forelse($kitchenChoices as $label => $choice)<div style="padding:3px 0;font-size:14px;"><strong>{{ $label }}:</strong> {{ $choice }}</div>
        @empty<div style="font-size:13px;color:#6b7280;">No specific soup, dessert, or drink choice recorded.</div>
        @endforelse
    </div>
    <div style="margin-top:28px;font-size:11px;color:#6b7280;display:flex;justify-content:space-between;"><span>Prepared by: ____________________</span><span>Printed: {{ now()->format('M d, Y g:i A') }}</span></div>
</section>

{{-- ── Print Header (shared) ── --}}
<div class="d-none d-print-block mb-3 text-center" style="border-bottom:2px solid #4a0080;padding-bottom:12px;margin-bottom:14px;">
    <h4 style="color:#2d0057;font-weight:800;margin:0;">The Grand Lourds Hotel</h4>
    <p style="color:#9ca3af;font-size:11px;margin:2px 0;">1 De Venecia Avenue, Nalsian, Calasiao, 2418 Pangasinan</p>
    <p style="color:#9ca3af;font-size:11px;margin:0;">0942-483-4680</p>
    <div style="margin-top:8px;">
        <span style="font-size:16px;font-weight:800;color:#4a0080;">{{ $booking->reference_no }}</span>
        &nbsp;&nbsp;
        <span style="background:{{ $color }}20;color:{{ $color }};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:capitalize;">{{ $booking->status }}</span>
    </div>
</div>

<div class="row g-4">
<div class="col-lg-8">

    {{-- ===== VIEW MODE ===== --}}
    <div id="viewMode">

        {{-- Guest Info --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-user me-2"></i>Guest Information</h6>
                <div class="row g-2" style="font-size:13px;">
                    <div class="col-md-4"><span style="color:#9ca3af;">Name</span><div class="fw-bold">{{ $booking->guest_name }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Phone</span><div class="fw-bold">{{ $booking->guest_phone }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Email</span><div class="fw-bold">{{ $booking->guest_email }}</div></div>
                </div>
            </div>
        </div>

        {{-- Event Info --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-calendar me-2"></i>Event Details</h6>
                <div class="row g-2" style="font-size:13px;">
                    <div class="col-md-4"><span style="color:#9ca3af;">Event</span><div class="fw-bold">{{ $booking->event->name }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Venue</span><div class="fw-bold">{{ $booking->venue->name }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Celebrant/Couple</span><div class="fw-bold">{{ $booking->celebrant_name }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Date</span><div class="fw-bold">{{ $booking->event_date->format('F d, Y') }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Time</span><div class="fw-bold">{{ $booking->event_time_start ? \Carbon\Carbon::parse($booking->event_time_start)->format('h:i A') : '—' }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">No. of Guests</span><div class="fw-bold">{{ number_format($booking->pax_count) }} pax</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Food Set</span><div class="fw-bold">Set {{ $booking->food_set }}</div></div>
                    <div class="col-md-4"><span style="color:#9ca3af;">Price/pax</span><div class="fw-bold">₱{{ number_format($booking->price_per_pax, 2) }}</div></div>
                    <div class="col-12"><span style="color:#9ca3af;">Special Requests</span><div class="fw-bold">{{ $booking->special_requests ?? '—' }}</div></div>
                </div>
            </div>
        </div>

        {{-- Add-ons --}}
        @if(!empty($charges))
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-plus-circle me-2"></i>Additional Charges</h6>
                <table class="table table-sm mb-0" style="font-size:13px;">
                    <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($charges as $addon)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $addon['key'])) }}</td>
                            <td>₱{{ number_format($addon['price']) }}</td>
                            <td>{{ $addon['qty'] ?? 1 }}</td>
                            <td>₱{{ number_format($addon['price'] * ($addon['qty'] ?? 1)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Event-only signature block — only shows when printing event-only --}}
        <div class="d-none d-print-block signature-block-event" style="margin-top:20px;">
            <div style="border-top:1px solid #e9d5ff;padding-top:12px;font-size:11px;color:#9ca3af;">
                <div style="font-size:10px;color:#6b7280;margin-bottom:10px;text-align:center;font-style:italic;">
                    For event coordinator use only
                </div>
                <div class="d-flex justify-content-between">
                    <div><div style="margin-bottom:28px;"></div><div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Staff Signature</div></div>
                    <div><div style="margin-bottom:28px;"></div><div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Coordinator Signature</div></div>
                </div>
                <div style="margin-top:14px;text-align:center;color:#9ca3af;">Printed: {{ now()->format('F d, Y h:i A') }}</div>
            </div>
        </div>

        {{-- Payment Proof --}}
        <div class="card border-0 shadow-sm mb-4 no-print" style="border-radius:14px;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#2d0057,#4a0080);padding:14px 20px;color:white;font-size:14px;font-weight:700;display:flex;justify-content:space-between;align-items:center;">
                <span><i class="fas fa-receipt me-2"></i> Payment Proof</span>
                <span style="background:{{ $proofInfo['bg'] }};color:{{ $proofInfo['color'] }};padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;">
                    {{ $proofInfo['label'] }}
                </span>
            </div>
            <div class="card-body p-4">
                @if($proofStatus === 'none')
                    <div style="text-align:center;padding:12px 0;color:#9ca3af;font-size:13px;">
                        <i class="fas fa-clock fa-2x mb-2 d-block" style="color:#d8b4fe;"></i>
                        Guest has not uploaded payment proof yet.
                    </div>
                @elseif($proofStatus === 'submitted')
                    <p style="font-size:13px;color:#6b7280;margin-bottom:14px;">Guest submitted a payment screenshot. Please verify:</p>
                    @if($booking->payment_proof)
                    <div style="text-align:center;margin-bottom:16px;">
                        <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                             style="max-width:100%;max-height:360px;border-radius:10px;border:1.5px solid #e9d5ff;cursor:pointer;"
                             onclick="window.open(this.src,'_blank')" title="Click to view full size">
                        <div style="font-size:11px;color:#9ca3af;margin-top:6px;"><i class="fas fa-search-plus me-1"></i> Click image to view full size</div>
                    </div>
                    @endif
                    <div class="d-flex gap-2">
                        <form action="{{ route('staff.guest-bookings.verify-payment', $booking->id) }}" method="POST" style="flex:1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm w-100"
                                    style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:600;padding:10px;">
                                <i class="fas fa-check me-1"></i> Verify Payment
                            </button>
                        </form>
                        <form action="{{ route('staff.guest-bookings.reject-payment', $booking->id) }}" method="POST" style="flex:1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm w-100"
                                    style="background:linear-gradient(135deg,#5b21b6,#7c3aed);color:white;border-radius:8px;font-weight:600;padding:10px;"
                                    onclick="return confirm('Reject this payment proof? Guest will need to re-upload.')">
                                <i class="fas fa-times me-1"></i> Reject
                            </button>
                        </form>
                    </div>
                @elseif($proofStatus === 'verified')
                    <div style="text-align:center;padding:10px 0;">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#7b2ff7;"></i>
                        <div style="font-size:13px;font-weight:700;color:#2d0a4e;margin-bottom:4px;">Payment Verified ✅</div>
                        <div style="font-size:12px;color:#9ca3af;">This booking's downpayment has been verified.</div>
                    </div>
                    @if($booking->payment_proof)
                    <div style="text-align:center;margin-top:12px;">
                        <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                             style="max-width:100%;max-height:240px;border-radius:10px;border:1.5px solid #e9d5ff;cursor:pointer;"
                             onclick="window.open(this.src,'_blank')">
                    </div>
                    @endif
                @elseif($proofStatus === 'rejected')
                    <div style="background:#fee2e2;border-radius:10px;padding:12px 14px;font-size:12px;color:#991b1b;">
                        <i class="fas fa-times-circle me-1"></i> Payment proof was rejected. Waiting for guest to re-upload.
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Actions --}}
        @if($booking->status === 'pending')
        <div class="card border-0 shadow-sm no-print" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-check-circle me-2"></i>Update Status</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="{{ route('staff.guest-bookings.confirm', $booking->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm"
                                style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:600;">
                            <i class="fas fa-check me-1"></i> Confirm Booking
                        </button>
                    </form>
                    <form action="{{ route('staff.guest-bookings.cancel', $booking->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm"
                                style="background:linear-gradient(135deg,#5b21b6,#7c3aed);color:white;border-radius:8px;font-weight:600;"
                                onclick="return confirm('Cancel this booking?')">
                            <i class="fas fa-times me-1"></i> Cancel Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @elseif($booking->status === 'confirmed')
        <div class="card border-0 shadow-sm no-print" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-flag-checkered me-2"></i>Mark as Completed</h6>
                <form action="{{ route('staff.guest-bookings.complete', $booking->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#6d28d9,#a855f7);color:white;border-radius:8px;font-weight:600;">
                        <i class="fas fa-check-double me-1"></i> Mark as Completed
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>{{-- end viewMode --}}

    {{-- ===== EDIT MODE ===== --}}
    <div id="editMode" style="display:none;">
        <form action="{{ route('staff.guest-bookings.update', $booking->id) }}" method="POST">
        @csrf @method('PATCH')

        <div class="card border-0 shadow-sm mb-4 edit-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-user me-2"></i>Guest Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="field-label">Full Name</div>
                        <input type="text" name="guest_name" class="edit-field" value="{{ $booking->guest_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Phone Number</div>
                        <input type="text" name="guest_phone" class="edit-field" value="{{ $booking->guest_phone }}"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="11" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Email Address</div>
                        <input type="email" name="guest_email" class="edit-field" value="{{ $booking->guest_email }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 edit-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-calendar me-2"></i>Event Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="field-label">Event</div>
                        <input type="text" class="edit-field" value="{{ $booking->event->name }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Venue</div>
                        <input type="text" class="edit-field" value="{{ $booking->venue->name }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Celebrant / Couple</div>
                        <input type="text" name="celebrant_name" class="edit-field" value="{{ $booking->celebrant_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Event Date</div>
                        <input type="date" name="event_date" class="edit-field" value="{{ $booking->event_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Event Time</div>
                        <select name="event_time_start" class="edit-select" required>
                            @foreach(['08:00'=>'8:00 AM','08:30'=>'8:30 AM','09:00'=>'9:00 AM','09:30'=>'9:30 AM','10:00'=>'10:00 AM','10:30'=>'10:30 AM','11:00'=>'11:00 AM','11:30'=>'11:30 AM','12:00'=>'12:00 PM','12:30'=>'12:30 PM','13:00'=>'1:00 PM','13:30'=>'1:30 PM','14:00'=>'2:00 PM','14:30'=>'2:30 PM','15:00'=>'3:00 PM','15:30'=>'3:30 PM','16:00'=>'4:00 PM','16:30'=>'4:30 PM','17:00'=>'5:00 PM'] as $val => $lbl)
                            <option value="{{ $val }}" {{ \Carbon\Carbon::parse($booking->event_time_start)->format('H:i') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">No. of Guests</div>
                        <input type="number" name="pax_count" class="edit-field" value="{{ $booking->pax_count }}"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Food Set</div>
                        <select name="food_set" class="edit-select" required>
                            @foreach(['A','B','C','D'] as $set)
                            <option value="{{ $set }}" {{ $booking->food_set === $set ? 'selected' : '' }}>Set {{ $set }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Price/pax</div>
                        <input type="text" class="edit-field" value="₱{{ number_format($booking->price_per_pax, 2) }}" disabled>
                    </div>
                    <div class="col-12">
                        <div class="field-label">Special Requests</div>
                        <textarea name="special_requests" class="edit-field" rows="2">{{ $booking->special_requests }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 edit-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;">
                    <i class="fas fa-plus-circle me-2"></i>Additional Charges
                    <span style="font-size:11px;color:#9ca3af;font-weight:400;"> — check to add / uncheck to remove</span>
                </h6>

                @php $gEx = collect($charges)->firstWhere('key','grazing_table'); @endphp
                <div class="addon-row-edit">
                    <input type="checkbox" name="addons[grazing_table][selected]" id="chk-grazing_table" value="1"
                           {{ $gEx ? 'checked' : '' }} onchange="toggleTier('grazing_table',this.checked)"
                           style="accent-color:#7b2ff7;width:15px;height:15px;flex-shrink:0;">
                    <label for="chk-grazing_table" style="flex:1;font-size:13px;font-weight:600;cursor:pointer;margin:0;">Grazing Table</label>
                    <span id="price-grazing_table" style="color:#4a0080;font-weight:700;font-size:13px;min-width:70px;text-align:right;">
                        {{ $gEx ? '₱'.number_format($gEx['price']) : '—' }}
                    </span>
                    <input type="hidden" name="addons[grazing_table][price]" id="hidden-grazing_table" value="{{ $gEx['price'] ?? 0 }}">
                    <input type="hidden" name="addons[grazing_table][qty]" value="1">
                    <input type="hidden" name="addons[grazing_table][key]" value="grazing_table">
                    <div id="tier-grazing_table" style="{{ $gEx ? 'display:block' : 'display:none' }};width:100%;padding:6px 0 0 26px;">
                        <select onchange="setTier('grazing_table',this.value)" class="edit-select" style="max-width:220px;font-size:12px;">
                            <option value="">— Select Pax —</option>
                            <option value="10000" {{ ($gEx['price'] ?? 0)==10000 ? 'selected':'' }}>50 pax — ₱10,000</option>
                            <option value="15000" {{ ($gEx['price'] ?? 0)==15000 ? 'selected':'' }}>100 pax — ₱15,000</option>
                            <option value="20000" {{ ($gEx['price'] ?? 0)==20000 ? 'selected':'' }}>150 pax — ₱20,000</option>
                            <option value="25000" {{ ($gEx['price'] ?? 0)==25000 ? 'selected':'' }}>200 pax — ₱25,000</option>
                            <option value="30000" {{ ($gEx['price'] ?? 0)==30000 ? 'selected':'' }}>250 pax — ₱30,000</option>
                        </select>
                    </div>
                </div>

                @php $sEx = collect($charges)->firstWhere('key','shabu_station'); @endphp
                <div class="addon-row-edit">
                    <input type="checkbox" name="addons[shabu_station][selected]" id="chk-shabu_station" value="1"
                           {{ $sEx ? 'checked' : '' }} onchange="toggleTier('shabu_station',this.checked)"
                           style="accent-color:#7b2ff7;width:15px;height:15px;flex-shrink:0;">
                    <label for="chk-shabu_station" style="flex:1;font-size:13px;font-weight:600;cursor:pointer;margin:0;">Shabu-Shabu Station</label>
                    <span id="price-shabu_station" style="color:#4a0080;font-weight:700;font-size:13px;min-width:70px;text-align:right;">
                        {{ $sEx ? '₱'.number_format($sEx['price']) : '—' }}
                    </span>
                    <input type="hidden" name="addons[shabu_station][price]" id="hidden-shabu_station" value="{{ $sEx['price'] ?? 0 }}">
                    <input type="hidden" name="addons[shabu_station][qty]" value="1">
                    <input type="hidden" name="addons[shabu_station][key]" value="shabu_station">
                    <div id="tier-shabu_station" style="{{ $sEx ? 'display:block' : 'display:none' }};width:100%;padding:6px 0 0 26px;">
                        <select onchange="setTier('shabu_station',this.value)" class="edit-select" style="max-width:220px;font-size:12px;">
                            <option value="">— Select Pax —</option>
                            <option value="12500" {{ ($sEx['price'] ?? 0)==12500 ? 'selected':'' }}>100 pax — ₱12,500</option>
                            <option value="15000" {{ ($sEx['price'] ?? 0)==15000 ? 'selected':'' }}>150 pax — ₱15,000</option>
                            <option value="17500" {{ ($sEx['price'] ?? 0)==17500 ? 'selected':'' }}>200 pax — ₱17,500</option>
                            <option value="20000" {{ ($sEx['price'] ?? 0)==20000 ? 'selected':'' }}>250 pax — ₱20,000</option>
                        </select>
                    </div>
                </div>

                @foreach($allAddons as $addon)
                @if(in_array($addon['key'], ['grazing_table','shabu_station'])) @continue @endif
                @php $ex = collect($charges)->firstWhere('key', $addon['key']); @endphp
                <div class="addon-row-edit">
                    <input type="checkbox" name="addons[{{ $addon['key'] }}][selected]"
                           id="chk-{{ $addon['key'] }}" value="1"
                           {{ $ex ? 'checked' : '' }}
                           style="accent-color:#7b2ff7;width:15px;height:15px;flex-shrink:0;">
                    <label for="chk-{{ $addon['key'] }}" style="flex:1;font-size:13px;font-weight:600;cursor:pointer;margin:0;">
                        {{ $addon['label'] }}
                    </label>
                    <span style="color:#4a0080;font-weight:700;font-size:13px;min-width:70px;text-align:right;">
                        ₱{{ number_format($addon['price']) }}
                    </span>
                    <input type="hidden" name="addons[{{ $addon['key'] }}][price]" value="{{ $addon['price'] }}">
                    <input type="hidden" name="addons[{{ $addon['key'] }}][qty]" value="1">
                    <input type="hidden" name="addons[{{ $addon['key'] }}][key]" value="{{ $addon['key'] }}">
                </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-sm px-4"
                    style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:700;">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
            <button type="button" onclick="toggleEditMode()" class="btn btn-sm px-4"
                    style="background:#f5f0ff;color:#4a0080;border-radius:8px;font-weight:600;border:1px solid #e9d5ff;">
                <i class="fas fa-times me-1"></i> Cancel
            </button>
        </div>

        </form>
    </div>{{-- end editMode --}}

</div>

<div class="col-lg-4">

    {{-- ══════════════════════════════════════ --}}
    {{-- BOOKING SUMMARY                        --}}
    {{-- booking-summary-card: hidden in event-only print --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mb-3 booking-summary-card" style="border-radius:14px;overflow:hidden;position:sticky;top:80px;">
        <div style="background:linear-gradient(135deg,#2d0057,#4a0080);padding:16px 20px;color:white;font-family:'Cormorant Garamond',serif;font-size:1.1rem;">
            Booking Summary
        </div>
        <div class="card-body p-4">
            <div style="font-size:12px;">
                <div class="d-flex justify-content-between py-2 border-bottom"><span style="color:#9ca3af;">Package Total</span><span class="fw-bold">₱{{ number_format($booking->price_per_pax * $booking->pax_count, 2) }}</span></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><span style="color:#9ca3af;">Add-ons</span><span class="fw-bold">₱{{ number_format($booking->additional_total, 2) }}</span></div>
                <div class="d-flex justify-content-between py-2 mt-1">
                    <span style="color:#4a0080;font-weight:700;font-size:14px;">TOTAL</span>
                    <span style="color:#4a0080;font-weight:800;font-size:1.2rem;">₱{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            @php
                $pColors = ['unpaid'=>'#ef4444','partial'=>'#f59e0b','paid'=>'#10b981'];
                $pLabels = ['unpaid'=>'Unpaid','partial'=>'Partial Payment','paid'=>'Fully Paid'];
                $pColor  = $pColors[$booking->payment_status] ?? '#ef4444';
                $pLabel  = $pLabels[$booking->payment_status] ?? 'Unpaid';
                $balance = $booking->total_amount - $booking->amount_paid;
            @endphp

            {{-- Payment block — hidden in event-only print --}}
            <div class="payment-print-section" style="margin-top:12px;padding:12px;border-radius:10px;border:1.5px solid #e9d5ff;background:#faf5ff;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Payment</span>
                    <span style="background:{{ $pColor }}20;color:{{ $pColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">{{ $pLabel }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1" style="font-size:12px;">
                    <span style="color:#9ca3af;">Amount Paid</span>
                    <span style="font-weight:700;color:#6d28d9;">₱{{ number_format($booking->amount_paid, 2) }}</span>
                </div>
                @if($booking->payment_status !== 'paid')
                <div class="d-flex justify-content-between mb-1" style="font-size:12px;">
                    <span style="color:#9ca3af;">Balance</span>
                    <span style="font-weight:700;color:#7c3aed;">₱{{ number_format($balance, 2) }}</span>
                </div>
                @endif
                @if(!empty($booking->payment_method))
                <div class="d-flex justify-content-between align-items-center mb-1 mt-2 pt-2" style="font-size:12px;border-top:0.5px solid #e9d5ff;">
                    <span style="color:#9ca3af;">Method</span>
                    <span style="font-weight:700;color:#4a0080;">
                        {{ $methodIcons[$booking->payment_method] ?? '' }}
                        {{ $methodLabels[$booking->payment_method] ?? ucfirst($booking->payment_method) }}
                    </span>
                </div>
                @endif
                @if(!empty($booking->payment_reference))
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:12px;">
                    <span style="color:#9ca3af;">Ref No.</span>
                    <span style="font-weight:700;color:#4a0080;font-family:monospace;letter-spacing:0.5px;">
                        {{ $booking->payment_reference }}
                    </span>
                </div>
                @endif
                @if(!empty($booking->payment_notes))
                <div class="mt-2 pt-2" style="border-top:0.5px solid #e9d5ff;font-size:11px;color:#6b7280;font-style:italic;">
                    <i class="fas fa-sticky-note me-1" style="color:#a78bfa;"></i>{{ $booking->payment_notes }}
                </div>
                @endif
            </div>

            <div style="background:#f0e6ff;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:12px;color:#6b21a8;" class="no-print">
                <i class="fas fa-clock me-1"></i> Submitted {{ $booking->created_at->diffForHumans() }}
            </div>

            {{-- Signature block for Full print (payment included) --}}
            <div class="d-none d-print-block signature-block-payment" style="margin-top:20px;">
                <div style="border-top:1px solid #e9d5ff;padding-top:12px;font-size:11px;color:#9ca3af;">
                    <div class="d-flex justify-content-between">
                        <div><div style="margin-bottom:28px;"></div><div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Staff Signature</div></div>
                        <div><div style="margin-bottom:28px;"></div><div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Guest Signature</div></div>
                    </div>
                    <div style="margin-top:14px;text-align:center;color:#9ca3af;">Printed: {{ now()->format('F d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- UPDATE PAYMENT                         --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm no-print" style="border-radius:14px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#4a0080,#7b2ff7);padding:14px 20px;color:white;font-size:0.9rem;font-weight:700;">
            <i class="fas fa-money-bill-wave me-2"></i> Update Payment
        </div>
        <div class="card-body p-4">
            <form action="{{ route('staff.guest-bookings.payment', $booking->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Amount Paid (₱)</label>
                    <input type="number" name="amount_paid" step="0.01" min="0"
                           max="{{ $booking->total_amount }}"
                           value="{{ $booking->amount_paid }}"
                           class="form-control form-control-sm mt-1"
                           style="border-color:#e9d5ff;border-radius:8px;" required>
                </div>
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Payment Status</label>
                    <select name="payment_status" class="form-select form-select-sm mt-1"
                            style="border-color:#e9d5ff;border-radius:8px;" required>
                        <option value="unpaid"  {{ $booking->payment_status === 'unpaid'  ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ $booking->payment_status === 'partial' ? 'selected' : '' }}>Partial Payment</option>
                        <option value="paid"    {{ $booking->payment_status === 'paid'    ? 'selected' : '' }}>Fully Paid</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Payment Method</label>
                    <select name="payment_method" id="paymentMethodSelect"
                            class="form-select form-select-sm mt-1"
                            style="border-color:#e9d5ff;border-radius:8px;"
                            onchange="toggleRefField(this.value)">
                        <option value="">— Select Method —</option>
                        <option value="cash"  {{ ($booking->payment_method ?? '') === 'cash'  ? 'selected' : '' }}>💵 Cash</option>
                        <option value="gcash" {{ ($booking->payment_method ?? '') === 'gcash' ? 'selected' : '' }}>📱 GCash</option>
                        <option value="card"  {{ ($booking->payment_method ?? '') === 'card'  ? 'selected' : '' }}>💳 Credit / Debit Card</option>
                        <option value="bank"  {{ ($booking->payment_method ?? '') === 'bank'  ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="maya"  {{ ($booking->payment_method ?? '') === 'maya'  ? 'selected' : '' }}>💜 Maya</option>
                    </select>
                </div>
                <div class="mb-3" id="refNumberWrap"
                     style="{{ in_array($booking->payment_method ?? '', ['gcash','card','bank','maya']) ? 'display:block' : 'display:none' }}">
                    <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">
                        Reference / Transaction No.
                    </label>
                    <input type="text" name="payment_reference" id="paymentRefInput"
                           value="{{ $booking->payment_reference ?? '' }}"
                           placeholder="e.g. 1234567890"
                           class="form-control form-control-sm mt-1"
                           style="border-color:#e9d5ff;border-radius:8px;font-family:monospace;letter-spacing:1px;">
                    <div style="font-size:10px;color:#9ca3af;margin-top:4px;">
                        <i class="fas fa-info-circle me-1"></i>
                        GCash / Maya reference number or bank transaction ID
                    </div>
                </div>
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">
                        Notes <span style="font-weight:400;text-transform:none;">(optional)</span>
                    </label>
                    <textarea name="payment_notes" rows="2"
                              class="form-control form-control-sm mt-1"
                              style="border-color:#e9d5ff;border-radius:8px;font-size:12px;resize:none;"
                              placeholder="e.g. Downpayment only, balance on event day">{{ $booking->payment_notes ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-sm w-100"
                        style="background:linear-gradient(135deg,#6d28d9,#a855f7);color:white;border-radius:8px;font-weight:600;padding:10px;">
                    <i class="fas fa-save me-1"></i> Save Payment
                </button>
            </form>
        </div>
    </div>

</div>{{-- end col-lg-4 --}}
</div>{{-- end row --}}

</div>{{-- end printable --}}

<script>
function toggleEditMode() {
    const view = document.getElementById('viewMode');
    const edit = document.getElementById('editMode');
    const btn  = document.getElementById('editBtn');
    const isEditing = edit.style.display !== 'none';
    if (isEditing) {
        view.style.display = 'block';
        edit.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-pen me-1"></i> Edit';
        btn.style.background = 'linear-gradient(135deg,#4a0080,#7b2ff7)';
    } else {
        view.style.display = 'none';
        edit.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-times me-1"></i> Cancel Edit';
        btn.style.background = 'linear-gradient(135deg,#5b21b6,#7c3aed)';
        edit.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
function toggleTier(key, show) {
    document.getElementById('tier-' + key).style.display = show ? 'block' : 'none';
    if (!show) {
        document.getElementById('hidden-' + key).value = 0;
        document.getElementById('price-' + key).textContent = '—';
    }
}
function setTier(key, value) {
    const price = parseInt(value) || 0;
    document.getElementById('hidden-' + key).value = price;
    document.getElementById('price-' + key).textContent = price > 0 ? '₱' + price.toLocaleString('en-PH') : '—';
}
function toggleRefField(method) {
    const wrap  = document.getElementById('refNumberWrap');
    const input = document.getElementById('paymentRefInput');
    const needsRef = ['gcash', 'card', 'bank', 'maya'].includes(method);
    wrap.style.display  = needsRef ? 'block' : 'none';
    input.required      = needsRef;
}
function togglePrintMenu() {
    document.getElementById('printDropdownMenu').classList.toggle('show');
}
function doPrint(mode) {
    document.getElementById('printDropdownMenu').classList.remove('show');
    document.body.classList.remove('print-event-only', 'print-full', 'print-kitchen');
    document.body.classList.add(mode === 'event' ? 'print-event-only' : mode === 'kitchen' ? 'print-kitchen' : 'print-full');
    window.print();
    setTimeout(function() {
        document.body.classList.remove('print-event-only', 'print-full', 'print-kitchen');
    }, 1000);
}
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 400);
        });
    }, 3000);

    const sel = document.getElementById('paymentMethodSelect');
    if (sel) toggleRefField(sel.value);

    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('printDropdownWrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('printDropdownMenu').classList.remove('show');
        }
    });
});
</script>

@endsection
