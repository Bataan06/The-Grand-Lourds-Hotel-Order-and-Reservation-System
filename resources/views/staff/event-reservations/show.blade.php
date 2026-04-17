@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .card-header-violet { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; font-weight: 600; }
    .info-label { font-weight: 700; color: #6a0dad; }
    .amenity-item { background: #f3e5f5; border-radius: 8px; padding: 6px 12px; margin-bottom: 6px; color: #4a0080; font-size: 0.85rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-calendar-check me-2"></i> Reservation Details</h2>
    <div class="d-flex gap-2">
        {{-- Print Receipt Button --}}
        <a href="{{ route('staff.event-reservations.receipt', $reservation->id) }}"
           target="_blank"
           class="btn text-white"
           style="background:linear-gradient(135deg,#059669,#10b981);border-radius:8px;font-weight:600;">
            <i class="fas fa-print me-2"></i> Print Receipt
        </a>
        <a href="{{ route('staff.event-reservations.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-user me-2"></i> Guest Information</div>
            <div class="card-body p-4">
                <p><span class="info-label">Name:</span> {{ $reservation->user->name }}</p>
                <p><span class="info-label">Email:</span> {{ $reservation->user->email }}</p>
                <p><span class="info-label">Event:</span> {{ $reservation->event->name }}</p>
                <p><span class="info-label">Venue:</span> {{ $reservation->venue->name }}</p>
                <p><span class="info-label">Event Date:</span> {{ $reservation->event_date->format('F d, Y') }}</p>
                <p><span class="info-label">Event Time:</span>
                    {{ $reservation->event_time_start ? \Carbon\Carbon::parse($reservation->event_time_start)->format('g:i A') : 'TBD' }}
                </p>
                <p><span class="info-label">No. of Guests:</span> {{ $reservation->pax_count }}</p>
                <p><span class="info-label">Food Set:</span> Set {{ $reservation->food_set }}</p>
                <p><span class="info-label">Price per Pax:</span>
                    &#8369;{{ number_format($reservation->price_per_pax, 2) }}
                </p>
                <p><span class="info-label">Total Amount:</span>
                    <strong style="color:#4a0080;font-size:1.1rem;">&#8369;{{ number_format($reservation->total_amount, 2) }}</strong>
                </p>
                @if($reservation->celebrant_name)
                <p><span class="info-label">Celebrant/Couple:</span> {{ $reservation->celebrant_name }}</p>
                @endif
                <p><span class="info-label">Special Requests:</span> {{ $reservation->special_requests ?? 'None' }}</p>
                <p><span class="info-label">Status:</span>
                    <span class="ms-2 badge" style="background:
                        {{ $reservation->status === 'confirmed' ? '#4a0080' :
                          ($reservation->status === 'cancelled' ? '#d500f9' :
                          ($reservation->status === 'completed' ? '#9c27b0' : '#ede7f6')) }};
                        color: {{ $reservation->status === 'pending' ? '#6a0dad' : 'white' }};
                        padding:6px 14px; border-radius:20px;">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card mb-3">
            <div class="card-header-violet"><i class="fas fa-tasks me-2"></i> Actions</div>
            <div class="card-body p-4">
                @if($reservation->status === 'pending')
                <form action="{{ route('staff.event-reservations.confirm', $reservation->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn text-white w-100 mb-2" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;">
                        <i class="fas fa-check me-2"></i> Confirm Reservation
                    </button>
                </form>
                @endif
                @if($reservation->status === 'confirmed')
                <form action="{{ route('staff.event-reservations.complete', $reservation->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn text-white w-100 mb-2" style="background:#9c27b0;border-radius:8px;font-weight:700;">
                        <i class="fas fa-flag-checkered me-2"></i> Mark as Completed
                    </button>
                </form>
                @endif
                @if($reservation->status === 'completed')
                    <p class="text-muted text-center mb-2"><i class="fas fa-check-circle text-success me-2"></i>This reservation is completed.</p>
                @endif

                {{-- Print Receipt always visible --}}
                <a href="{{ route('staff.event-reservations.receipt', $reservation->id) }}"
                   target="_blank"
                   class="btn text-white w-100"
                   style="background:linear-gradient(135deg,#059669,#10b981);border-radius:8px;font-weight:700;">
                    <i class="fas fa-print me-2"></i> Print Receipt
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header-violet"><i class="fas fa-check-circle me-2"></i> Package Inclusions</div>
            <div class="card-body p-4">
                @foreach($reservation->package->amenities as $amenity)
                    <div class="amenity-item"><i class="fas fa-check me-2" style="color:#7b2ff7;"></i>{{ $amenity }}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection