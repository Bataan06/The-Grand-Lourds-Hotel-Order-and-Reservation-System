@extends('layouts.app')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #1a0035, #2d0057, #4a0080);
        border-radius: 20px; padding: 30px 40px; color: white;
        margin-bottom: 28px; position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 280px; height: 280px; border-radius: 50%;
        background: rgba(123,47,247,0.15); pointer-events: none;
    }
    .stat-card {
        border: none; border-radius: 15px; padding: 24px 24px 20px;
        color: white; box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        cursor: pointer; transition: all 0.2s; border: 3px solid transparent;
    }
    .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; margin-bottom: 6px; }
    .stat-card .stat-num   { font-size: 2.4rem; font-weight: 800; line-height: 1; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.25); }
    .stat-card.active-filter { border: 3px solid white; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.25); }
    .card-total     { background: linear-gradient(135deg, #2d0057, #7b2ff7); }
    .card-pending   { background: linear-gradient(135deg, #9333ea, #c084fc); }
    .card-confirmed { background: linear-gradient(135deg, #3b0764, #7c3aed); }
    .card-completed { background: linear-gradient(135deg, #047857, #10b981); }
    .card-pencil    { background: linear-gradient(135deg, #92400e, #f59e0b); }
    .card-cancelled { background: linear-gradient(135deg, #b91c1c, #ef4444); }

    .section-title {
        font-weight: 800; color: #2d0057;
        border-left: 4px solid #7b2ff7;
        padding-left: 14px; font-size: 1.05rem;
    }
    .reservations-card {
        border: none; border-radius: 18px;
        box-shadow: 0 5px 25px rgba(74,0,128,0.08);
        overflow: hidden; background: #fff;
    }
    .res-table { width: 100%; border-collapse: collapse; min-width: 1100px; }
    .res-table thead tr { background: #6c47c7; }
    .res-table thead th {
        padding: 13px 14px; text-align: left; font-size: 0.78rem;
        font-weight: 600; color: #fff; white-space: nowrap; border: none;
    }
    .res-table tbody tr { border-bottom: 1px solid #f0eaff; transition: background 0.12s; }
    .res-table tbody tr:last-child { border-bottom: none; }
    .res-table tbody tr:hover { background: #faf5ff; }
    .res-table tbody td { padding: 14px 14px; vertical-align: top; border: none; }

    .cell-ref        { font-weight: 700; font-size: 0.78rem; color: #5b21b6; white-space: nowrap; }
    .cell-guest-name { font-weight: 700; font-size: 0.85rem; color: #111827; line-height: 1.4; }
    .cell-guest-sub  { font-size: 0.73rem; color: #6b7280; line-height: 1.5; }
    .cell-event-name { font-weight: 600; font-size: 0.82rem; color: #111827; line-height: 1.4; }
    .cell-event-sub  { font-size: 0.73rem; color: #6b7280; }
    .cell-venue      { font-size: 0.82rem; color: #111827; font-weight: 500; }
    .cell-date-main  { font-weight: 600; font-size: 0.82rem; color: #111827; line-height: 1.4; white-space: nowrap; }
    .cell-date-time  { font-size: 0.73rem; color: #7c3aed; white-space: nowrap; }
    .cell-pax        { font-size: 0.82rem; color: #111827; font-weight: 500; }
    .badge-foodset   { background: #ede7f6; color: #4a0080; padding: 3px 9px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; display:inline-block; }
    .cell-total      { font-weight: 800; font-size: 0.88rem; color: #111827; }
    .cell-per-pax    { font-size: 0.72rem; color: #9ca3af; margin-top: 2px; }

    .badge-pending   { background:#f3e5f5; color:#7b1fa2; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-confirmed { background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-cancelled { background:#fce4ec; color:#c62828; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-completed { background:#e5e7eb; color:#374151; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-pencil    { background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; display:inline-block; white-space:nowrap; }
    .badge-unpaid    { background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-partial   { background:#fef3c7; color:#d97706; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-paid      { background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }

    .btn-action-view {
        background: #4a0080; color: #fff; border: none; border-radius: 8px;
        padding: 5px 12px; font-size: 0.75rem; font-weight: 600;
        text-decoration: none; display:inline-flex; align-items:center; gap:4px;
        white-space: nowrap; transition: opacity 0.12s;
    }
    .btn-action-view:hover { opacity: 0.85; color: #fff; }
    .btn-action-confirm {
        background: #7b2ff7; color: #fff; border: none; border-radius: 8px;
        padding: 5px 12px; font-size: 0.75rem; font-weight: 600;
        cursor: pointer; display:inline-flex; align-items:center; gap:4px;
    }
    .btn-action-confirm:hover { opacity: 0.85; }
</style>

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="position-relative" style="z-index:1;">
        <h3 class="fw-bold mb-1" style="font-size:1.5rem;">Welcome, {{ Auth::user()->name }}!</h3>
        <p class="mb-0" style="opacity:0.75;font-size:0.9rem;">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, F d, Y') }}
            &nbsp;·&nbsp;
            <i class="fas fa-user-tie me-1"></i> Staff Dashboard
        </p>
    </div>
</div>

{{-- Flash Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert"
     style="border-radius:12px;border:none;background:#d1fae5;color:#065f46;font-weight:600;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stat Cards --}}
@php
    $total       = \App\Models\GuestBooking::count();
    $pending     = \App\Models\GuestBooking::where('status','pending')->where('is_pencil', false)->count();
    $confirmed   = \App\Models\GuestBooking::where('status','confirmed')->count();
    $completed   = \App\Models\GuestBooking::where('status','completed')->count();
    $pencilCount = \App\Models\GuestBooking::where('status', 'pencil')->count();
    $cancelled   = \App\Models\GuestBooking::where('status', 'cancelled')->count();
@endphp
<div class="row mb-4">
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-total" onclick="filterTable('all')" id="card-all">
            <div class="stat-label">Total</div>
            <div class="stat-num">{{ $total }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-pending" onclick="filterTable('pending')" id="card-pending">
            <div class="stat-label">Pending</div>
            <div class="stat-num">{{ $pending }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-confirmed" onclick="filterTable('confirmed')" id="card-confirmed">
            <div class="stat-label">Confirmed</div>
            <div class="stat-num">{{ $confirmed }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-completed" id="card-completed">
            <div class="stat-label">Completed</div>
            <div class="stat-num">{{ $completed }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-pencil" onclick="filterTable('pencil')" id="card-pencil">
            <div class="stat-label"> Pencil</div>
            <div class="stat-num">{{ $pencilCount }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-cancelled" id="card-cancelled">
            <div class="stat-label">Cancelled</div>
            <div class="stat-num">{{ $cancelled }}</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="section-title mb-0" id="sectionLabel">
        <i class="fas fa-calendar-check me-2"></i> Recent Reservations
    </p>
</div>

<div class="reservations-card">
    <div style="overflow-x:auto;">
        <table class="res-table">
            <thead>
                <tr>
                    <th>Ref #</th><th>Guest</th><th>Event</th><th>Venue</th>
                    <th>Date</th><th>Pax</th><th>Food Set</th><th>Total</th>
                    <th>Status</th><th>Payment</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse(\App\Models\GuestBooking::with(['event','venue'])
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->latest()
                ->take(20)
                ->get() as $r)
                <tr data-status="{{ $r->is_pencil ? 'pencil' : $r->display_status }}" class="res-row">

                    {{-- REF # --}}
                    <td>
                        <span class="cell-ref">{{ $r->reference_no }}</span>
                        @if($r->is_pencil)
                            <div><span class="badge-pencil"> Pencil</span></div>
                        @endif
                    </td>

                    {{-- GUEST --}}
                    <td>
                        <div class="cell-guest-name">{{ $r->guest_name }}</div>
                        @if($r->guest_phone)<div class="cell-guest-sub">{{ $r->guest_phone }}</div>@endif
                        <div class="cell-guest-sub">{{ $r->guest_email }}</div>
                    </td>

                    {{-- EVENT --}}
                    <td>
                        <div class="cell-event-name">{{ $r->event->name ?? '—' }}</div>
                        @if($r->celebrant_name)<div class="cell-event-sub">{{ $r->celebrant_name }}</div>@endif
                    </td>

                    {{-- VENUE --}}
                    <td><div class="cell-venue">{{ $r->venue->name ?? '—' }}</div></td>

                    {{-- DATE --}}
                    <td>
                        <div class="cell-date-main">{{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}</div>
                        @if($r->event_time_start)
                            <div class="cell-date-time">
                                {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                                <span style="color:#9ca3af;">→</span>
                                {{ \Carbon\Carbon::parse($r->event_time_start)->addHours(4)->format('g:i A') }}
                            </div>
                        @endif
                    </td>

                    {{-- PAX --}}
                    <td><div class="cell-pax">{{ number_format($r->pax_count) }}</div></td>

                    {{-- FOOD SET --}}
                    <td>
                        @if($r->food_set)
                            <span class="badge-foodset">Set {{ $r->food_set }}</span>
                        @else <span style="color:#d1d5db;">—</span> @endif
                    </td>

                    {{-- TOTAL --}}
                    <td>
                        <div class="cell-total">&#8369;{{ number_format($r->total_amount, 2) }}</div>
                        @if($r->price_per_pax)
                            <div class="cell-per-pax">&#8369;{{ number_format($r->price_per_pax, 2) }}/pax</div>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($r->status === 'pencil' || $r->is_pencil)
                            <span class="badge-pencil"> Pencil Reservation</span>
                        @elseif($r->status === 'pending')
                            <span class="badge-pending">Pending</span>
                        @elseif($r->display_status === 'ongoing')
                            <span class="badge-confirmed" style="background:#dbeafe;color:#1d4ed8;">Ongoing</span>
                        @elseif($r->status === 'confirmed')
                            <span class="badge-confirmed">Confirmed</span>
                        @elseif($r->status === 'cancelled')
                            <span class="badge-cancelled">Cancelled</span>
                        @else
                            <span class="badge-completed">Completed</span>
                        @endif
                    </td>

                    {{-- PAYMENT --}}
                    <td>
                        @php $ps = $r->payment_status ?? 'unpaid'; @endphp
                        @if($ps === 'paid') <span class="badge-paid">Paid</span>
                        @elseif($ps === 'partial') <span class="badge-partial">Partial</span>
                        @else <span class="badge-unpaid">Unpaid</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>
                        <div class="d-flex flex-column align-items-start gap-1">
                            <a href="{{ route('staff.guest-bookings.show', ['guestBooking' => $r->id, 'return_to' => 'dashboard']) }}" class="btn-action-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($r->status === 'pending')
                            <form action="{{ route('staff.guest-bookings.confirm', $r->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action-confirm">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5" style="color:#bbb;">
                        <i class="fas fa-calendar fa-2x d-block mb-2" style="color:#ce93d8;"></i>
                        No reservations yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterTable(status) {
    document.querySelectorAll('.res-row').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
    const labels = {
        all:'Recent Reservations', pending:'Pending Reservations',
        confirmed:'Confirmed Reservations', completed:'Completed Reservations',
        pencil:' Pencil Reservations', cancelled:'Cancelled Reservations'
    };
    document.getElementById('sectionLabel').innerHTML =
        '<i class="fas fa-calendar-check me-2"></i> ' + (labels[status] || 'Recent Reservations');
    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active-filter'));
    const map = { all:'card-all', pending:'card-pending', confirmed:'card-confirmed', completed:'card-completed', pencil:'card-pencil', cancelled:'card-cancelled' };
    const el = document.getElementById(map[status]);
    if (el) el.classList.add('active-filter');
}
document.addEventListener('DOMContentLoaded', () => filterTable('all'));
</script>

@endsection
