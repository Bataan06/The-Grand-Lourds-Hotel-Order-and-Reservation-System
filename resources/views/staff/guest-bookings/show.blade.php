@extends('layouts.app')

@section('title', 'Booking #' . $booking->reference_no)

@section('content')

<style>
@media print {
    body * { visibility: hidden; }
    #printable, #printable * { visibility: visible; }
    #printable { position: absolute; top: 0; left: 0; width: 100%; padding: 20px; }
    .no-print { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #e9d5ff !important; }
    .col-lg-8 { width: 60% !important; float: left; }
    .col-lg-4 { width: 38% !important; float: right; }
    .row { display: block; }
    .card-body { padding: 12px !important; }
    .mb-4 { margin-bottom: 8px !important; }
    h6 { font-size: 11px !important; margin-bottom: 6px !important; }
    .col-md-4 { width: 33% !important; float: left; font-size: 11px !important; }
    .col-12 { width: 100% !important; clear: both; font-size: 11px !important; }
    * { font-size: 11px; }
    @page { margin: 10mm; size: A4; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <a href="{{ route('staff.guest-bookings.index') }}" class="text-decoration-none" style="color:#9ca3af;font-size:13px;">
            <i class="fas fa-arrow-left me-1"></i> Back to Bookings
        </a>
        <h4 class="fw-bold mb-0 mt-1" style="color:#2d0a4e;">{{ $booking->reference_no }}</h4>
    </div>
    <div class="d-flex align-items-center gap-2">
        @php $colors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','completed'=>'#6b7280','cancelled'=>'#ef4444']; $color = $colors[$booking->status] ?? '#6b7280'; @endphp
        <span style="background:{{ $color }}20;color:{{ $color }};padding:6px 16px;border-radius:20px;font-size:13px;font-weight:700;text-transform:capitalize;">
            {{ $booking->status }}
        </span>
        <button onclick="window.print()" class="btn btn-sm" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:600;">
            <i class="fas fa-print me-1"></i> Print
        </button>
    </div>
</div>

{{-- PRINTABLE AREA --}}
<div id="printable">

    {{-- Print Header --}}
    <div class="d-none d-print-block mb-4 text-center" style="border-bottom:2px solid #4a0080;padding-bottom:16px;margin-bottom:20px;">
        <h4 style="color:#2d0057;font-weight:800;margin:0;">The Grand Lourds Hotel</h4>
        <p style="color:#9ca3af;font-size:12px;margin:2px 0;">1 De Venecia Avenue, Nalsian, Calasiao, 2418 Pangasinan</p>
        <p style="color:#9ca3af;font-size:12px;margin:0;">0942-483-4680</p>
        <div style="margin-top:10px;">
            <span style="font-size:18px;font-weight:800;color:#4a0080;">{{ $booking->reference_no }}</span>
            &nbsp;&nbsp;
            <span style="background:{{ $color }}20;color:{{ $color }};padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;text-transform:capitalize;">{{ $booking->status }}</span>
        </div>
    </div>

<div class="row g-4">
    <div class="col-lg-8">

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
                    <div class="col-12">
                        <span style="color:#9ca3af;">Special Requests</span>
                        <div class="fw-bold">{{ $booking->special_requests ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add-ons --}}
        @php $charges = is_array($booking->additional_charges) ? $booking->additional_charges : json_decode($booking->additional_charges, true); @endphp
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

        {{-- Actions --}}
        @if($booking->status === 'pending')
        <div class="card border-0 shadow-sm no-print" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4a0080;"><i class="fas fa-check-circle me-2"></i>Update Status</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="{{ route('staff.guest-bookings.confirm', $booking->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" style="border-radius:8px;font-weight:600;">
                            <i class="fas fa-check me-1"></i> Confirm Booking
                        </button>
                    </form>
                    <form action="{{ route('staff.guest-bookings.cancel', $booking->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius:8px;font-weight:600;"
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
                    <button type="submit" class="btn btn-sm" style="background:#4a0080;color:white;border-radius:8px;font-weight:600;">
                        <i class="fas fa-check-double me-1"></i> Mark as Completed
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- Summary --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;overflow:hidden;position:sticky;top:80px;">
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

                {{-- Payment Status Display --}}
                @php
                    $pColors = ['unpaid'=>'#ef4444','partial'=>'#f59e0b','paid'=>'#10b981'];
                    $pLabels = ['unpaid'=>'Unpaid','partial'=>'Partial Payment','paid'=>'Fully Paid'];
                    $pColor  = $pColors[$booking->payment_status] ?? '#ef4444';
                    $pLabel  = $pLabels[$booking->payment_status] ?? 'Unpaid';
                    $balance = $booking->total_amount - $booking->amount_paid;
                @endphp
                <div style="margin-top:12px;padding:12px;border-radius:10px;border:1.5px solid {{ $pColor }}20;background:{{ $pColor }}08;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Payment</span>
                        <span style="background:{{ $pColor }}20;color:{{ $pColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">{{ $pLabel }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:12px;">
                        <span style="color:#9ca3af;">Amount Paid</span>
                        <span style="font-weight:700;color:#10b981;">₱{{ number_format($booking->amount_paid, 2) }}</span>
                    </div>
                    @if($booking->payment_status !== 'paid')
                    <div class="d-flex justify-content-between" style="font-size:12px;">
                        <span style="color:#9ca3af;">Balance</span>
                        <span style="font-weight:700;color:#ef4444;">₱{{ number_format($balance, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div style="background:#fef3c7;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:12px;color:#92400e;" class="no-print">
                    <i class="fas fa-clock me-1"></i> Submitted {{ $booking->created_at->diffForHumans() }}
                </div>

                {{-- Print footer signature area --}}
                <div class="d-none d-print-block mt-4" style="margin-top:30px !important;">
                    <div style="border-top:1px solid #e9d5ff;padding-top:12px;font-size:11px;color:#9ca3af;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div style="margin-bottom:30px;"></div>
                                <div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Staff Signature</div>
                            </div>
                            <div>
                                <div style="margin-bottom:30px;"></div>
                                <div style="border-top:1px solid #2d0057;padding-top:4px;font-weight:600;color:#2d0057;">Guest Signature</div>
                            </div>
                        </div>
                        <div style="margin-top:16px;text-align:center;color:#9ca3af;">
                            Printed: {{ now()->format('F d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Payment Card --}}
        <div class="card border-0 shadow-sm no-print" style="border-radius:14px;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#047857,#10b981);padding:14px 20px;color:white;font-size:0.9rem;font-weight:700;">
                <i class="fas fa-money-bill-wave me-2"></i> Update Payment
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.guest-bookings.payment', $booking->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Amount Paid (₱)</label>
                        <input type="number" name="amount_paid" step="0.01" min="0"
                               max="{{ $booking->total_amount }}"
                               value="{{ $booking->amount_paid }}"
                               class="form-control form-control-sm mt-1"
                               style="border-color:#e9d5ff;border-radius:8px;"
                               required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm mt-1"
                                style="border-color:#e9d5ff;border-radius:8px;" required>
                            <option value="unpaid"  {{ $booking->payment_status === 'unpaid'  ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ $booking->payment_status === 'partial' ? 'selected' : '' }}>Partial Payment</option>
                            <option value="paid"    {{ $booking->payment_status === 'paid'    ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:linear-gradient(135deg,#047857,#10b981);color:white;border-radius:8px;font-weight:600;">
                        <i class="fas fa-save me-1"></i> Save Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>{{-- end printable --}}

@endsection