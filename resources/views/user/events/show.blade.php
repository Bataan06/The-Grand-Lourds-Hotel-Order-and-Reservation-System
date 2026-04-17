@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .card-header-violet { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; font-weight: 600; }
    .info-label { font-weight: 700; color: #6a0dad; }
    .amenity-item { background: #f3e5f5; border-radius: 8px; padding: 6px 12px; margin-bottom: 6px; color: #4a0080; font-size: 0.85rem; }
    .badge-pending   { background: #ede7f6; color: #6a0dad; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
    .badge-confirmed { background: #4a0080; color: white; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
    .badge-cancelled { background: #d500f9; color: white; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
    .badge-completed { background: #9c27b0; color: white; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-calendar-check me-2"></i> Reservation Details</h2>
    <a href="{{ route('user.reservations.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-info-circle me-2"></i> Reservation Info</div>
            <div class="card-body p-4">
                <p><span class="info-label">Event:</span> {{ $reservation->event->name }}</p>
                <p><span class="info-label">Venue:</span> {{ $reservation->venue->name }}</p>
                <p><span class="info-label">Package:</span> {{ $reservation->package->pax_range }} pax</p>
                <p><span class="info-label">Event Date:</span> {{ $reservation->event_date->format('F d, Y') }}</p>
                <p><span class="info-label">Event Time:</span> {{ $reservation->event_time ?? 'TBD' }}</p>
                <p><span class="info-label">No. of Guests:</span> {{ $reservation->pax_count }}</p>
                <p><span class="info-label">Food Set:</span> Set {{ $reservation->food_set }} — ₱{{ number_format($reservation->price_per_pax) }}/pax</p>
                @if($reservation->celebrant_name)
                <p><span class="info-label">Celebrant/Couple:</span> {{ $reservation->celebrant_name }}</p>
                @endif
                <p><span class="info-label">Special Requests:</span> {{ $reservation->special_requests ?? 'None' }}</p>
                <p><span class="info-label">Status:</span>
                    @if($reservation->status === 'pending')
                        <span class="badge-pending ms-1">Pending</span>
                    @elseif($reservation->status === 'confirmed')
                        <span class="badge-confirmed ms-1">Confirmed</span>
                    @elseif($reservation->status === 'cancelled')
                        <span class="badge-cancelled ms-1">Cancelled</span>
                    @else
                        <span class="badge-completed ms-1">Completed</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-check-circle me-2"></i> Package Inclusions</div>
            <div class="card-body p-4">
                @foreach($reservation->package->amenities as $amenity)
                    <div class="amenity-item"><i class="fas fa-check me-2" style="color:#7b2ff7;"></i>{{ $amenity }}</div>
                @endforeach
            </div>
        </div>

        @php $menuKey = 'menu_set_' . strtolower($reservation->food_set); $menu = $reservation->package->$menuKey; @endphp
        @if($menu)
        <div class="card mt-3">
            <div class="card-header-violet"><i class="fas fa-utensils me-2"></i> Food Menu — Set {{ $reservation->food_set }}</div>
            <div class="card-body p-4">
                <ul class="mb-0" style="color:#555;">
                    @foreach($menu['items'] as $item)<li>{{ $item }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection