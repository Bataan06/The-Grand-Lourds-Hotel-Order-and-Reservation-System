<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<title>Booking {{ $booking->reference_no }} — The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #faf7ff; min-height: 100vh; }
        .gl-nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 48px; background: rgba(13,0,26,0.95); position: sticky; top: 0; z-index: 200; }
        .gl-nav .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .gl-nav .logo-img { width: 36px; height: 36px; object-fit: contain; }
        .gl-nav .brand-name { font-family: 'Cormorant Garamond',serif; font-size: 16px; font-weight: 600; color: #f0e6ff; }
        .gl-nav .brand-sub { font-size: 9px; color: #a78bfa; letter-spacing: 2px; text-transform: uppercase; }
        .back-link { color: rgba(255,255,255,0.5); font-size: 12px; text-decoration: none; }
        .back-link:hover { color: white; }
        .page-wrap { max-width: 680px; margin: 36px auto; padding: 0 16px 60px; }
        .card-box { background: white; border-radius: 16px; box-shadow: 0 4px 24px rgba(74,0,128,0.07); border: 1px solid #e9d5ff; overflow: hidden; margin-bottom: 20px; }
        .card-hdr { background: linear-gradient(135deg,#2d0057,#4a0080); padding: 16px 22px; color: white; font-family: 'Cormorant Garamond',serif; font-size: 1.1rem; }
        .card-bd { padding: 22px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 0.5px solid #f5f0ff; font-size: 13px; }
        .info-row:last-child { border: none; }
        .info-label { color: #9ca3af; }
        .info-val { font-weight: 700; color: #2d0a4e; text-align: right; }

        /* ── Payment Method Selector ── */
        .pay-method-card {
            border: 2px solid #e9d5ff;
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .pay-method-card:hover { border-color: #7b2ff7; background: #faf5ff; }
        .pay-method-card.selected { border-color: #4a0080; background: #f0e6ff; }
        .method-icon-wrap {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
            transition: all 0.2s;
        }
        .pay-method-card.selected .method-icon-wrap { background: #4a0080; color: white; }
        .pay-method-card:not(.selected) .method-icon-wrap { background: #f3f0ff; color: #9ca3af; }
        .method-title { font-size: 14px; font-weight: 700; color: #2d0a4e; }
        .method-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; }
        .radio-circle {
            width: 18px; height: 18px; border-radius: 50%;
            border: 2px solid #d1d5db;
            margin-left: auto; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .pay-method-card.selected .radio-circle { border-color: #4a0080; background: #4a0080; }
        .pay-method-card.selected .radio-circle::after {
            content: ''; width: 6px; height: 6px;
            background: white; border-radius: 50%; display: block;
        }

        /* ── Details panel ── */
        .method-details {
            display: none;
            border: 1.5px solid #e9d5ff;
            border-top: none;
            border-radius: 0 0 12px 12px;
            overflow: hidden;
            margin-bottom: 10px;
            animation: slideDown 0.2s ease;
        }
        .method-details.open { display: block; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .detail-header {
            padding: 10px 16px;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.5px;
            display: flex; align-items: center; gap: 8px;
            color: white;
        }
        .detail-row {
            display: flex; justify-content: space-between;
            padding: 10px 16px; background: white;
            border-top: 1px solid #f3e8ff;
            font-size: 13px;
        }
        .detail-row .dk { color: #9ca3af; }
        .detail-row .dv { font-weight: 700; color: #2d0a4e; }
        .gcash-big { font-size: 1.5rem; font-weight: 900; letter-spacing: 3px; color: #1e0040; padding: 12px 16px 0; background: white; }

        /* ── Upload ── */
        .upload-zone { border: 2px dashed #e9d5ff; border-radius: 12px; padding: 28px; text-align: center; cursor: pointer; transition: all 0.2s; background: #faf5ff; }
        .upload-zone:hover, .upload-zone.dragover { border-color: #7b2ff7; background: #f0e6ff; }
        .upload-zone i { font-size: 2rem; color: #c084fc; margin-bottom: 8px; display: block; }
        .btn-upload { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 13px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; margin-top: 14px; }
        .btn-upload:hover { opacity: 0.9; }
        .btn-upload:disabled { opacity: 0.5; cursor: not-allowed; }
        .preview-img { max-width: 100%; max-height: 280px; border-radius: 8px; margin-top: 12px; display: none; border: 1px solid #e9d5ff; }

        @media (max-width: 576px) { .gl-nav { padding: 12px 16px; } }
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

<div class="page-wrap">

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:14px 18px;margin-bottom:16px;color:#065f46;font-size:13px;" class="auto-dismiss">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:14px 18px;margin-bottom:16px;color:#991b1b;font-size:13px;" class="auto-dismiss">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
    @endif

    @php
        $statusColors = ['pending'=>'#f59e0b','confirmed'=>'#7b2ff7','completed'=>'#6b7280','cancelled'=>'#ef4444'];
        $statusColor = $statusColors[$booking->status] ?? '#6b7280';
        $downpayment = $booking->total_amount * 0.5;
    @endphp

    {{-- Booking Summary --}}
    <div class="card-box">
        <div class="card-hdr" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div>Booking Summary</div>
                <div style="font-size:11px;opacity:0.7;margin-top:2px;letter-spacing:1px;">{{ $booking->reference_no }}</div>
            </div>
            <span style="background:{{ $statusColor }}30;color:{{ $statusColor }};padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;text-transform:capitalize;">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        <div class="card-bd">
            <div class="info-row"><span class="info-label">Guest Name</span><span class="info-val">{{ $booking->guest_name }}</span></div>
            <div class="info-row"><span class="info-label">Celebrant / Couple</span><span class="info-val">{{ $booking->celebrant_name }}</span></div>
            <div class="info-row"><span class="info-label">Event</span><span class="info-val">{{ $booking->event->name }}</span></div>
            <div class="info-row"><span class="info-label">Venue</span><span class="info-val">{{ $booking->venue->name }}</span></div>
            <div class="info-row"><span class="info-label">Date</span><span class="info-val">{{ $booking->event_date->format('F d, Y') }}</span></div>
            <div class="info-row"><span class="info-label">Time</span><span class="info-val">{{ $booking->event_time_start ? \Carbon\Carbon::parse($booking->event_time_start)->format('h:i A') : '—' }}</span></div>
            <div class="info-row"><span class="info-label">No. of Guests</span><span class="info-val">{{ $booking->pax_count }} pax</span></div>
            <div class="info-row"><span class="info-label">Food Set</span><span class="info-val">Set {{ $booking->food_set }} · ₱{{ number_format($booking->price_per_pax) }}/pax</span></div>
            @php $charges = is_array($booking->additional_charges) ? $booking->additional_charges : json_decode($booking->additional_charges, true); @endphp
            @if(!empty($charges))
            @foreach($charges as $addon)
            <div class="info-row">
                <span class="info-label">+ {{ ucwords(str_replace('_',' ',$addon['key'])) }}</span>
                <span class="info-val">₱{{ number_format($addon['price'] * ($addon['qty'] ?? 1)) }}</span>
            </div>
            @endforeach
            @endif
            <div class="info-row" style="margin-top:4px;">
                <span style="font-weight:700;color:#4a0080;font-size:14px;">TOTAL AMOUNT</span>
                <span style="font-weight:800;color:#4a0080;font-size:1.2rem;">₱{{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- ===== PENDING or CONFIRMED ===== --}}
    @if($booking->status === 'pending' || $booking->status === 'confirmed')

    @if($booking->status === 'pending')
    <div style="background:#fef3c7;border-radius:10px;padding:12px 18px;margin-bottom:16px;font-size:12px;color:#92400e;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-phone" style="font-size:1.1rem;"></i>
        <div>Our staff will call you at <strong>{{ $booking->guest_phone }}</strong> to discuss your booking. After your call, pay your downpayment below and upload the screenshot.</div>
    </div>
    @endif

    {{-- Upload Proof --}}
    <div class="card-box">
        <div class="card-hdr"><i class="fas fa-upload me-2"></i> Upload Payment Screenshot</div>
        <div class="card-bd">

            @if($booking->payment_proof_status === 'submitted')
            <div style="text-align:center;padding:16px 0;">
                <div style="width:64px;height:64px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fas fa-check" style="font-size:1.5rem;color:#10b981;"></i>
                </div>
                <div style="font-size:15px;font-weight:700;color:#2d0a4e;margin-bottom:6px;">Payment Proof Submitted!</div>
                <div style="font-size:12px;color:#9ca3af;margin-bottom:16px;">Our staff is reviewing your payment. We will contact you once verified.</div>
                @if($booking->payment_proof)
                <img src="{{ asset('storage/' . $booking->payment_proof) }}" style="max-width:100%;max-height:280px;border-radius:8px;border:1px solid #e9d5ff;">
                @endif
            </div>

            @elseif($booking->payment_proof_status === 'verified')
            <div style="text-align:center;padding:16px 0;">
                <div style="width:64px;height:64px;border-radius:50%;background:#f0e6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fas fa-check-double" style="font-size:1.5rem;color:#7b2ff7;"></i>
                </div>
                <div style="font-size:15px;font-weight:700;color:#2d0a4e;margin-bottom:4px;">Payment Verified ✅</div>
                <div style="font-size:12px;color:#9ca3af;">Your payment has been verified. Your booking is all set!</div>
            </div>

            @else
            @if($booking->payment_proof_status === 'rejected')
            <div style="background:#fee2e2;border-radius:10px;padding:12px 14px;margin-bottom:14px;font-size:12px;color:#991b1b;">
                <i class="fas fa-times-circle me-1"></i> Your previous payment proof was rejected. Please upload a new one.
            </div>
            @endif

            <p style="font-size:13px;color:#6b7280;margin-bottom:14px;">After sending payment, upload your screenshot here:</p>

            <form action="{{ route('guest.booking.upload', $booking->reference_no) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" id="paymentMethodInput" value="gcash">

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('proofFile').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div style="font-size:13px;font-weight:600;color:#4a0080;" id="uploadLabel">Click to upload payment screenshot</div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">JPG, PNG, or PDF · Max 5MB</div>
                    <img id="previewImg" class="preview-img" alt="Preview">
                </div>
                <input type="file" name="payment_proof" id="proofFile" accept="image/*,.pdf" style="display:none;" onchange="previewFile(this)">

                @error('payment_proof')
                <div style="color:#dc2626;font-size:12px;margin-top:6px;"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-upload" id="uploadBtn" disabled>
                    <i class="fas fa-upload me-2"></i> Submit Payment Proof
                </button>
            </form>
            @endif

        </div>
    </div>

    {{-- ===== CANCELLED ===== --}}
    @elseif($booking->status === 'cancelled')
    <div class="card-box">
        <div class="card-bd" style="text-align:center;padding:32px;">
            <div style="width:72px;height:72px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-times-circle" style="font-size:1.8rem;color:#ef4444;"></i>
            </div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;color:#2d0a4e;font-weight:700;margin-bottom:8px;">Booking Cancelled</div>
            <p style="font-size:13px;color:#6b7280;line-height:1.7;">
                This booking has been cancelled. Please contact us at <strong>0942-483-4680</strong> for more information.
            </p>
            <a href="/" style="display:inline-block;margin-top:20px;background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:10px 28px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">
                <i class="fas fa-calendar-plus me-1"></i> Book Again
            </a>
        </div>
    </div>

    {{-- ===== COMPLETED ===== --}}
    @elseif($booking->status === 'completed')
    <div class="card-box">
        <div class="card-bd" style="text-align:center;padding:32px;">
            <div style="width:72px;height:72px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-flag-checkered" style="font-size:1.8rem;color:#10b981;"></i>
            </div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;color:#2d0a4e;font-weight:700;margin-bottom:8px;">Event Completed! 🎉</div>
            <p style="font-size:13px;color:#6b7280;line-height:1.7;">
                Thank you for choosing The Grand Lourds Hotel! We hope your event was a wonderful success.
            </p>
        </div>
    </div>
    @endif

    <div style="text-align:center;margin-top:8px;">
        <a href="/" style="font-size:12px;color:#9ca3af;text-decoration:none;">
            <i class="fas fa-home me-1"></i> Back to Website
        </a>
    </div>

</div>

<script>
function selectMethod(method) {
    // Cards
    document.getElementById('opt-gcash').classList.toggle('selected', method === 'gcash');
    document.getElementById('opt-bank').classList.toggle('selected', method === 'bank');
    // Details
    document.getElementById('details-gcash').classList.toggle('open', method === 'gcash');
    document.getElementById('details-bank').classList.toggle('open', method === 'bank');
    // Hidden input for form
    document.getElementById('selectedMethodInput').value = method;
    document.getElementById('paymentMethodInput').value = method;
}

function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('uploadBtn').disabled = false;
    document.getElementById('uploadLabel').textContent = file.name;
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('previewImg');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

const zone = document.getElementById('uploadZone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            const input = document.getElementById('proofFile');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewFile(input);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.auto-dismiss').forEach(el => {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
