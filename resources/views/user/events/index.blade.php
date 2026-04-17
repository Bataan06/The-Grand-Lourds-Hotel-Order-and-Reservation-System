@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .event-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); transition: transform 0.3s; overflow: hidden; cursor: pointer; display: flex; flex-direction: column; height: 100%; }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(123,47,247,0.2); }
    .event-card .card-body { display: flex; flex-direction: column; flex: 1; }
    .event-card .card-body p { flex: 1; }
    .btn-book { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px 25px; font-weight: 700; width: 100%; margin-top: auto; }
    .btn-book:hover { opacity: 0.9; color: white; }

    /* Slideshow */
    .slide-container { position: relative; height: 220px; overflow: hidden; flex-shrink: 0; }
    .slide-container img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 1s ease-in-out; }
    .slide-container img.active { opacity: 1; }

    /* Placeholder fallback */
    .img-placeholder { height: 220px; display: flex; align-items: center; justify-content: center; font-size: 4rem; flex-shrink: 0; }
    .birthday-bg   { background: linear-gradient(135deg, #ce93d8, #7b2ff7); color: white; }
    .wedding-bg    { background: linear-gradient(135deg, #f8bbd0, #c2185b); color: white; }
    .conference-bg { background: linear-gradient(135deg, #90caf9, #1565c0); color: white; }
</style>

<div class="mb-4">
    <h2 class="page-title"><i class="fas fa-calendar-alt me-2"></i> Browse Events</h2>
    <p class="text-muted">Choose an event type to start your reservation.</p>
</div>

<div class="row">
    @forelse($events as $event)
    <div class="col-md-4 mb-4 d-flex">
        <div class="event-card card w-100">
            @php
                $name = strtolower($event->name);
                $slides = match(true) {
                    str_contains($name, 'birthday') || str_contains($name, 'christening') => ['images/birthday1.jpg','images/birthday2.jpg','images/birthday3.jpg'],
                    str_contains($name, 'wedding') => ['images/wedding1.jpg','images/wedding2.jpg','images/wedding3.jpg','images/wedding4.jpg'],
                    default => ['images/conference1.jpg','images/conference2.jpg','images/conference3.jpg'],
                };
            @endphp

            @if($event->image)
                <img src="{{ asset($event->image) }}" style="height:220px;width:100%;object-fit:cover;flex-shrink:0;" alt="{{ $event->name }}">
            @else
                <div class="slide-container">
                    @foreach($slides as $i => $slide)
                        <img src="{{ asset($slide) }}" class="{{ $i === 0 ? 'active' : '' }}" alt="{{ $event->name }}">
                    @endforeach
                </div>
            @endif

            <div class="card-body p-4">
                <h4 class="fw-bold" style="color:#4a0080;">{{ $event->name }}</h4>
                <p class="text-muted">{{ $event->description }}</p>
                <a href="{{ route('user.events.venues', $event->id) }}" class="btn btn-book mt-3">
                    <i class="fas fa-arrow-right me-2"></i> Book Now
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="fas fa-calendar fa-3x mb-3 d-block" style="color:#ce93d8;"></i>
        No events available at the moment.
    </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.slide-container').forEach(function (container) {
        const imgs = container.querySelectorAll('img');
        if (imgs.length <= 1) return;
        let current = 0;
        setInterval(function () {
            imgs[current].classList.remove('active');
            current = (current + 1) % imgs.length;
            imgs[current].classList.add('active');
        }, 3000);
    });
});
</script>
@endsection