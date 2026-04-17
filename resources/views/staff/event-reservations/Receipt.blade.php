<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }} — Grand Lourds Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #fff; color: #1f2937; font-size: 13px; padding: 30px; }

        .receipt-header { text-align: center; border-bottom: 3px solid #4a0080; padding-bottom: 16px; margin-bottom: 20px; }
        .hotel-name { font-size: 22px; font-weight: 800; color: #4a0080; letter-spacing: 1px; }
        .hotel-sub  { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .receipt-title { margin-top: 10px; font-size: 15px; font-weight: 700; color: #7b2ff7; text-transform: uppercase; letter-spacing: 2px; }
        .receipt-no { font-size: 12px; color: #9ca3af; margin-top: 4px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .info-section { background: #faf5ff; border-radius: 8px; padding: 14px 16px; border-left: 4px solid #7b2ff7; }
        .info-section h3 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #7b2ff7; margin-bottom: 10px; }
        .info-row { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #f3e8ff; }
        .info-row:last-child { border-bottom: none; }
        .info-row .lbl { color: #6b7280; font-size: 12px; }
        .info-row .val { font-weight: 600; color: #1f2937; font-size: 12px; text-align: right; }

        .amenities-section { margin-bottom: 20px; background: #faf5ff; border-radius: 8px; padding: 14px 16px; border-left: 4px solid #7b2ff7; }
        .amenities-section h3 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #7b2ff7; margin-bottom: 10px; }
        .amenities-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; }
        .amenity-item { font-size: 11px; color: #4a0080; padding: 3px 0; display: flex; align-items: flex-start; gap: 6px; }
        .amenity-item::before { content: '✓'; color: #7b2ff7; font-weight: 700; flex-shrink: 0; }

        .total-box { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 10px; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .total-box .total-label  { font-size: 13px; opacity: 0.85; }
        .total-box .total-pax    { font-size: 12px; opacity: 0.75; margin-top: 2px; }
        .total-box .total-amount { font-size: 26px; font-weight: 800; }

        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-pending   { background: #ede7f6; color: #6a0dad; }
        .status-confirmed { background: #4a0080; color: white; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .receipt-footer { text-align: center; border-top: 1px dashed #d1d5db; padding-top: 14px; color: #9ca3af; font-size: 11px; line-height: 1.7; }
        .receipt-footer strong { color: #4a0080; }

        @media print {
            body { padding: 10px; }
        }
    </style>
</head>
<body>

{{-- Receipt Header --}}
<div class="receipt-header">
    <div class="hotel-name">THE GRAND LOURDS HOTEL</div>
    <div class="hotel-sub">Calasiao, Pangasinan &nbsp;|&nbsp; Grand Events Venue</div>
    <div class="receipt-title">Official Reservation Receipt</div>
    <div class="receipt-no">
        Receipt No: <strong>#{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Issued: {{ now()->format('F d, Y \a\t g:i A') }}
    </div>
</div>

{{-- Info Grid --}}
<div class="info-grid">
    <div class="info-section">
        <h3>Guest Information</h3>
        <div class="info-row">
            <span class="lbl">Name</span>
            <span class="val">{{ $reservation->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Email</span>
            <span class="val">{{ $reservation->user->email }}</span>
        </div>
        @if($reservation->celebrant_name)
        <div class="info-row">
            <span class="lbl">Celebrant / Couple</span>
            <span class="val">{{ $reservation->celebrant_name }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="lbl">Status</span>
            <span class="val">
                <span class="status-badge status-{{ $reservation->status }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </span>
        </div>
    </div>

    <div class="info-section">
        <h3>Event Details</h3>
        <div class="info-row">
            <span class="lbl">Event Type</span>
            <span class="val">{{ $reservation->event->name }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Venue</span>
            <span class="val">{{ $reservation->venue->name }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Date</span>
            <span class="val">{{ $reservation->event_date->format('F d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Time</span>
            <span class="val">
                {{ $reservation->event_time_start
                    ? \Carbon\Carbon::parse($reservation->event_time_start)->format('g:i A')
                    : 'TBD' }}
            </span>
        </div>
        <div class="info-row">
            <span class="lbl">No. of Guests</span>
            <span class="val">{{ number_format($reservation->pax_count) }} pax</span>
        </div>
        <div class="info-row">
            <span class="lbl">Food Set</span>
            <span class="val">Set {{ $reservation->food_set }}</span>
        </div>
    </div>
</div>

{{-- Total Bill --}}
<div class="total-box">
    <div>
        <div class="total-label">Total Amount Due</div>
        <div class="total-pax">
            &#8369;{{ number_format($reservation->price_per_pax, 2) }}/pax
            &times; {{ number_format($reservation->pax_count) }} guests
        </div>
    </div>
    <div class="total-amount">&#8369;{{ number_format($reservation->total_amount, 2) }}</div>
</div>

{{-- Package Inclusions --}}
@if($reservation->package && $reservation->package->amenities)
<div class="amenities-section">
    <h3>Package Inclusions</h3>
    <div class="amenities-grid">
        @foreach($reservation->package->amenities as $amenity)
            <div class="amenity-item">{{ $amenity }}</div>
        @endforeach
    </div>
</div>
@endif

{{-- Special Requests --}}
@if($reservation->special_requests)
<div class="amenities-section">
    <h3>Special Requests / Notes</h3>
    <p style="font-size:12px;color:#374151;line-height:1.6;">{{ $reservation->special_requests }}</p>
</div>
@endif

{{-- Footer --}}
<div class="receipt-footer">
    <p><strong>The Grand Lourds Hotel</strong> — Calasiao, Pangasinan</p>
    <p>Thank you for choosing us for your special celebration!</p>
    <p>For inquiries, please contact our Events Team.</p>
    <p style="margin-top:8px;color:#d1d5db;">— This is an official reservation receipt —</p>
</div>

{{-- Auto-trigger print dialog on load --}}
<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>