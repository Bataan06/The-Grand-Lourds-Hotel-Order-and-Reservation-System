@extends('layouts.app')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #2d0057, #4a0080, #7b2ff7);
        border-radius: 20px; padding: 30px 40px; color: white;
        margin-bottom: 28px; position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 280px; height: 280px; border-radius: 50%;
        background: rgba(255,255,255,0.06); pointer-events: none;
    }

    .stat-card {
        border: none; border-radius: 15px; padding: 24px 24px 20px;
        color: white; position: relative; overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .stat-card:hover        { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.22); }
    .stat-card.active-card  { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(0,0,0,0.28); outline: 3px solid rgba(255,255,255,0.55); outline-offset: 2px; }
    .stat-card.dimmed       { opacity: 1; }
    .stat-card .stat-label  { font-size: 0.85rem; opacity: 0.85; margin-bottom: 6px; }
    .stat-card .stat-num    { font-size: 2.4rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-card .stat-sub    { font-size: 0.78rem; opacity: 0.75; }

    .card-v1 { background: linear-gradient(135deg, #2d0057, #7b2ff7); }
    .card-v2 { background: linear-gradient(135deg, #3b0764, #7c3aed); }
    .card-v3 { background: linear-gradient(135deg, #9333ea, #c084fc); }
    .card-v4 { background: linear-gradient(135deg, #991b1b, #ef4444); }
    .card-v5 { background: linear-gradient(135deg, #047857, #10b981); }
    .card-v6 { background: linear-gradient(135deg, #1d4ed8, #60a5fa); cursor: default; }

    .section-title { font-weight: 800; color: #2d0057; border-left: 4px solid #7b2ff7; padding-left: 14px; font-size: 1.05rem; }

    .table-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); overflow: hidden; background: white; }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 13px 15px; font-size: 0.83rem; font-weight: 600; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; font-size: 0.85rem; border-color: #f3e8ff; }
    .table tbody tr:hover { background: #faf5ff; }

    .badge-pending   { background: #f3e5f5; color: #7b1fa2; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-confirmed { background: #4a0080; color: white;   padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-cancelled { background: #fce4ec; color: #c62828; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }

    .guest-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg,#7c3aed,#9333ea); display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: white; flex-shrink: 0; }
    .event-date { font-weight: 600; font-size: 0.85rem; color: #1f2937; }
    .event-time { font-size: 0.73rem; color: #7c3aed; margin-top: 2px; display:flex; align-items:center; gap:4px; }
    .hidden-row { display: none !important; }
</style>

@php
    $total        = \App\Models\GuestBooking::count();
    $pending      = \App\Models\GuestBooking::where('status','pending')->count();
    $confirmed    = \App\Models\GuestBooking::where('status','confirmed')->count();
    $completed    = \App\Models\GuestBooking::where('status','completed')->count();
    $cancelled    = \App\Models\GuestBooking::where('status','cancelled')->count();
    $totalGuests  = \App\Models\User::where('role','user')->count();
    $totalRevenue = \App\Models\GuestBooking::whereIn('status',['confirmed','completed'])->sum('total_amount');
@endphp

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="row align-items-center position-relative" style="z-index:1;">
        <div class="col-md-8">
            <h3 class="mb-1 fw-bold" style="font-size:1.5rem;">
                Welcome back, {{ Auth::user()->name }}! 👋
            </h3>
            <p class="mb-0" style="opacity:0.75;font-size:0.9rem;">
                Here's what's happening at The Grand Lourds Hotel today.
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <p class="mb-0" style="opacity:0.7;font-size:0.88rem;">
                <i class="fas fa-calendar me-1"></i> {{ now()->format('l, F d, Y') }}
            </p>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v1 active-card" id="card-all" onclick="filterTable('all')">
            <div class="stat-label">Total</div>
            <div class="stat-num">{{ $total }}</div>
            <div class="stat-sub">All reservations</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v2 dimmed" id="card-confirmed" onclick="filterTable('confirmed')">
            <div class="stat-label">Confirmed</div>
            <div class="stat-num">{{ $confirmed }}</div>
            <div class="stat-sub">Active</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v5 dimmed" id="card-completed" onclick="filterTable('completed')">
            <div class="stat-label">Completed</div>
            <div class="stat-num">{{ $completed }}</div>
            <div class="stat-sub">Done events</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v4 dimmed" id="card-cancelled" onclick="filterTable('cancelled')">
            <div class="stat-label">Cancelled</div>
            <div class="stat-num">{{ $cancelled }}</div>
            <div class="stat-sub">Cancelled</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v3 dimmed" id="card-pending" onclick="filterTable('pending')">
            <div class="stat-label">Pending</div>
            <div class="stat-num">{{ $pending }}</div>
            <div class="stat-sub">For review</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card card-v6">
            <div class="stat-label">Revenue</div>
            <div class="stat-num" style="font-size:1.6rem;">&#8369;{{ number_format($totalRevenue/1000, 1) }}K</div>
            <div class="stat-sub">Confirmed + done</div>
        </div>
    </div>
</div>

{{-- Recent Reservations --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="section-title mb-0" id="section-label">
                <i class="fas fa-calendar-check me-2"></i> Recent Reservations
            </p>
            <a href="{{ route('admin.event-reservations.index') }}" class="btn text-white btn-sm"
               style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;">
                <i class="fas fa-list me-1"></i> View All
            </a>
        </div>

        <div class="table-card">
            <div style="overflow-x:auto;">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Event</th>
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Pax</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reservations-body">
                        @forelse(\App\Models\GuestBooking::with(['event','venue'])->latest()->take(20)->get() as $r)
                        <tr class="res-row" data-status="{{ $r->status }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">{{ strtoupper(substr($r->guest_name, 0, 1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.83rem;">{{ $r->guest_name }}</div>
                                        <div style="font-size:0.72rem;color:#9b59b6;">{{ $r->guest_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight:500;color:#4a0080;font-size:0.85rem;">{{ $r->event->name ?? '—' }}</td>
                            <td style="font-size:0.85rem;">{{ $r->venue->name ?? '—' }}</td>
                            <td>
                                <div class="event-date">{{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}</div>
                                @if($r->event_time_start)
                                    <div class="event-time">
                                        <i class="fas fa-clock" style="font-size:10px;"></i>
                                        {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:0.85rem;">{{ $r->pax_count }} <span style="color:#9b59b6;font-size:0.73rem;">pax</span></td>
                            <td style="font-weight:700;color:#4a0080;font-size:0.85rem;">
                                @if($r->total_amount) &#8369;{{ number_format($r->total_amount, 2) }}
                                @else <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($r->status === 'pending')     <span class="badge-pending">Pending</span>
                                @elseif($r->status === 'confirmed') <span class="badge-confirmed">Confirmed</span>
                                @elseif($r->status === 'cancelled') <span class="badge-cancelled">Cancelled</span>
                                @else                               <span class="badge-completed">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                                No reservations yet.
                            </td>
                        </tr>
                        @endforelse
                        <tr id="no-results-row" style="display:none;">
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-filter fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                                No reservations found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable(status) {
    // Update cards
    const cardIds = ['all','confirmed','completed','cancelled','pending'];
    cardIds.forEach(function(id) {
        var card = document.getElementById('card-' + id);
        if (!card) return;
        if (id === status) {
            card.classList.add('active-card');
            card.classList.remove('dimmed');
        } else {
            card.classList.remove('active-card');
            card.classList.add('dimmed');
        }
    });

    // Filter rows
    var rows = document.querySelectorAll('.res-row');
    var visible = 0;
    rows.forEach(function(row) {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    // No results row
    var noResults = document.getElementById('no-results-row');
    if (noResults) noResults.style.display = visible === 0 ? '' : 'none';

    // Update label
    var label = document.getElementById('section-label');
    var names = { all:'Recent Reservations', confirmed:'Confirmed Reservations', completed:'Completed Reservations', cancelled:'Cancelled Reservations', pending:'Pending Reservations' };
    if (label) label.innerHTML = '<i class="fas fa-calendar-check me-2"></i> ' + (names[status] || 'Recent Reservations');
}
</script>

@endsection