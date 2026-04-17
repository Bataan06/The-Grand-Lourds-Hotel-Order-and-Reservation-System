@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .venue-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); transition: transform 0.3s; overflow: hidden; }
    .venue-card:hover { transform: translateY(-5px); }
    .img-placeholder { height: 200px; display: flex; align-items: center; justify-content: center; font-size: 4rem; background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; }
    .btn-select { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px 25px; font-weight: 700; width: 100%; }
    .btn-select:hover { opacity: 0.9; color: white; }
    .breadcrumb-item a { color: #7b2ff7; text-decoration: none; }
</style>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.events.index') }}">Events</a></li>
        <li class="breadcrumb-item active">{{ $event->name }}</li>
    </ol>
</nav>

<div class="mb-4">
    <h2 class="page-title"><i class="fas fa-map-marker-alt me-2"></i> Select Venue</h2>
    <p class="text-muted">Choose a venue for your <strong>{{ $event->name }}</strong>.</p>
</div>

<div class="row">
    @foreach($venues as $venue)
    <div class="col-md-6 mb-4">
        <div class="venue-card card h-100">
            @if($venue->image)
                <img src="{{ asset($venue->image) }}" style="height:200px; object-fit:cover;">
            @else
                <div class="img-placeholder">
                    <i class="fas fa-building"></i>
                </div>
            @endif
            <div class="card-body p-4">
                <h4 class="fw-bold" style="color:#4a0080;">{{ $venue->name }}</h4>
                <p class="text-muted">{{ $venue->description }}</p>
                <a href="{{ route('user.events.packages', [$event->id, $venue->id]) }}" class="btn btn-select mt-2">
                    <i class="fas fa-arrow-right me-2"></i> Select Venue
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection