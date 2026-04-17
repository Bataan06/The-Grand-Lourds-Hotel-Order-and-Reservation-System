<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }} — The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #faf7ff; }

        /* NAV */
        .gl-nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 48px; background: rgba(13,0,26,0.95); backdrop-filter: blur(14px); position: sticky; top: 0; z-index: 200; border-bottom: 0.5px solid rgba(192,132,252,0.2); }
        .gl-nav .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .gl-nav .logo-img { width: 38px; height: 38px; object-fit: contain; }
        .gl-nav .brand-name { font-family: 'Cormorant Garamond',serif; font-size: 16px; font-weight: 600; color: #f0e6ff; }
        .gl-nav .brand-sub { font-size: 9px; color: #a78bfa; letter-spacing: 2px; text-transform: uppercase; }
        .back-link { color: rgba(255,255,255,0.5); font-size: 12px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .back-link:hover { color: white; }

        /* HERO PHOTO */
        .event-hero { position: relative; height: 520px; overflow: hidden; }
        .event-hero img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .event-hero:hover img { transform: scale(1.03); }
        .event-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(13,0,26,0.75) 100%); }
        .event-hero-content { position: absolute; bottom: 0; left: 0; right: 0; padding: 36px 48px; }
        .event-hero-content h1 { font-family: 'Cormorant Garamond',serif; font-size: 3rem; color: white; font-weight: 700; margin-bottom: 8px; text-shadow: 0 2px 20px rgba(0,0,0,0.5); }
        .event-hero-content p { color: rgba(255,255,255,0.8); font-size: 14px; max-width: 600px; }

        /* PHOTO THUMBS */
        .photo-thumbs { display: flex; gap: 8px; padding: 12px 48px; background: #1a0035; overflow-x: auto; }
        .photo-thumb { width: 100px; height: 70px; border-radius: 6px; overflow: hidden; cursor: pointer; flex-shrink: 0; border: 2px solid transparent; transition: all 0.2s; }
        .photo-thumb:hover, .photo-thumb.active { border-color: #a78bfa; }
        .photo-thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* CONTENT */
        .content-wrap { max-width: 1100px; margin: 0 auto; padding: 40px 24px; display: grid; grid-template-columns: 1fr 320px; gap: 28px; align-items: start; }
        @media (max-width: 900px) { .content-wrap { grid-template-columns: 1fr; } .sticky-box { position: relative !important; top: auto !important; } }

        /* PACKAGES */
        .section-title { font-family: 'Cormorant Garamond',serif; font-size: 1.5rem; color: #2d0a4e; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid #e9d5ff; }
        .pkg-card { background: white; border-radius: 12px; border: 1px solid #e9d5ff; margin-bottom: 14px; overflow: hidden; transition: border-color 0.2s; }
        .pkg-card:hover { border-color: #a78bfa; }
        .pkg-card-header { padding: 16px 20px; background: #faf5ff; border-bottom: 1px solid #e9d5ff; display: flex; justify-content: space-between; align-items: center; }
        .pkg-card-header .pkg-name { font-family: 'Cormorant Garamond',serif; font-size: 1.1rem; color: #2d0a4e; font-weight: 700; }
        .pkg-pax { background: #ede7f6; color: #4a0080; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; }
        .pkg-body { padding: 16px 20px; }
        .inc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 16px; margin-bottom: 14px; }
        @media (max-width: 576px) { .inc-grid { grid-template-columns: 1fr; } }
        .inc-item { display: flex; gap: 8px; align-items: flex-start; font-size: 12px; color: #374151; }
        .inc-item i { color: #7c3aed; font-size: 10px; margin-top: 3px; flex-shrink: 0; }
        .price-chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .price-chip { background: #f0e6ff; color: #4a0080; font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 20px; }

        /* STICKY BOOK BOX */
        .sticky-box { position: sticky; top: 80px; }
        .book-box { background: white; border-radius: 14px; box-shadow: 0 4px 20px rgba(74,0,128,0.1); overflow: hidden; border: 1px solid #e9d5ff; }
        .book-box-header { background: linear-gradient(135deg,#2d0057,#4a0080); color: white; padding: 18px 20px; font-family: 'Cormorant Garamond',serif; font-size: 1.1rem; }
        .book-box-body { padding: 20px; }
        .book-box-body p { font-size: 12px; color: #6b7280; line-height: 1.7; margin-bottom: 16px; }
        .feature-list { list-style: none; padding: 0; margin-bottom: 18px; }
        .feature-list li { display: flex; gap: 8px; align-items: center; font-size: 12px; color: #374151; padding: 5px 0; border-bottom: 0.5px solid #f5f0ff; }
        .feature-list li:last-child { border: none; }
        .feature-list li i { color: #7c3aed; width: 14px; }
        .btn-book-now { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 13px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; text-decoration: none; display: block; text-align: center; transition: opacity 0.2s; }
        .btn-book-now:hover { opacity: 0.9; color: white; }
        .contact-box { margin-top: 14px; background: #fef3c7; border-radius: 8px; padding: 12px 14px; font-size: 12px; color: #92400e; }

        @media (max-width: 768px) {
            .gl-nav { padding: 12px 16px; }
            .event-hero { height: 300px; }
            .event-hero-content { padding: 20px 16px; }
            .event-hero-content h1 { font-size: 2rem; }
            .photo-thumbs { padding: 8px 16px; }
        }
    </style>
</head>
<body>

<nav class="gl-nav">
    <a class="brand" href="/">
        <img src="{{ asset('images/logo.png') }}" class="logo-img" alt="Logo">
        <div><div class="brand-name">The Grand Lourds Hotel</div><div class="brand-sub">Calasiao · Pangasinan</div></div>
    </a>
    <a href="/#events" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Events</a>
</nav>

{{-- HERO PHOTO with Slideshow --}}
@php
    $slug = strtolower($event->name);
    $photos = match(true) {
        str_contains($slug, 'wedding') => ['images/wedding1.jpg','images/wedding2.jpg','images/wedding3.jpg','images/wedding4.jpg'],
        str_contains($slug, 'birthday') || str_contains($slug, 'christening') => ['images/birthday1.jpg','images/birthday2.jpg','images/birthday3.jpg'],
        default => ['images/conference1.jpg','images/conference2.jpg','images/conference3.jpg'],
    };
@endphp

<div class="event-hero" id="eventHero">
    <img src="{{ asset($photos[0]) }}" id="heroImg" alt="{{ $event->name }}">
    <div class="event-hero-overlay"></div>
    <div class="event-hero-content">
        <h1>{{ $event->name }}</h1>
        <p>{{ $event->description }}</p>
    </div>
</div>

{{-- PHOTO THUMBNAILS --}}
<div class="photo-thumbs">
    @foreach($photos as $i => $photo)
    <div class="photo-thumb {{ $i === 0 ? 'active' : '' }}" onclick="changePhoto('{{ asset($photo) }}', this)">
        <img src="{{ asset($photo) }}" alt="Photo {{ $i+1 }}">
    </div>
    @endforeach
</div>

{{-- MAIN CONTENT --}}
<div class="content-wrap">

    {{-- LEFT: Packages & Inclusions --}}
    <div>
        <h2 class="section-title">Available Packages</h2>

        @forelse($packages as $pkg)
        @php $tiers = $pkg->price_tiers ?? []; @endphp
        <div class="pkg-card">
            <div class="pkg-card-header">
                <div>
                    <div class="pkg-name">{{ $pkg->venue->name }}</div>
                    <div style="font-size:11px;color:#9b59b6;margin-top:2px;">{{ $event->name }}</div>
                </div>
                <span class="pkg-pax"><i class="fas fa-users me-1"></i>{{ $pkg->pax_range }} guests</span>
            </div>
            <div class="pkg-body">
                {{-- Inclusions --}}
                @if(!empty($pkg->amenities))
                <div style="font-size:11px;font-weight:700;color:#6b21a8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">What's Included</div>
                <div class="inc-grid mb-3">
                    @foreach($pkg->amenities as $inc)
                    <div class="inc-item"><i class="fas fa-check"></i> {{ $inc }}</div>
                    @endforeach
                </div>
                @endif

                {{-- Price tiers --}}
                @if(!empty($tiers))
                <div style="font-size:11px;font-weight:700;color:#6b21a8;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Price per Person</div>
                <div class="price-chips">
                    @foreach($tiers as $price => $sets)
                    <span class="price-chip">₱{{ number_format($price) }}/pax</span>
                    @endforeach
                </div>
                @endif

                <a href="/book/{{ strtolower(str_replace([' ', '/'], ['-', '-'], $event->name)) }}?pkg={{ $pkg->id }}"
                   style="display:inline-block;margin-top:14px;background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:9px 22px;border-radius:7px;font-size:13px;font-weight:700;text-decoration:none;">
                    <i class="fas fa-calendar-check me-1"></i> Book This Package
                </a>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:#9ca3af;">
            <i class="fas fa-box-open fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
            No packages available yet. Please call us to inquire.
        </div>
        @endforelse
    </div>

    {{-- RIGHT: Sticky Book Box --}}
    <div class="sticky-box">
        <div class="book-box">
            <div class="book-box-header">
                <div>Book {{ $event->name }}</div>
                <div style="font-size:11px;opacity:0.7;margin-top:3px;">The Grand Lourds Hotel</div>
            </div>
            <div class="book-box-body">
                <p>{{ $event->description ?? 'Celebrate your special occasion with us. Full venue, catering, and event coordination included.' }}</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Full venue setup included</li>
                    <li><i class="fas fa-utensils"></i> Food service & catering</li>
                    <li><i class="fas fa-clock"></i> Free use of venue for 4 hours</li>
                    <li><i class="fas fa-user-tie"></i> Uniformed banquet staff</li>
                    <li><i class="fas fa-snowflake"></i> Air-conditioned venue</li>
                    <li><i class="fas fa-square-parking"></i> Free parking</li>
                </ul>
                <a href="/book/{{ strtolower(str_replace([' ', '/'], ['-', '-'], $event->name)) }}" class="btn-book-now">
                    <i class="fas fa-calendar-check me-2"></i> Book Now
                </a>
                <div class="contact-box">
                    <i class="fas fa-phone me-1"></i> <strong>0942-483-4680</strong><br>
                    <span style="font-size:11px;">Call us for inquiries & reservations</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changePhoto(src, thumb) {
    document.getElementById('heroImg').src = src;
    document.querySelectorAll('.photo-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>