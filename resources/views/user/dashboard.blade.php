@extends('layouts.app')

@section('content')
<style>
    .user-hero {
        background: linear-gradient(135deg, #1a0035, #2d0057, #4a0080);
        border-radius: 20px; padding: 50px 50px;
        margin-bottom: 35px; display: flex;
        align-items: center; gap: 40px;
        overflow: hidden; position: relative; min-height: 380px;
        max-width: 100%; box-sizing: border-box;
    }
    .user-hero::before {
        content: ''; position: absolute; top: -80px; right: -80px;
        width: 350px; height: 350px; border-radius: 50%;
        background: rgba(123,47,247,0.15); pointer-events: none;
    }
    .hero-left { flex: 1; position: relative; z-index: 1; }
    .hero-left .hotel-name { font-size: 0.75rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: #ce93d8; margin-bottom: 16px; }
    .hero-left h2 { font-family: 'Georgia', serif; font-size: 2.4rem; font-weight: 800; color: white; line-height: 1.2; margin-bottom: 16px; }
    .hero-left h2 em { font-style: italic; color: #e9d5ff; }
    .hero-left p { color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.7; max-width: 380px; margin-bottom: 24px; }
    .hero-checks { list-style: none; padding: 0; margin: 0 0 28px; }
    .hero-checks li { color: rgba(255,255,255,0.85); font-size: 0.88rem; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
    .hero-checks li i { color: #ce93d8; font-size: 1rem; }
    .btn-hero-book { background: linear-gradient(135deg, #7c3aed, #a21caf); color: white; border: none; border-radius: 10px; padding: 13px 28px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .btn-hero-book:hover { opacity: 0.9; color: white; transform: translateY(-2px); }
    .hero-date { margin-top: 20px; font-size: 0.82rem; color: rgba(255,255,255,0.5); }

    /* BIGGER PHOTO COLLAGE */
    .hero-photos { width: 320px; flex-shrink: 0; display: flex; flex-direction: column; gap: 10px; position: relative; z-index: 1; }
    .hero-photo { border-radius: 16px; overflow: hidden; border: 2px solid rgba(255,255,255,0.15); box-shadow: 0 8px 25px rgba(0,0,0,0.3); }
    .hero-photo img { width: 100%; height: 130px; object-fit: cover; display: block; transition: transform 0.4s; }
    .hero-photo:hover img { transform: scale(1.05); }

    /* RESERVATIONS */
    .section-title { font-weight: 800; color: #2d0057; border-left: 4px solid #7b2ff7; padding-left: 14px; margin-bottom: 20px; font-size: 1.05rem; }
    .reservations-card { border: none; border-radius: 18px; box-shadow: 0 5px 25px rgba(74,0,128,0.08); overflow: hidden; }
    .table thead th { background: linear-gradient(135deg, #2d0057, #7b2ff7); color: white; border: none; padding: 14px 16px; font-weight: 600; font-size: 0.85rem; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; font-size: 0.88rem; border-color: #f8f0ff; }
    .table tbody tr:hover { background: #faf5ff; }
    .badge-pending   { background: #f3e5f5; color: #7b1fa2; padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .badge-confirmed { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .badge-cancelled { background: #fce4ec; color: #c62828; padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .empty-state { padding: 25px 20px; text-align: center; }
    .empty-state i { font-size: 2rem; color: #ce93d8; margin-bottom: 8px; }
    .empty-state p { color: #9c8ab0; font-size: 0.85rem; margin-bottom: 10px; }
    .btn-new-res { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 10px; padding: 10px 22px; font-weight: 700; font-size: 0.88rem; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .btn-new-res:hover { opacity: 0.9; color: white; transform: translateY(-1px); }
    .btn-empty { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 8px; padding: 8px 20px; font-weight: 600; font-size: 0.83rem; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .btn-empty:hover { opacity: 0.9; color: white; }

    /* ===== RESPONSIVE ===== */
    * { box-sizing: border-box; }
    .user-hero { box-sizing: border-box; width: 100%; max-width: 100%; }
    @media (max-width: 768px) {
        .user-hero { flex-direction: column; padding: 24px 20px; min-height: auto; gap: 20px; border-radius: 14px; }
        .hero-photos { width: 100%; flex-direction: row; gap: 8px; }
        .hero-photo { flex: 1; min-width: 0; }
        .hero-photo img { height: 85px; }
        .hero-left h2 { font-size: 1.5rem; }
        .hero-left p { font-size: 0.83rem; max-width: 100%; }
        .hero-left .hotel-name { font-size: 0.68rem; letter-spacing: 2px; }
        .btn-new-res { font-size: 0.78rem; padding: 8px 14px; white-space: nowrap; }
        .reservations-card { overflow-x: auto; }
        .table { min-width: 750px; }
    }
    @media (max-width: 480px) {
        .user-hero { padding: 16px; gap: 12px; flex-direction: column; min-height: auto; }
        .hero-left { width: 100%; }
        .hero-left h2 { font-size: 1.1rem; }
        .hero-left p { font-size: 0.78rem; max-width: 100%; }
        .hero-checks { display: none; }
        .btn-hero-book { padding: 9px 16px; font-size: 0.8rem; }
        .hero-photos { width: 100%; flex-direction: row; gap: 6px; display: flex; }
        .hero-photo { flex: 1; min-width: 0; }
        .hero-photo img { height: 60px; width: 100%; object-fit: cover; }
        .hero-date { font-size: 0.72rem; margin-top: 10px; }
        .section-title { font-size: 0.9rem; }
    }
</style>

{{-- HERO --}}
<div class="user-hero">
    <div class="hero-left">
        <div class="hotel-name">✦ The Grand Lourds Hotel</div>
        <h2>Let Us Make Your<br><em>Grand Celebration</em><br>Come True</h2>
        <p>Welcome back, <strong style="color:#e9d5ff;">{{ Auth::user()->name }}</strong><br>
        Celebrate life's special moments in our elegant venue — perfect for weddings, birthdays, and meetings.</p>
        <ul class="hero-checks">
            <li><i class="fas fa-check-circle"></i> Flexible venue options</li>
            <li><i class="fas fa-check-circle"></i> Delicious menu selections</li>
            <li><i class="fas fa-check-circle"></i> Professional event setup</li>
            <li><i class="fas fa-check-circle"></i> Complimentary event amenities</li>
        </ul>
        <a href="{{ route('user.events.index') }}" class="btn-hero-book">
            <i class="fas fa-calendar-plus me-2"></i> Book an Event
        </a>
        <div class="hero-date"><i class="fas fa-calendar me-1"></i> {{ now()->format('l, F d, Y') }}</div>
    </div>
    <div class="hero-photos">
        <div class="hero-photo"><img src="{{ asset('images/wedding1.jpg') }}" alt="Event"></div>
        <div class="hero-photo"><img src="{{ asset('images/wedding2.jpg') }}" alt="Event"></div>
        <div class="hero-photo"><img src="{{ asset('images/wedding3.jpg') }}" alt="Event"></div>
    </div>
</div>

{{-- RESERVATIONS --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="section-title mb-0"><i class="fas fa-calendar-check me-2"></i> My Reservations</p>
    <a href="{{ route('user.events.index') }}" class="btn-new-res">
        <i class="fas fa-plus me-1"></i> New Reservation
    </a>
</div>

<div class="reservations-card card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Celebrant</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Food Set</th>
                    <th>Total Bill</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\EventReservation::with(['event','venue'])->where('user_id',Auth::id())->latest()->get() as $r)
                <tr>
                    <td><strong>{{ $r->event->name }}</strong></td>
                    <td>{{ $r->celebrant_name ?? '—' }}</td>
                    <td>{{ $r->venue->name }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $r->event_date->format('M d, Y') }}</div>
                        @if($r->event_time_start)
                            <div style="font-size:0.72rem;color:#7c3aed;">
                                <i class="fas fa-clock" style="font-size:9px;"></i>
                                {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $r->pax_count }} pax</td>
                    <td><span style="background:#ede7f6;color:#4a0080;padding:4px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;">Set {{ $r->food_set }}</span></td>
                    <td>
                        <div style="font-weight:700;color:#4a0080;">₱{{ number_format($r->total_amount, 2) }}</div>
                        <div style="font-size:0.72rem;color:#9ca3af;">₱{{ number_format($r->price_per_pax, 2) }}/pax</div>
                    </td>
                    <td>
                        @if($r->status === 'pending')
                            <span class="badge-pending">Pending</span>
                        @elseif($r->status === 'confirmed')
                            <span class="badge-confirmed">Confirmed</span>
                        @elseif($r->status === 'cancelled')
                            <span class="badge-cancelled">Cancelled</span>
                        @else
                            <span class="badge-completed">Completed</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('user.reservations.show', $r->id) }}" class="btn btn-sm text-white" style="background:#7b2ff7;border-radius:8px;">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-calendar-plus d-block"></i>
                            <p>No reservations yet.</p>
                            <a href="{{ route('user.events.index') }}" class="btn-empty">
                                <i class="fas fa-arrow-right me-1"></i> Book Your First Event
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection