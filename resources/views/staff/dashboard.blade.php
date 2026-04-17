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
    }
    .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; margin-bottom: 6px; }
    .stat-card .stat-num   { font-size: 2.4rem; font-weight: 800; line-height: 1; }
    .card-total     { background: linear-gradient(135deg, #2d0057, #7b2ff7); }
    .card-pending   { background: linear-gradient(135deg, #9333ea, #c084fc); }
    .card-confirmed { background: linear-gradient(135deg, #3b0764, #7c3aed); }
    .card-completed { background: linear-gradient(135deg, #047857, #10b981); }
    .card-pencil    { background: linear-gradient(135deg, #92400e, #f59e0b); }

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
        padding: 13px 14px; text-align: left;
        font-size: 0.78rem; font-weight: 600;
        color: #fff; white-space: nowrap; border: none;
    }
    .res-table tbody tr { border-bottom: 1px solid #f0eaff; transition: background 0.12s; }
    .res-table tbody tr:last-child { border-bottom: none; }
    .res-table tbody tr:hover { background: #faf5ff; }
    .res-table tbody td { padding: 14px 14px; vertical-align: top; border: none; }

    .cell-ref { font-weight: 700; font-size: 0.78rem; color: #5b21b6; white-space: nowrap; }
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

    .badge-unpaid  { background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-partial { background:#fef3c7; color:#d97706; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }
    .badge-paid    { background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; display:inline-block; white-space:nowrap; }

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

    /* Pencil Notification Banner */
    .pencil-alert {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 2px solid #f59e0b;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .pencil-alert-item {
        background: white;
        border-radius: 10px;
        padding: 10px 14px;
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-left: 4px solid #f59e0b;
    }
</style>

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="position-relative" style="z-index:1;">
        <h3 class="fw-bold mb-1" style="font-size:1.5rem;">
            Welcome, {{ Auth::user()->name }}! 👋
        </h3>
        <p class="mb-0" style="opacity:0.75;font-size:0.9rem;">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, F d, Y') }}
            &nbsp;·&nbsp;
            <i class="fas fa-user-tie me-1"></i> Staff Dashboard
        </p>
    </div>
</div>

{{-- Pencil Reservation Notification --}}
@php
    $pencilBookings = \App\Models\GuestBooking::with(['event','venue'])
        ->where('is_pencil', true)
        ->where('status', 'pending')
        ->latest()
        ->get();
@endphp

@if($pencilBookings->count() > 0)
<div class="pencil-alert">
    <div style="font-weight:800;color:#92400e;font-size:0.95rem;">
        ✏️ Pencil Reservations — Action Required ({{ $pencilBookings->count() }})
    </div>
    <div style="font-size:0.82rem;color:#78350f;margin-top:4px;">
        The following tentative bookings need to be validated. Please call the guest to confirm.
    </div>
    @foreach($pencilBookings as $p)
    <div class="pencil-alert-item">
        <div>
            <div style="font-weight:700;color:#111827;font-size:0.85rem;">
                {{ $p->guest_name }}
                <span style="font-size:0.75rem;color:#5b21b6;margin-left:8px;">{{ $p->reference_no }}</span>
            </div>
            <div style="font-size:0.78rem;color:#6b7280;">
                {{ $p->event->name ?? '—' }} · {{ $p->venue->name ?? '—' }} ·
                {{ \Carbon\Carbon::parse($p->event_date)->format('M d, Y') }} ·
                {{ $p->guest_phone }}
            </div>
        </div>
        <a href="{{ route('staff.guest-bookings.show', $p->id) }}"
           class="btn-action-view" style="background:#f59e0b;">
            <i class="fas fa-eye"></i> View
        </a>
    </div>
    @endforeach
</div>
@endif

{{-- Stat Cards --}}
@php
    $total     = \App\Models\GuestBooking::count();
    $pending   = \App\Models\GuestBooking::where('status','pending')->where('is_pencil', false)->count();
    $confirmed = \App\Models\GuestBooking::where('status','confirmed')->count();
    $completed = \App\Models\GuestBooking::where('status','completed')->count();
    $pencilCount = \App\Models\GuestBooking::where('is_pencil', true)->where('status','pending')->count();
@endphp

<div class="row mb-4">
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-total" onclick="filterTable('all')" style="cursor:pointer;" id="card-all">
            <div class="stat-label">Total</div>
            <div class="stat-num">{{ $total }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-pending" onclick="filterTable('pending')" style="cursor:pointer;" id="card-pending">
            <div class="stat-label">Pending</div>
            <div class="stat-num">{{ $pending }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-confirmed" onclick="filterTable('confirmed')" style="cursor:pointer;" id="card-confirmed">
            <div class="stat-label">Confirmed</div>
            <div class="stat-num">{{ $confirmed }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-completed" onclick="filterTable('completed')" style="cursor:pointer;" id="card-completed">
            <div class="stat-label">Completed</div>
            <div class="stat-num">{{ $completed }}</div>
        </div>
    </div>
    <div class="col-6 col-md mb-3">
        <div class="stat-card card-pencil" onclick="filterTable('pencil')" style="cursor:pointer;" id="card-pencil">
            <div class="stat-label">✏️ Pencil</div>
            <div class="stat-num">{{ $pencilCount }}</div>
        </div>
    </div>
</div>

{{-- Reservations Table --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="section-title mb-0" id="sectionLabel">
        <i class="fas fa-calendar-check me-2"></i> All Reservations
    </p>
</div>

<div class="reservations-card">
    <div style="overflow-x:auto;">
        <table class="res-table">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Guest</th>
                    <th>Event</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Food Set</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse(\App\Models\GuestBooking::with(['event', 'venue'])->latest()->take(20)->get() as $r)
                <tr data-status="{{ $r->is_pencil ? 'pencil' : $r->status }}" class="res-row">
                    <td>
                        <span class="cell-ref">{{ $r->reference_no }}</span>
                        @if($r->is_pencil)
                        <div><span class="badge-pencil">✏️ Pencil</span></div>
                        @endif
                        @if($r->has_conflict)
                        <div><span style="background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:20px;font-size:0.68rem;font-weight:700;">⚠️ Conflict</span></div>
                        @endif
                    </td>

                    <td>
                        <div class="cell-guest-name">{{ $r->guest_name }}</div>
                        @if($r->guest_phone)<div class="cell-guest-sub">{{ $r->guest_phone }}</div>@endif
                        <div class="cell-guest-sub">{{ $r->guest_email }}</div>
                    </td>

                    <td>
                        <div class="cell-event-name">{{ $r->event->name ?? '—' }}</div>
                        @if($r->celebrant_name)<div class="cell-event-sub">{{ $r->celebrant_name }}</div>@endif
                    </td>

                    <td><div class="cell-venue">{{ $r->venue->name ?? '—' }}</div></td>

                    <td>
                        <div class="cell-date-main">{{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}</div>
                        @if($r->event_time_start)
                        <div class="cell-date-time">{{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}</div>
                        @endif
                    </td>

                    <td><div class="cell-pax">{{ number_format($r->pax_count) }}</div></td>

                    <td>
                        @if($r->food_set)
                            <span class="badge-foodset">Set {{ $r->food_set }}</span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>

                    <td>
                        <div class="cell-total">&#8369;{{ number_format($r->total_amount, 2) }}</div>
                        @if($r->price_per_pax)
                        <div class="cell-per-pax">&#8369;{{ number_format($r->price_per_pax, 2) }}/pax</div>
                        @endif
                    </td>

                    <td>
                        @if($r->is_pencil && $r->status === 'pending')
                            <span class="badge-pencil">✏️ Pencil</span>
                        @elseif($r->status === 'pending')
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
                        @php $ps = $r->payment_status ?? 'unpaid'; @endphp
                        @if($ps === 'paid')
                            <span class="badge-paid">Paid</span>
                        @elseif($ps === 'partial')
                            <span class="badge-partial">Partial</span>
                        @else
                            <span class="badge-unpaid">Unpaid</span>
                        @endif
                    </td>

                    <td>
                        <div class="d-flex flex-column align-items-start gap-1">
                            <a href="{{ route('staff.guest-bookings.show', $r->id) }}" class="btn-action-view">
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
    const rows = document.querySelectorAll('.res-row');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    const labels = {
        all: 'All Reservations',
        pending: 'Pending Reservations',
        confirmed: 'Confirmed Reservations',
        completed: 'Completed Reservations',
        pencil: '✏️ Pencil Reservations'
    };
    document.getElementById('sectionLabel').innerHTML = '<i class="fas fa-calendar-check me-2"></i> ' + (labels[status] || 'All Reservations');

    document.querySelectorAll('.stat-card').forEach(c => c.style.opacity = '0.6');
    const map = { all: 'card-all', pending: 'card-pending', confirmed: 'card-confirmed', completed: 'card-completed', pencil: 'card-pencil' };
    const active = document.getElementById(map[status]);
    if (active) active.style.opacity = '1';
}
document.addEventListener('DOMContentLoaded', () => filterTable('all'));
</script>

@endsection