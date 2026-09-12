<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #faf7ff; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        .gl-nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 48px; background: rgba(13,0,26,0.92); backdrop-filter: blur(14px); position: sticky; top: 0; z-index: 200; border-bottom: 0.5px solid rgba(192,132,252,0.2); }
        .gl-nav .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .gl-nav .logo-img { width: 42px; height: 42px; object-fit: contain; }
        .gl-nav .brand-name { font-family: 'Cormorant Garamond',serif; font-size: 17px; font-weight: 600; color: #f0e6ff; line-height: 1.1; }
        .gl-nav .brand-sub { font-size: 9px; color: #a78bfa; letter-spacing: 2px; text-transform: uppercase; }
        .gl-nav .nav-links a { color: rgba(255,255,255,0.6); font-size: 13px; text-decoration: none; margin: 0 14px; transition: color .2s; }
        .gl-nav .nav-links a:hover { color: #e9d5ff; }
        .gl-hero { position: relative; min-height: 620px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .hero-bg-photo { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center top; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(13,0,26,0.88) 0%, rgba(45,10,78,0.82) 30%, rgba(26,0,48,0.85) 70%, rgba(13,0,26,0.97) 100%); }
        .hero-content { position: relative; z-index: 3; text-align: center; padding: 0 24px; }
        .hero-logo { width: 100px; height: 100px; object-fit: contain; margin: 0 auto 16px; display: block; filter: drop-shadow(0 0 24px rgba(192,132,252,0.8)) brightness(1.2); }
        .hero-badge { display: inline-flex; align-items: center; gap: 7px; background: rgba(167,139,250,0.2); border: 0.5px solid rgba(192,132,252,0.5); color: #fff; font-size: 10px; padding: 5px 16px; border-radius: 20px; margin-bottom: 18px; letter-spacing: 2px; text-transform: uppercase; }
        .gl-hero h1 { font-family: 'Cormorant Garamond',serif; font-size: 62px; font-weight: 700; color: #fff; line-height: 1.0; margin-bottom: 8px; text-shadow: 0 2px 20px rgba(0,0,0,0.9), 0 0 40px rgba(124,58,237,0.5); }
        .gl-hero h1 em { font-style: italic; color: #e9d5ff; }
        .hero-loc { color: rgba(255,255,255,0.92); font-size: 13px; margin-bottom: 8px; }
        .hero-loc b { color: #c084fc; }
        .hero-tagline { color: rgba(255,255,255,0.92); font-size: 17px; max-width: 440px; margin: 0 auto 30px; line-height: 1.65; font-family: 'Cormorant Garamond', serif; font-style: italic; }
        .btn-reserve { background: linear-gradient(135deg,#7c3aed,#a855f7); color: #fff; border: none; padding: 14px 34px; border-radius: 6px; font-size: 15px; font-weight: 500; box-shadow: 0 0 28px rgba(124,58,237,.6); transition: all .2s; margin-right: 10px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-reserve:hover { transform: translateY(-2px); color: #fff; }
        .btn-outline-hero { background: rgba(255,255,255,0.12); color: #fff; border: 0.5px solid rgba(192,132,252,0.5); padding: 14px 30px; border-radius: 6px; font-size: 14px; text-decoration: none; display: inline-block; transition: all .2s; }
        .btn-outline-hero:hover { background: rgba(192,132,252,0.2); color: #fff; }
        .gl-strip { background: #faf7ff; border-top: 0.5px solid #e9d5ff; border-bottom: 0.5px solid #e9d5ff; padding: 14px 48px; display: flex; gap: 32px; justify-content: center; flex-wrap: wrap; }
        .gl-strip .item { font-size: 12px; color: #6b21a8; }
        .gl-strip .item b { color: #4c1d95; }
        .event-card { background: #fff; border-radius: 16px; overflow: hidden; border: 0.5px solid #e9d5ff; transition: transform .2s; height: 100%; display: flex; flex-direction: column; }
        .event-card:hover { transform: translateY(-4px); border-color: #a78bfa; }
        .event-card-body { flex: 1; display: flex; flex-direction: column; padding: 16px; }
        .event-card-body .card-bottom { margin-top: auto; padding-top: 12px; }
        .event-thumb { height: 200px; display: flex; align-items: center; justify-content: center; font-size: 52px; flex-shrink: 0; overflow: hidden; position: relative; }
        .event-thumb { cursor: zoom-in; }
        .image-lightbox { display:none; position:fixed; inset:0; z-index:2000; background:rgba(13,0,26,.9); padding:28px; align-items:center; justify-content:center; cursor:zoom-out; }
        .image-lightbox.show { display:flex; }
        .image-lightbox img { max-width:min(1100px,94vw); max-height:86vh; object-fit:contain; border-radius:12px; box-shadow:0 16px 50px rgba(0,0,0,.55); cursor:default; }
        .image-lightbox-close { position:absolute; top:16px; right:22px; border:0; background:rgba(255,255,255,.15); color:white; width:38px; height:38px; border-radius:50%; font-size:25px; line-height:1; cursor:pointer; }
        .image-lightbox-nav { position:absolute; top:50%; transform:translateY(-50%); border:0; background:rgba(255,255,255,.16); color:#fff; width:46px; height:58px; border-radius:10px; font-size:30px; cursor:pointer; }
        .image-lightbox-nav:hover, .image-lightbox-close:hover { background:rgba(255,255,255,.3); }
        .image-lightbox-prev { left:18px; }
        .image-lightbox-next { right:18px; }
        .image-lightbox-caption { position:absolute; bottom:18px; color:#fff; font-size:13px; text-align:center; }
        .ev-wedding  { background: linear-gradient(135deg,#880e4f,#e91e63); }
        .ev-birthday { background: linear-gradient(135deg,#4a0080,#7b2ff7); }
        .ev-seminar  { background: linear-gradient(135deg,#1a237e,#3f51b5); }
        .wedding-slide, .birthday-slide, .conference-slide { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.8s ease; }
        .wedding-slide.active, .birthday-slide.active, .conference-slide.active { opacity: 1; }
        .slide-dots { position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); display: flex; gap: 5px; z-index: 10; }
        .slide-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s; border: none; padding: 0; }
        .slide-dot.active { background: white; width: 16px; border-radius: 3px; }
        .btn-book { color: #fff !important; border: none; padding: 8px 20px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: opacity .2s; text-decoration: none; display: block; text-align: center; }
        .btn-book:hover { opacity: 0.85; }
        .tag { background: #f5f0ff; color: #7c3aed; font-size: 10px; padding: 2px 8px; border-radius: 4px; border: 0.5px solid #e9d5ff; display: inline-block; margin: 2px; }
        .offer-card { border-radius: 16px; overflow: hidden; border: none; transition: transform .2s; box-shadow: 0 5px 20px rgba(0,0,0,0.08); height: 100%; }
        .offer-card:hover { transform: translateY(-4px); }
        .offer-badge-pill { position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg,#f59e0b,#d97706); color: #fff; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; letter-spacing: 1px; text-transform: uppercase; }

        {{-- Track Booking Section --}}
        .track-section { background: linear-gradient(135deg,#f5f0ff,#faf7ff); border-top: 1px solid #e9d5ff; border-bottom: 1px solid #e9d5ff; padding: 20px 48px; }
        .track-input { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 10px 16px; font-size: 13px; font-family: inherit; min-width: 240px; }
        .track-input:focus { border-color: #7b2ff7; outline: none; }
        .track-btn { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px 22px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .track-btn:hover { opacity: 0.9; }

        @media (max-width: 768px) {
            .gl-nav { padding: 12px 20px; }
            .gl-nav .nav-links { display: none; }
            .gl-hero { min-height: 480px; }
            .gl-hero h1 { font-size: 38px; }
            .hero-logo { width: 70px; height: 70px; }
            section[id] { padding: 40px 20px !important; }
            .gl-strip { padding: 12px 20px; gap: 16px; }
            h2.font-serif { font-size: 28px !important; }
            footer { padding: 18px 20px !important; flex-direction: column; text-align: center; }
            .track-section { padding: 16px 20px; }
        }
        @media (max-width: 576px) { .gl-hero h1 { font-size: 30px; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="gl-nav">
    <a class="brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" class="logo-img" alt="Logo">
        <div><div class="brand-name">The Grand Lourds Hotel</div><div class="brand-sub">Calasiao · Pangasinan</div></div>
    </a>
    <div class="nav-links">
        <a href="#events">Events</a>
        <a href="#offers">Special Offers</a>
        <a href="#amenities">Amenities</a>
        <a href="#contact">Contact</a>
    </div>
</nav>

{{-- HERO --}}
<section class="gl-hero">
    <img src="{{ asset('images/hotel.jpg') }}" class="hero-bg-photo" alt="Grand Lourds Hotel">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <img src="{{ asset('images/logo.png') }}" class="hero-logo" alt="Logo">
        <div class="hero-badge">Where Every Guest is Family</div>
        <h1>The Grand<br><em>Lourds Hotel</em></h1>
        <div class="hero-loc">Judge Jose De Venecia Rd., Nalsian, <b>Calasiao, Pangasinan</b></div>
        <p class="hero-tagline">"Turn moments into memories - reserve your perfect event today."</p>
    </div>
</section>

{{-- STRIP --}}
<div class="gl-strip">
    <div class="item"><b>Weddings</b></div>
    <div class="item"><b>Birthdays / Christening</b></div>
    <div class="item"><b>Seminars & Conferences</b></div>
    <div class="item"><b>Food Service Included</b></div>
    
</div>

{{-- PAYMENT UPLOAD SECTION --}}
<div class="track-section">
    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
        <div style="font-size:13px;font-weight:700;color:#4a0080;">
            <i class="fas fa-receipt me-2"></i>Booking confirmed by staff? Enter your reference number to upload payment:
        </div>
        <input type="text" class="track-input" id="trackRefInput"
               placeholder="e.g. GLH-XXXXXXXX"
               maxlength="20" style="text-transform:uppercase;">
        <button class="track-btn" onclick="trackBooking()">
            <i class="fas fa-arrow-right me-1"></i> Go
        </button>
    </div>
</div>

{{-- EVENTS --}}
<section id="events" style="padding:60px 48px;background:#faf7ff;">
    <div class="text-center mb-5">
        <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;">What We Offer</div>
        <h2 class="font-serif" style="font-size:38px;color:#2d0a4e;">Our Event Packages</h2>
        <p style="color:#9ca3af;font-size:13px;">All packages include full inclusions, food service, A/C venue, and more</p>
    </div>

    @php
        $gradients = [
            'linear-gradient(135deg,#880e4f,#e91e63)',
            'linear-gradient(135deg,#4a0080,#7b2ff7)',
            'linear-gradient(135deg,#1a237e,#3f51b5)',
            'linear-gradient(135deg,#004d40,#00897b)',
            'linear-gradient(135deg,#bf360c,#ff5722)',
        ];
        $btnColors = ['#880e4f','#4a0080','#283593','#00695c','#bf360c'];
    @endphp

    @if($events->count() > 0)
    <div class="row g-4 justify-content-center align-items-stretch" style="max-width:960px;margin:0 auto;">
        @foreach($events as $i => $event)
        @php
            $slug = strtolower(str_replace([' ', '/'], '-', $event->name));
            $gradient = $gradients[$i % count($gradients)];
            $btnColor = $btnColors[$i % count($btnColors)];
        @endphp
        <div class="col-md-4 d-flex">
            <div class="event-card w-100">
                @php
                    $nameLower = strtolower($event->name);
                    if (str_contains($nameLower, 'wedding')) {
                        $slideClass = 'wedding-slide';
                        $imgs = ['wedding1.jpg','wedding2.jpg','wedding3.jpg','wedding4.jpg'];
                    } elseif (str_contains($nameLower, 'birthday') || str_contains($nameLower, 'christening')) {
                        $slideClass = 'birthday-slide';
                        $imgs = ['birthday1.jpg','birthday2.jpg','birthday3.jpg'];
                    } else {
                        $slideClass = 'conference-slide';
                        $imgs = ['conference1.jpg','conference2.jpg','conference3.jpg'];
                    }
                @endphp
                <div class="event-thumb" style="background:{{ $gradient }};position:relative;" onclick="openEventImage(this, event)">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}"
                             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                    @else
                        @foreach($imgs as $idx => $img)
                            <img src="{{ asset('images/' . $img) }}"
                                 class="{{ $slideClass }}{{ $idx === 0 ? ' active' : '' }}"
                                 alt="{{ $event->name }}">
                        @endforeach
                        <div class="slide-dots">
                            @foreach($imgs as $idx => $img)
                                <button class="slide-dot{{ $idx === 0 ? ' active' : '' }}"
                                        onclick="goEventSlide('{{ $slideClass }}', {{ $idx }})"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="event-card-body">
                    <span class="tag" style="background:#f5f0ff;color:#7c3aed;">{{ $event->name }}</span>
                    <h5 class="font-serif mt-2 mb-2" style="color:#2d0a4e;">{{ $event->name }} Package</h5>
                    <p style="font-size:12px;color:#9ca3af;line-height:1.6;margin-bottom:10px;">
                        {{ $event->description ?? 'Experience an unforgettable event with full inclusions, catering, and professional service.' }}
                    </p>
                    <div class="card-bottom">
                        <a href="/events/{{ $slug }}" class="btn-book" style="background:{{ $btnColor }};">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-4">
        <p style="color:#a78bfa;font-size:14px;">No events available at the moment. Check back soon!</p>
    </div>
    @endif
    <p class="text-center mt-3" style="font-size:12px;color:#a78bfa;">* All inclusions are non-convertible · Free use of venue for 4 hours</p>
</section>

{{-- SPECIAL OFFERS --}}
<section id="offers" style="padding:60px 48px;background:linear-gradient(135deg,#f5f0ff,#faf7ff);border-top:2px solid #e9d5ff;border-bottom:2px solid #e9d5ff;">
    <div class="text-center mb-5">
        <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;">Limited Time</div>
        <h2 class="font-serif" style="font-size:38px;color:#2d0a4e;">Special Offers</h2>
        <p style="color:#9ca3af;font-size:13px;">Exclusive deals and upcoming events at The Grand Lourds Hotel</p>
    </div>
    @php $offers = $offers ?? \App\Models\SpecialOffer::where('is_active', true)->latest()->get(); @endphp
    @if($offers->count() > 0)
    <div class="row g-4 justify-content-center" style="max-width:960px;margin:0 auto;">
        @foreach($offers as $offer)
        <div class="col-md-4 d-flex">
            <div class="offer-card card w-100">
                <div class="position-relative">
                    @if($offer->image)
                    <div style="height:160px;overflow:hidden;"><img src="{{ asset('storage/' . $offer->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $offer->title }}"></div>
                    @else
                    <div style="height:160px;background:{{ $offer->gradient }};display:flex;align-items:center;justify-content:center;"><i class="fas fa-tags" style="color:rgba(255,255,255,0.5);font-size:2rem;"></i></div>
                    @endif
                    <span class="offer-badge-pill">{{ $offer->badge }}</span>
                </div>
                <div class="card-body p-4">
                    <h5 class="font-serif mb-1" style="color:#2d0a4e;">{{ $offer->title }}</h5>
                    <p style="font-size:12px;color:#9ca3af;line-height:1.6;margin-bottom:12px;">{{ $offer->description }}</p>
                    <div style="background:{{ $offer->highlight_bg }};border-radius:8px;padding:10px 14px;font-size:12px;color:{{ $offer->highlight_color }};margin-bottom:12px;">🎁 {{ $offer->highlight }}</div>
                    <a href="/book" class="btn-book" style="background:{{ $offer->gradient }};">Avail Now</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-4"><p style="color:#a78bfa;font-size:14px;">No special offers available at the moment. Check back soon!</p></div>
    @endif
    <p class="text-center mt-3" style="font-size:12px;color:#a78bfa;">* Book directly · No account needed · Terms and conditions apply</p>
</section>

{{-- AMENITIES --}}
<section id="amenities" style="padding:60px 0;background:#fff;overflow:hidden;">
    <div class="text-center mb-5" style="padding:0 48px;">
        <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;">Venue Features</div>
        <h2 class="font-serif" style="font-size:38px;color:#2d0a4e;">Venue Amenities</h2>
    </div>
    <style>
        .marquee-wrap { overflow: hidden; position: relative; }
        .marquee-wrap::before, .marquee-wrap::after { content:''; position:absolute; top:0; width:80px; height:100%; z-index:2; }
        .marquee-wrap::before { left:0; background:linear-gradient(to right, #fff, transparent); }
        .marquee-wrap::after { right:0; background:linear-gradient(to left, #fff, transparent); }
        .marquee-track { display: flex; gap: 20px; width: max-content; animation: marquee 25s linear infinite; }
        .marquee-track:hover { animation-play-state: paused; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .amen-card-sm { text-align:center; padding:20px 16px; border-radius:14px; background:#faf7ff; border:0.5px solid #e9d5ff; transition:border-color .2s; width:160px; flex-shrink:0; }
        .amen-card-sm:hover { border-color:#a78bfa; }
        .amen-icon-sm { width:48px; height:48px; border-radius:50%; background:#f0e6ff; display:flex; align-items:center; justify-content:center; font-size:20px; margin:0 auto 10px; }
        .amen-card-sm h3 { font-size:12px; font-weight:600; color:#2d0a4e; margin-bottom:3px; }
        .amen-card-sm p { font-size:10px; color:#9ca3af; line-height:1.4; margin:0; }
    </style>
    <div class="marquee-wrap">
        <div class="marquee-track">
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-snowflake" style="color:#9c27b0;"></i></div><h3>Full A/C</h3><p>Air-conditioned venue</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-wifi" style="color:#9c27b0;"></i></div><h3>Free Wi-Fi</h3><p>High-speed internet</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-square-parking" style="color:#9c27b0;"></i></div><h3>Free Parking</h3><p>Secured parking area</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-shield-halved" style="color:#9c27b0;"></i></div><h3>CCTV</h3><p>24/7 security cameras</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-fire-extinguisher" style="color:#9c27b0;"></i></div><h3>Fire Safety</h3><p>Full fire prevention</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-restroom" style="color:#9c27b0;"></i></div><h3>Clean Restrooms</h3><p>Well-maintained facilities</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-elevator" style="color:#9c27b0;"></i></div><h3>Elevator</h3><p>Available for guests</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-plug" style="color:#9c27b0;"></i></div><h3>Power Backup</h3><p>Generator available</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-door-open" style="color:#9c27b0;"></i></div><h3>Private Venue</h3><p>Exclusive event space</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-bed" style="color:#9c27b0;"></i></div><h3>Hotel Rooms</h3><p>Available for guests</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-snowflake" style="color:#9c27b0;"></i></div><h3>Full A/C</h3><p>Air-conditioned venue</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-wifi" style="color:#9c27b0;"></i></div><h3>Free Wi-Fi</h3><p>High-speed internet</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-square-parking" style="color:#9c27b0;"></i></div><h3>Free Parking</h3><p>Secured parking area</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-shield-halved" style="color:#9c27b0;"></i></div><h3>CCTV</h3><p>24/7 security cameras</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-fire-extinguisher" style="color:#9c27b0;"></i></div><h3>Fire Safety</h3><p>Full fire prevention</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-restroom" style="color:#9c27b0;"></i></div><h3>Clean Restrooms</h3><p>Well-maintained facilities</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-elevator" style="color:#9c27b0;"></i></div><h3>Elevator</h3><p>Available for guests</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-plug" style="color:#9c27b0;"></i></div><h3>Power Backup</h3><p>Generator available</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-door-open" style="color:#9c27b0;"></i></div><h3>Private Venue</h3><p>Exclusive event space</p></div>
            <div class="amen-card-sm"><div class="amen-icon-sm"><i class="fas fa-bed" style="color:#9c27b0;"></i></div><h3>Hotel Rooms</h3><p>Available for guests</p></div>
        </div>
    </div>
</section>

{{-- MAP --}}
<section id="contact" style="padding:0;background:rgba(13,0,26,0.92);">
    <div style="padding:20px 48px 12px;text-align:center;">
        <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;">Find Us</div>
        <h2 class="font-serif" style="font-size:38px;color:#f0e6ff;margin-bottom:6px;">Our Location</h2>
        <p style="color:rgba(255,255,255,0.5);font-size:13px;margin-bottom:6px;"><i class="fas fa-map-marker-alt" style="color:#a78bfa;"></i> 1 De Venecia Avenue, Nalsian, Calasiao, 2418 Pangasinan</p>
        <p style="color:rgba(255,255,255,0.5);font-size:13px;"><i class="fas fa-phone" style="color:#a78bfa;"></i> 0942-483-4680</p>
    </div>
    <div style="width:100%;height:420px;">
        <iframe src="https://www.google.com/maps?q=Grand+Lourds+Hotel+Calasiao+Pangasinan&z=17&output=embed"
            width="100%" height="420" style="border:0;display:block;filter:invert(90%) hue-rotate(180deg);"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

{{-- FOOTER --}}
<footer style="background:#0d0010;padding:22px 48px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <img src="{{ asset('images/logo.png') }}" style="width:28px;height:28px;object-fit:contain;opacity:0.6;" alt="logo">
        <p style="font-size:11px;color:rgba(255,255,255,.4);margin:0;">© 2026 The Grand Lourds Hotel · 1 De Venecia Avenue, Nalsian, Calasiao, 2418 Pangasinan</p>
    </div>
    <div style="display:flex;gap:18px;">
        <a href="#" style="font-size:11px;color:rgba(255,255,255,.4);text-decoration:none;">Privacy</a>
        <a href="#" style="font-size:11px;color:rgba(255,255,255,.4);text-decoration:none;">Terms</a>
    </div>
</footer>

<div class="image-lightbox" id="imageLightbox" onclick="closeImagePreview(event)" role="dialog" aria-modal="true" aria-label="Image preview">
    <button type="button" class="image-lightbox-close" aria-label="Close image preview" onclick="closeImagePreview()">&times;</button>
    <button type="button" class="image-lightbox-nav image-lightbox-prev" aria-label="Previous photo" onclick="changePreviewImage(-1)">&#8249;</button>
    <img id="imageLightboxImg" src="" alt="">
    <button type="button" class="image-lightbox-nav image-lightbox-next" aria-label="Next photo" onclick="changePreviewImage(1)">&#8250;</button>
    <div class="image-lightbox-caption" id="imageLightboxCaption"></div>
</div>

<script>
var slideIndexes = {};
var previewImages = [];
var previewIndex = 0;
function goEventSlide(cls, n) {
    var slides = document.querySelectorAll('.' + cls);
    if (slideIndexes[cls] === undefined) slideIndexes[cls] = 0;
    if (slides[slideIndexes[cls]]) slides[slideIndexes[cls]].classList.remove('active');
    slideIndexes[cls] = n;
    if (slides[n]) slides[n].classList.add('active');
}

function openEventImage(thumb, event) {
    if (event.target.closest('.slide-dot')) return;
    previewImages = Array.from(thumb.querySelectorAll('img')).map(image => ({ src: image.src, alt: image.alt || 'Event venue photo' }));
    if (!previewImages.length) return;
    const activeImage = thumb.querySelector('img.active');
    previewIndex = activeImage ? Array.from(thumb.querySelectorAll('img')).indexOf(activeImage) : 0;
    showPreviewImage();
    document.getElementById('imageLightbox').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function showPreviewImage() {
    const image = previewImages[previewIndex];
    if (!image) return;
    document.getElementById('imageLightboxImg').src = image.src;
    document.getElementById('imageLightboxImg').alt = image.alt;
    document.getElementById('imageLightboxCaption').textContent = `${image.alt} (${previewIndex + 1} of ${previewImages.length})`;
    const showNavigation = previewImages.length > 1;
    document.querySelectorAll('.image-lightbox-nav').forEach(button => button.style.display = showNavigation ? '' : 'none');
}

function changePreviewImage(direction) {
    if (!previewImages.length) return;
    previewIndex = (previewIndex + direction + previewImages.length) % previewImages.length;
    showPreviewImage();
}

function closeImagePreview(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('imageLightbox').classList.remove('show');
    document.getElementById('imageLightboxImg').src = '';
    previewImages = [];
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    ['wedding-slide','birthday-slide','conference-slide'].forEach(function(cls) {
        var slides = document.querySelectorAll('.' + cls);
        if (slides.length > 0) {
            slideIndexes[cls] = 0;
            setInterval(function() {
                var next = ((slideIndexes[cls] || 0) + 1) % slides.length;
                goEventSlide(cls, next);
            }, 3500);
        }
    });
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') closeImagePreview();
    if (document.getElementById('imageLightbox').classList.contains('show') && event.key === 'ArrowLeft') changePreviewImage(-1);
    if (document.getElementById('imageLightbox').classList.contains('show') && event.key === 'ArrowRight') changePreviewImage(1);
});

function trackBooking() {
    const ref = document.getElementById('trackRefInput').value.trim().toUpperCase();
    if (!ref) { alert('Please enter your reference number.'); return; }
    if (!ref.startsWith('GLH-')) { alert('Invalid reference number. It should start with GLH-'); return; }
    window.location.href = '/booking/' + ref;
}

document.getElementById('trackRefInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') trackBooking();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
