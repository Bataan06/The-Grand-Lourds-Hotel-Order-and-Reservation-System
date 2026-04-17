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
        .hero-tagline { color: rgba(255,255,255,0.92); font-size: 14px; max-width: 440px; margin: 0 auto 30px; line-height: 1.65; }
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
        .tag-wedding  { background: #fce4ec; color: #880e4f; border-color: #f8bbd0; }
        .tag-birthday { background: #ede7f6; color: #4a0080; border-color: #ce93d8; }
        .tag-seminar  { background: #e8eaf6; color: #283593; border-color: #c5cae9; }
        .offer-card { border-radius: 16px; overflow: hidden; border: none; transition: transform .2s; box-shadow: 0 5px 20px rgba(0,0,0,0.08); height: 100%; }
        .offer-card:hover { transform: translateY(-4px); }
        .offer-badge-pill { position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg,#f59e0b,#d97706); color: #fff; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; letter-spacing: 1px; text-transform: uppercase; }
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
        }
        @media (max-width: 576px) { .gl-hero h1 { font-size: 30px; } }
    </style>
    {{-- ✅ TANGGAL NA: Lumang duplicate slideshow script na nasa head --}}
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
    <div class="item"><b>0942-483-4680</b></div>
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
                <div class="event-thumb" style="background:{{ $gradient }};position:relative;">
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
                    @if($event->packages->count() > 0)
                    <div class="mb-2">
                        @foreach($event->packages->take(1) as $pkg)
                            @php
                                $inclusions = is_array($pkg->inclusions) ? $pkg->inclusions : json_decode($pkg->inclusions ?? '[]', true);
                            @endphp
                            @foreach(array_slice($inclusions ?? [], 0, 6) as $inc)
                                <span class="tag">{{ $inc }}</span>
                            @endforeach
                        @endforeach
                    </div>
                    @endif
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
<section id="offers" style="padding:60px 48px;background:#faf7ff;">
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
            {{-- Duplicate --}}
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

{{-- FLOATING CHAT WIDGET --}}
<style>
.float-msg-btn { position: fixed; bottom: 28px; right: 28px; z-index: 999; }
.float-msg-btn button { width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg,#4a0080,#7b2ff7); border: none; color: white; font-size: 1.3rem; cursor: pointer; box-shadow: 0 4px 20px rgba(74,0,128,0.5); transition: all 0.2s; }
.float-msg-btn button:hover { transform: scale(1.1); }
.msg-modal { position: fixed; bottom: 96px; right: 28px; z-index: 999; width: 360px; background: white; border-radius: 16px; box-shadow: 0 8px 40px rgba(0,0,0,0.18); display: none; flex-direction: column; overflow: hidden; max-height: 580px; }
.msg-modal.show { display: flex; }
.msg-modal-header { background: linear-gradient(135deg,#2d0057,#4a0080); padding: 14px 18px; color: white; display: flex; justify-content: space-between; align-items: center; }
.msg-modal-header .title { font-family: 'Cormorant Garamond',serif; font-size: 1rem; }
.msg-modal-header .sub { font-size: 10px; opacity: 0.7; margin-top: 2px; }
.msg-modal-header button { background: none; border: none; color: white; font-size: 1rem; cursor: pointer; opacity: 0.7; }
.msg-tabs { display: flex; border-bottom: 1px solid #f0e6ff; }
.msg-tab { flex: 1; padding: 10px; font-size: 12px; font-weight: 600; color: #9b59b6; background: none; border: none; cursor: pointer; transition: all 0.2s; }
.msg-tab.active { color: #4a0080; border-bottom: 2px solid #7b2ff7; }
.msg-body { padding: 16px; overflow-y: auto; flex: 1; }
.quick-msgs { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.quick-msg-btn { background: #f5f0ff; border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 8px 12px; font-size: 12px; color: #4a0080; cursor: pointer; text-align: left; transition: all 0.2s; }
.quick-msg-btn:hover { background: #ede7f6; border-color: #a78bfa; }
.quick-msg-btn.selected { background: #4a0080; color: white; border-color: #4a0080; }
.msg-form input, .msg-form textarea { width: 100%; border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 9px 12px; font-size: 12px; margin-bottom: 8px; font-family: 'DM Sans',sans-serif; }
.msg-form input:focus, .msg-form textarea:focus { outline: none; border-color: #7b2ff7; }
.msg-form textarea { resize: none; height: 80px; }
.msg-send-btn { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 700; width: 100%; cursor: pointer; }
.msg-send-btn:hover { opacity: 0.9; }
.msg-success { text-align: center; padding: 16px 10px; display: none; }
.msg-success i { font-size: 2.5rem; color: #10b981; margin-bottom: 8px; display: block; }
.ref-box { background: #f0e6ff; border-radius: 10px; padding: 12px 14px; margin: 10px 0; text-align: center; }
.ref-box .ref-label { font-size: 10px; color: #9b59b6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
.ref-box .ref-num { font-size: 1.2rem; font-weight: 800; color: #4a0080; letter-spacing: 2px; }
.ref-box .ref-hint { font-size: 10px; color: #9ca3af; margin-top: 4px; }
.check-reply-wrap input { width: 100%; border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 9px 12px; font-size: 13px; margin-bottom: 8px; font-family: 'DM Sans',sans-serif; text-transform: uppercase; letter-spacing: 1px; }
.check-reply-wrap input:focus { outline: none; border-color: #7b2ff7; }
.reply-result { display: none; background: #faf5ff; border-radius: 10px; padding: 14px; border: 1px solid #e9d5ff; margin-top: 10px; }
.reply-result .rr-label { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #9b59b6; letter-spacing: 1px; margin-bottom: 4px; }
.reply-result .rr-msg { font-size: 12px; color: #374151; background: white; border-radius: 8px; padding: 10px; margin-bottom: 8px; }
.reply-result .rr-reply { font-size: 12px; background: #4a0080; color: white; border-radius: 8px; padding: 10px; }
.reply-pending { text-align: center; padding: 16px; color: #9b59b6; font-size: 12px; display: none; }
</style>

<div class="float-msg-btn">
    <button onclick="toggleMsgModal()" title="Send us a message">
        <i class="fas fa-comment-dots"></i>
    </button>
</div>

<div class="msg-modal" id="msgModal">
    <div class="msg-modal-header">
        <div>
            <div class="title">💬 Message Us</div>
            <div class="sub">The Grand Lourds Hotel · Usually replies within an hour</div>
        </div>
        <button onclick="toggleMsgModal()">✕</button>
    </div>

    {{-- TABS --}}
    <div class="msg-tabs">
        <button class="msg-tab active" id="tabSend" onclick="switchTab('send')">Send Message</button>
        <button class="msg-tab" id="tabCheck" onclick="switchTab('check')">Check Reply</button>
    </div>

    {{-- SEND TAB --}}
    <div class="msg-body" id="paneSend">
        <div id="msgFormWrap">
            <div style="font-size:12px;font-weight:700;color:#4a0080;margin-bottom:8px;">Quick Messages:</div>
            <div class="quick-msgs">
                <button class="quick-msg-btn" onclick="selectQuick(this,'Is the venue still available for booking?')">📅 Is the venue still available?</button>
                <button class="quick-msg-btn" onclick="selectQuick(this,'What are your current promos and special offers?')">🎁 What are your current promos?</button>
                <button class="quick-msg-btn" onclick="selectQuick(this,'What is the price per person for your packages?')">💰 What is the price per person?</button>
                <button class="quick-msg-btn" onclick="selectQuick(this,'Can I visit the venue for an ocular inspection?')">🏨 Can I visit for ocular inspection?</button>
                <button class="quick-msg-btn" onclick="selectQuick(this,'What are your payment terms?')">💳 What are your payment terms?</button>
            </div>
            <div class="msg-form">
                <input type="text" id="msgName" placeholder="Your Name *" required>
                <input type="text" id="msgPhone" placeholder="Phone Number *" required>
                <input type="email" id="msgEmail" placeholder="Email Address (optional)">
                <textarea id="msgText" placeholder="Type your message here..."></textarea>
                <button class="msg-send-btn" onclick="sendGuestMessage()">
                    <i class="fas fa-paper-plane me-1"></i> Send Message
                </button>
            </div>
        </div>
        <div class="msg-success" id="msgSuccess">
            <i class="fas fa-check-circle"></i>
            <div style="font-size:14px;font-weight:700;color:#2d0057;margin-bottom:4px;">Message Sent!</div>
            <div style="font-size:12px;color:#6b7280;margin-bottom:10px;">Save your reference number to check our reply.</div>
            <div class="ref-box">
                <div class="ref-label">Your Reference Number</div>
                <div class="ref-num" id="refNoDisplay"></div>
                <div class="ref-hint">Use this to check staff reply in the "Check Reply" tab</div>
            </div>
            <button class="msg-send-btn" style="margin-top:10px;" onclick="switchTab('check')">
                <i class="fas fa-search me-1"></i> Check Reply Now
            </button>
        </div>
    </div>

    {{-- CHECK REPLY TAB --}}
    <div class="msg-body" id="paneCheck" style="display:none;">
        <div style="font-size:12px;font-weight:700;color:#4a0080;margin-bottom:8px;">Enter your reference number:</div>
        <div class="check-reply-wrap">
            {{-- ✅ AYOS NA: maxlength 12 → 13 (MSG- = 4 chars + 8 chars = 12... actually 12 is correct but set to 13 to be safe) --}}
            <input type="text" id="refInput" placeholder="e.g. MSG-A1B2C3D4" maxlength="13">
            <button class="msg-send-btn" onclick="checkReply()">
                <i class="fas fa-search me-1"></i> Check Reply
            </button>
        </div>
        <div class="reply-result" id="replyResult">
            <div class="rr-label">Your Message</div>
            <div class="rr-msg" id="rrMsg"></div>
            <div id="rrReplyWrap">
                <div class="rr-label">Staff Reply</div>
                <div class="rr-reply" id="rrReply"></div>
                <div style="font-size:10px;color:#9ca3af;margin-top:4px;" id="rrRepliedAt"></div>
            </div>
        </div>
        <div class="reply-pending" id="replyPending">
            <i class="fas fa-clock fa-2x" style="color:#c084fc;margin-bottom:8px;display:block;"></i>
            <div style="font-weight:600;color:#4a0080;margin-bottom:4px;">No reply yet</div>
            <div style="color:#9ca3af;">Our staff will reply to your message soon. Please check back later.</div>
        </div>
    </div>
</div>

<script>
function toggleMsgModal() { document.getElementById('msgModal').classList.toggle('show'); }

// ✅ AYOS NA: Iisang slideshow script lang, nasa dulo ng page (after DOM load)
var slideIndexes = {};
function goEventSlide(cls, n) {
    var slides = document.querySelectorAll('.' + cls);
    if (slideIndexes[cls] === undefined) slideIndexes[cls] = 0;
    if (slides[slideIndexes[cls]]) slides[slideIndexes[cls]].classList.remove('active');
    slideIndexes[cls] = n;
    if (slides[n]) slides[n].classList.add('active');

    // Update dots
    var dotContainer = document.querySelector('.' + cls.split('-')[0] + '-dots');
    if (dotContainer) {
        dotContainer.querySelectorAll('.slide-dot').forEach(function(d, i) {
            d.classList.toggle('active', i === n);
        });
    }
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

function switchTab(tab) {
    document.getElementById('tabSend').classList.toggle('active', tab === 'send');
    document.getElementById('tabCheck').classList.toggle('active', tab === 'check');
    document.getElementById('paneSend').style.display  = tab === 'send'  ? 'block' : 'none';
    document.getElementById('paneCheck').style.display = tab === 'check' ? 'block' : 'none';
}

function selectQuick(btn, text) {
    document.querySelectorAll('.quick-msg-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('msgText').value = text;
}

function sendGuestMessage() {
    const name  = document.getElementById('msgName').value.trim();
    const phone = document.getElementById('msgPhone').value.trim();
    const email = document.getElementById('msgEmail').value.trim();
    const text  = document.getElementById('msgText').value.trim();
    if (!name || !phone || !text) { alert('Please fill in your name, phone, and message.'); return; }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('/guest-message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ guest_name: name, guest_phone: phone, guest_email: email || null, message: text })
    })
    .then(r => { if (!r.ok) return r.text().then(t => { throw new Error(t); }); return r.json(); })
    .then(data => {
        if (data.success) {
            document.getElementById('msgFormWrap').style.display = 'none';
            document.getElementById('msgSuccess').style.display  = 'block';
            document.getElementById('refNoDisplay').textContent  = data.reference_no;
            document.getElementById('refInput').value            = data.reference_no;
        }
    })
    .catch(() => alert('Failed to send. Please try again.'));
}

function checkReply() {
    const ref = document.getElementById('refInput').value.trim().toUpperCase();
    if (!ref) { alert('Please enter your reference number.'); return; }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('/guest-message/check', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ reference_no: ref })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('replyResult').style.display  = 'none';
        document.getElementById('replyPending').style.display = 'none';

        if (!data.found) {
            alert('Reference number not found. Please check and try again.');
            return;
        }

        document.getElementById('rrMsg').textContent = data.message;

        if (data.staff_reply) {
            document.getElementById('rrReplyWrap').style.display = 'block';
            document.getElementById('rrReply').textContent       = data.staff_reply;
            document.getElementById('rrRepliedAt').textContent   = 'Replied: ' + data.replied_at;
            document.getElementById('replyResult').style.display = 'block';
        } else {
            document.getElementById('rrReplyWrap').style.display  = 'none';
            document.getElementById('replyResult').style.display  = 'block';
            document.getElementById('replyPending').style.display = 'block';
        }
    })
    .catch(() => alert('Failed to check reply. Please try again.'));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>