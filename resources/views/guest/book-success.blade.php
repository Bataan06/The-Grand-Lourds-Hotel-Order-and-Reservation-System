<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Submitted — The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: linear-gradient(135deg, #0d0018, #1a0035, #2d0057); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .success-card { background: white; border-radius: 20px; padding: 40px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.4); }
        .check-circle { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg,#10b981,#059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: white; }
        .ref-box { background: #f5f0ff; border-radius: 10px; padding: 16px; margin: 20px 0; border: 1.5px dashed #a78bfa; }
        .ref-box .ref-label { font-size: 11px; color: #9b59b6; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px; }
        .ref-box .ref-num { font-size: 1.4rem; font-weight: 800; color: #4a0080; letter-spacing: 2px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 0.5px solid #f3e8ff; font-size: 13px; }
        .detail-row:last-child { border: none; }
        .detail-label { color: #9ca3af; }
        .detail-val { font-weight: 600; color: #2d0057; }
        .btn-home { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 10px; padding: 12px 28px; font-weight: 700; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn-home:hover { opacity: 0.9; color: white; }
    </style>
</head>
<body>
<div class="success-card">
    <div class="check-circle"><i class="fas fa-check"></i></div>
    <h3 style="font-family:'Cormorant Garamond',serif;color:#2d0057;font-size:1.6rem;margin-bottom:8px;">Booking Request Submitted!</h3>
    <p style="color:#9ca3af;font-size:13px;margin-bottom:4px;">Thank you, <strong style="color:#4a0080;">{{ $booking->guest_name }}</strong>!</p>
    <p style="color:#9ca3af;font-size:13px;">Our staff will contact you shortly to confirm your reservation.</p>

    <div class="ref-box">
        <div class="ref-label">Reference Number</div>
        <div class="ref-num">{{ $booking->reference_no }}</div>
        <div style="font-size:11px;color:#9b59b6;margin-top:4px;">Please save this for your records</div>
    </div>

    <div style="text-align:left;">
        <div class="detail-row"><span class="detail-label">Event</span><span class="detail-val">{{ $booking->event->name }}</span></div>
        <div class="detail-row"><span class="detail-label">Venue</span><span class="detail-val">{{ $booking->venue->name }}</span></div>
        <div class="detail-row"><span class="detail-label">Celebrant/Couple</span><span class="detail-val">{{ $booking->celebrant_name }}</span></div>
        <div class="detail-row"><span class="detail-label">Date</span><span class="detail-val">{{ $booking->event_date->format('F d, Y') }}</span></div>
        <div class="detail-row"><span class="detail-label">Guests</span><span class="detail-val">{{ number_format($booking->pax_count) }} pax</span></div>
        <div class="detail-row"><span class="detail-label">Food Set</span><span class="detail-val">Set {{ $booking->food_set }}</span></div>
        <div class="detail-row"><span class="detail-label">Total Amount</span><span class="detail-val" style="color:#4a0080;font-size:1rem;">₱{{ number_format($booking->total_amount, 2) }}</span></div>
        <div class="detail-row"><span class="detail-label">Contact</span><span class="detail-val">{{ $booking->guest_phone }}</span></div>
    </div>

    <div style="background:#fef3c7;border-radius:8px;padding:12px;margin-top:16px;font-size:12px;color:#92400e;">
        <i class="fas fa-phone me-1"></i> We will call you at <strong>{{ $booking->guest_phone }}</strong> to confirm.
    </div>

    <a href="/" class="btn-home"><i class="fas fa-home me-2"></i> Back to Website</a>
</div>
</body>
</html>