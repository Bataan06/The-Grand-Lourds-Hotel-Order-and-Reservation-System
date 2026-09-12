@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #2d0057; font-weight: 800; }

    /* ── Stat Cards ── */
    .stat-card {
        border: none; border-radius: 15px; padding: 22px; color: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        cursor: pointer; transition: all 0.2s;
        border: 3px solid transparent;
        text-decoration: none; display: block;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.18); color: white; }
    .stat-card.active { border: 3px solid rgba(255,255,255,0.9); transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.22); }
    .stat-card p { opacity: .8; font-size: .8rem; margin-bottom: 4px; }
    .stat-card h3 { font-size: 2rem; font-weight: 800; margin: 0; }

    .card-total     { background: linear-gradient(135deg, #2d0057, #7b2ff7); }
    .card-pending   { background: linear-gradient(135deg, #6a0dad, #9b59b6); }
    .card-confirmed { background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .card-completed { background: linear-gradient(135deg, #1a7a4a, #2ecc71); }
    .card-cancelled { background: linear-gradient(135deg, #7f1d1d, #dc2626); }
    .card-pencil    { background: linear-gradient(135deg, #92400e, #f59e0b); }
    .card-pax       { background: linear-gradient(135deg, #1e3a5f, #3b82f6); }

    /* ── Table ── */
    .table-card { border: none; border-radius: 18px; box-shadow: 0 5px 25px rgba(74,0,128,0.08); overflow: hidden; }
    .table thead th { background: linear-gradient(135deg, #2d0057, #7b2ff7); color: white; border: none; padding: 13px 15px; font-size: 0.85rem; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; font-size: 0.85rem; border-color: #f8f0ff; }
    .table tbody tr:hover { background: #faf5ff; }

    .badge-pending   { background: #f3e5f5; color: #7b1fa2; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-confirmed { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-cancelled { background: #fce4ec; color: #c62828; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-pencil    { background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }

    .section-label { font-weight: 800; color: #4a0080; border-left: 4px solid #7b2ff7; padding-left: 12px; margin-bottom: 16px; font-size: 1rem; }

    /* ── Toggle Buttons ── */
    .type-btn { border-radius: 8px; padding: 8px 20px; font-weight: 700; font-size: 0.88rem; border: 2px solid #7b2ff7; cursor: pointer; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .type-btn.active { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border-color: transparent; }
    .type-btn.inactive { background: white; color: #7b2ff7; }
    .type-btn:hover { opacity: 0.85; }

    /* ── Revenue Box ── */
    .revenue-box { background: linear-gradient(135deg,#1e3a5f,#3b82f6); border-radius: 14px; padding: 20px 24px; color: white; }
    .revenue-box .rev-label { font-size: 0.78rem; opacity: 0.8; margin-bottom: 4px; }
    .revenue-box .rev-val { font-size: 1.5rem; font-weight: 800; }
    .revenue-box .rev-sub { font-size: 0.72rem; opacity: 0.65; margin-top: 2px; }

    /* ── Filter Badge ── */
    .filter-active-badge { display: inline-flex; align-items: center; gap: 6px; background: #ede9fe; color: #4a0080; border-radius: 20px; padding: 5px 14px; font-size: 0.82rem; font-weight: 700; margin-bottom: 12px; }

    /* ── Status Breakdown Clickable ── */
    .breakdown-row {
        border-radius: 12px; padding: 14px 16px; margin-bottom: 10px;
        border: 2px solid transparent; cursor: pointer;
        transition: all 0.18s; text-decoration: none; display: block;
        background: #faf7ff;
    }
    .breakdown-row:hover { border-color: #a78bfa; background: #f5f0ff; transform: translateX(4px); }
    .breakdown-row.active-breakdown { border-color: #7b2ff7; background: #ede9fe; transform: translateX(4px); }
    .breakdown-row .bd-label { font-size: 0.78rem; font-weight: 700; color: #374151; }
    .breakdown-row .bd-count { font-size: 0.78rem; color: #6b7280; }
    .breakdown-bar-wrap { height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; margin-top: 8px; }
    .breakdown-bar { height: 100%; border-radius: 4px; transition: width 0.5s; }
</style>

{{-- ══════════════════════════════════════════════ --}}
{{-- HEADER + TOGGLE                               --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="page-title mb-0">
        <i class="fas fa-chart-bar me-2"></i>
        {{ $type === 'daily' ? 'Daily Report' : 'Monthly Report' }}
    </h2>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <a href="?type=daily&date={{ $date }}&filter=all"
           class="type-btn {{ $type === 'daily' ? 'active' : 'inactive' }}">
            <i class="fas fa-calendar-day"></i> Daily
        </a>
        <a href="?type=monthly&month={{ $month }}&filter=all"
           class="type-btn {{ $type === 'monthly' ? 'active' : 'inactive' }}">
            <i class="fas fa-calendar-alt"></i> Monthly
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- DATE / MONTH FILTER FORM                       --}}
{{-- ══════════════════════════════════════════════ --}}
<form method="GET" class="d-flex gap-2 align-items-center mb-4 flex-wrap">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="filter" value="all">

    @if($type === 'daily')
        <input type="date" name="date" value="{{ $date }}"
               class="form-control" style="border-color:#7b2ff7;border-radius:8px;font-size:0.88rem;max-width:200px;"
               onchange="this.form.submit()">
    @else
        <input type="month" name="month" value="{{ $month }}"
               class="form-control" style="border-color:#7b2ff7;border-radius:8px;font-size:0.88rem;max-width:200px;"
               onchange="this.form.submit()">
    @endif

    <button type="submit"
            class="btn text-white"
            style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;font-size:0.88rem;padding:8px 18px;">
        <i class="fas fa-search me-1"></i> Filter
    </button>

    <span style="font-size:0.82rem;color:#7b2ff7;font-weight:600;">
        @if($type === 'daily')
            {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
        @else
            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
        @endif
    </span>
</form>

{{-- Active filter badge --}}
@if($filter !== 'all')
<div class="filter-active-badge">
    <i class="fas fa-filter"></i>
    Showing: <strong>{{ ucfirst($filter) }}</strong> only &nbsp;
    <a href="?type={{ $type }}&{{ $type === 'daily' ? 'date='.$date : 'month='.$month }}&filter=all"
       style="color:#7b1fa2;text-decoration:none;font-size:0.78rem;">✕ Clear</a>
</div>
@endif

{{-- ══════════════════════════════════════════════ --}}
{{-- CLICKABLE STAT CARDS                           --}}
{{-- ══════════════════════════════════════════════ --}}
@php
    $baseUrl = '?type='.$type.'&'.($type === 'daily' ? 'date='.$date : 'month='.$month);
@endphp
<div class="row mb-4 g-3">
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=all"
           class="stat-card card-total {{ $filter === 'all' ? 'active' : '' }}">
            <p>Total</p>
            <h3>{{ $summary['total'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=pending"
           class="stat-card card-pending {{ $filter === 'pending' ? 'active' : '' }}">
            <p>Pending</p>
            <h3>{{ $summary['pending'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=confirmed"
           class="stat-card card-confirmed {{ $filter === 'confirmed' ? 'active' : '' }}">
            <p>Confirmed</p>
            <h3>{{ $summary['confirmed'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=completed"
           class="stat-card card-completed {{ $filter === 'completed' ? 'active' : '' }}">
            <p>Completed</p>
            <h3>{{ $summary['completed'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=cancelled"
           class="stat-card card-cancelled {{ $filter === 'cancelled' ? 'active' : '' }}">
            <p>Cancelled</p>
            <h3>{{ $summary['cancelled'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ $baseUrl }}&filter=pencil"
           class="stat-card card-pencil {{ $filter === 'pencil' ? 'active' : '' }}">
            <p>✏️ Pencil</p>
            <h3>{{ $summary['pencil'] }}</h3>
        </a>
    </div>
    <div class="col-6 col-md">
        <div class="stat-card card-pax" style="cursor:default;">
            <p>Total Pax</p>
            <h3>{{ $summary['total_pax'] }}</h3>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- REVENUE + STATS ROW                            --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="row mb-4 g-3">
    <div class="col-md-5">
        <div class="revenue-box h-100">
            <div class="rev-label"><i class="fas fa-peso-sign me-1"></i> Total Revenue</div>
            <div class="rev-val">₱{{ number_format($summary['total_revenue'], 2) }}</div>
            <div class="rev-sub">Confirmed + Completed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background:white;border-radius:14px;padding:20px 24px;box-shadow:0 3px 15px rgba(74,0,128,0.07);height:100%;">
            <div style="font-size:0.78rem;color:#9ca3af;margin-bottom:4px;">AVG. PAX</div>
            <div style="font-size:1.5rem;font-weight:800;color:#2d0057;">{{ $summary['avg_pax'] }}</div>
            <div style="font-size:0.72rem;color:#9ca3af;margin-top:2px;">Per reservation</div>
        </div>
    </div>
    <div class="col-md-4">
        <div style="background:white;border-radius:14px;padding:20px 24px;box-shadow:0 3px 15px rgba(74,0,128,0.07);height:100%;">
            <div style="font-size:0.78rem;color:#9ca3af;margin-bottom:4px;">COMPLETION</div>
            <div style="font-size:1.5rem;font-weight:800;color:#2d0057;">{{ $summary['completion_rate'] }}%</div>
            <div style="font-size:0.72rem;color:#9ca3af;margin-top:2px;">Rate</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- RESERVATION STATUS BREAKDOWN — NOW CLICKABLE  --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="row mb-4 g-3">
    <div class="col-md-7">
        <p class="section-label">Reservation Status Breakdown</p>
        <div style="background:white;border-radius:14px;padding:20px;box-shadow:0 3px 15px rgba(74,0,128,0.07);">
            @php $total = max($summary['total'], 1); @endphp

            @foreach([
                ['label' => 'PENDING',   'filter' => 'pending',   'count' => $summary['pending'],   'color' => '#9333ea'],
                ['label' => 'CONFIRMED', 'filter' => 'confirmed', 'count' => $summary['confirmed'], 'color' => '#4a0080'],
                ['label' => 'COMPLETED', 'filter' => 'completed', 'count' => $summary['completed'], 'color' => '#10b981'],
                ['label' => 'CANCELLED', 'filter' => 'cancelled', 'count' => $summary['cancelled'], 'color' => '#dc2626'],
                ['label' => 'PENCIL',    'filter' => 'pencil',    'count' => $summary['pencil'],    'color' => '#f59e0b'],
            ] as $row)
            @php $pct = $total > 1 ? round($row['count'] / $total * 100) : 0; @endphp
            <a href="{{ $baseUrl }}&filter={{ $row['filter'] }}"
               class="breakdown-row {{ $filter === $row['filter'] ? 'active-breakdown' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="bd-label">
                        @if($row['filter'] === 'pencil')  @endif
                        {{ $row['label'] }}
                        @if($filter === $row['filter'])
                            <span style="font-size:0.7rem;color:#7b2ff7;margin-left:6px;">● Active Filter</span>
                        @endif
                    </span>
                    <span class="bd-count">{{ $row['count'] }} ({{ $pct }}%)</span>
                </div>
                <div class="breakdown-bar-wrap">
                    <div class="breakdown-bar" style="width:{{ $pct }}%;background:{{ $row['color'] }};"></div>
                </div>
            </a>
            @endforeach

            {{-- Clear filter link --}}
            @if($filter !== 'all')
            <div class="text-center mt-3">
                <a href="{{ $baseUrl }}&filter=all"
                   style="font-size:0.78rem;color:#7b2ff7;font-weight:700;text-decoration:none;">
                    ✕ Clear Filter — Show All
                </a>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-5">
        <p class="section-label">By Event Type</p>
        <div style="background:white;border-radius:14px;padding:20px;box-shadow:0 3px 15px rgba(74,0,128,0.07);height:calc(100% - 34px);">
            @forelse($byEvent as $eventName => $count)
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:0.5px solid #f3f0ff;">
                <span style="font-size:0.85rem;font-weight:600;color:#2d0057;">{{ $eventName }}</span>
                <span style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:3px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">{{ $count }}</span>
            </div>
            @empty
            <p style="color:#9ca3af;font-size:0.85rem;text-align:center;padding-top:20px;">No data.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- RESERVATIONS TABLE                             --}}
{{-- ══════════════════════════════════════════════ --}}
<p class="section-label">
    Reservations
    @if($filter !== 'all')
        — <span style="font-weight:600;color:#7b2ff7;">{{ ucfirst($filter) }}</span>
    @endif
    for
    @if($type === 'daily')
        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
    @else
        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
    @endif
    <span style="font-size:0.82rem;font-weight:600;color:#9ca3af;margin-left:8px;">({{ $reservations->count() }} records)</span>
</p>

<div class="table-card card">
    <div class="card-body p-0">
        <div style="overflow-x:auto;">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref #</th>
                        <th>Guest</th>
                        <th>Event</th>
                        <th>Venue</th>
                        <th>Event Date</th>
                        <th>Pax</th>
                        <th>Set</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td style="font-weight:700;font-size:0.78rem;color:#5b21b6;">{{ $r->reference_no }}</td>
                        <td>
                            <div style="font-weight:700;font-size:0.85rem;color:#111827;">{{ $r->guest_name }}</div>
                            <div style="font-size:0.72rem;color:#9ca3af;">{{ $r->guest_phone }}</div>
                        </td>
                        <td>{{ optional($r->event)->name ?? '—' }}</td>
                        <td>{{ optional($r->venue)->name ?? '—' }}</td>
                        <td>
                            <div style="font-weight:600;font-size:0.82rem;">{{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}</div>
                            @if($r->event_time_start)
                                <div style="font-size:0.72rem;color:#7c3aed;">
                                    {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                                    → {{ \Carbon\Carbon::parse($r->event_time_start)->addHours(4)->format('g:i A') }}
                                </div>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ number_format($r->pax_count) }}</td>
                        <td>
                            @if($r->food_set)
                                <span style="background:#ede7f6;color:#4a0080;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Set {{ $r->food_set }}</span>
                            @else —
                            @endif
                        </td>
                        <td style="font-weight:800;font-size:0.88rem;">₱{{ number_format($r->total_amount, 2) }}</td>
                        <td>
                            @if($r->status === 'pencil' || $r->is_pencil)
                                <span class="badge-pencil"> Pencil</span>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5" style="color:#bbb;">
                            <i class="fas fa-calendar fa-2x d-block mb-2" style="color:#ce93d8;"></i>
                            No reservations found
                            @if($filter !== 'all') for <strong>{{ $filter }}</strong> status @endif
                            on this {{ $type === 'daily' ? 'date' : 'month' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection