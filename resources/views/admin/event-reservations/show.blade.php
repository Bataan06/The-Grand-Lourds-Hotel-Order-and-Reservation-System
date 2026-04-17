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
    <a href="{{ route('admin.event-reservations.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
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
                <p><span class="info-label">Celebrant/Couple:</span> {{ $reservation->celebrant_name ?? '—' }}</p>
                <p><span class="info-label">Event Date:</span> {{ $reservation->event_date->format('F d, Y') }}</p>
                <p><span class="info-label">Event Time:</span>
                    {{ $reservation->event_time_start
                        ? \Carbon\Carbon::parse($reservation->event_time_start)->format('g:i A')
                        : 'TBD' }}
                </p>
                <p><span class="info-label">No. of Guests:</span> {{ number_format($reservation->pax_count) }}</p>
                <p><span class="info-label">Food Set:</span> Set {{ $reservation->food_set }}</p>
                <p><span class="info-label">Price per Pax:</span> &#8369;{{ number_format($reservation->price_per_pax, 2) }}</p>
                <p><span class="info-label">Total Amount:</span>
                    <strong style="color:#4a0080;font-size:1.1rem;">&#8369;{{ number_format($reservation->total_amount, 2) }}</strong>
                </p>
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
                <p><span class="info-label">Booked on:</span> {{ $reservation->created_at->format('F d, Y g:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        {{-- Update Status --}}
        <div class="card mb-3">
            <div class="card-header-violet"><i class="fas fa-edit me-2"></i> Update Status</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.event-reservations.update', $reservation->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color:#6a0dad;">Change Status</label>
                        <select name="status" class="form-select" style="border:1.5px solid #e9d5ff;border-radius:8px;">
                            <option value="pending"   {{ $reservation->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn text-white w-100"
                            style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;">
                        <i class="fas fa-save me-2"></i> Update Status
                    </button>
                </form>

                <hr style="border-color:#f3e8ff;margin:16px 0;">

                {{-- Delete --}}
                <form action="{{ route('admin.event-reservations.destroy', $reservation->id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this reservation permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn w-100"
                            style="background:#fee2e2;color:#dc2626;border-radius:8px;font-weight:700;border:none;">
                        <i class="fas fa-trash me-2"></i> Delete Reservation
                    </button>
                </form>
            </div>
        </div>

        {{-- Package Inclusions --}}
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-check-circle me-2"></i> Package Inclusions</div>
            <div class="card-body p-4">
                @if($reservation->package && $reservation->package->amenities)
                    @foreach($reservation->package->amenities as $amenity)
                        <div class="amenity-item"><i class="fas fa-check me-2" style="color:#7b2ff7;"></i>{{ $amenity }}</div>
                    @endforeach
                @else
                    <p class="text-muted">No package inclusions found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
