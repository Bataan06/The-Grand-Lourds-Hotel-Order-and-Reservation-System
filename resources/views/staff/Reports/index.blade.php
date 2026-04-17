@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #2d0057; font-weight: 800; }
    .stat-card { border: none; border-radius: 15px; padding: 22px; color: white; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    .card-total     { background: linear-gradient(135deg, #2d0057, #7b2ff7); }
    .card-pending   { background: linear-gradient(135deg, #6a0dad, #9b59b6); }
    .card-confirmed { background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .card-completed { background: linear-gradient(135deg, #1a7a4a, #2ecc71); }
    .card-cancelled { background: linear-gradient(135deg, #7f1d1d, #dc2626); }
    .table-card { border: none; border-radius: 18px; box-shadow: 0 5px 25px rgba(74,0,128,0.08); overflow: hidden; }
    .table thead th { background: linear-gradient(135deg, #2d0057, #7b2ff7); color: white; border: none; padding: 13px 15px; font-size: 0.85rem; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; font-size: 0.85rem; border-color: #f8f0ff; }
    .table tbody tr:hover { background: #faf5ff; }
    .badge-pending   { background: #f3e5f5; color: #7b1fa2; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-confirmed { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-cancelled { background: #fce4ec; color: #c62828; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .section-label { font-weight: 800; color: #4a0080; border-left: 4px solid #7b2ff7; padding-left: 12px; margin-bottom: 16px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-chart-bar me-2"></i> Monthly Report</h2>
    <form method="GET" class="d-flex gap-2 align-items-center">
        <input type="month" name="month" value="{{ $month }}" class="form-control" style="border-color:#7b2ff7;border-radius:8px;font-size:0.88rem;">
        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;font-size:0.88rem;padding:8px 18px;">
            <i class="fas fa-search me-1"></i> Filter
        </button>
    </form>
</div>

{{-- Stats --}}
<div class="row mb-4">
    <div class="col mb-3"><div class="stat-card card-total"><p style="opacity:.8;font-size:.8rem;margin-bottom:4px;">Total</p><h3 style="font-size:2rem;font-weight:800;margin:0;">{{ $summary['total'] }}</h3></div></div>
    <div class="col mb-3"><div class="stat-card card-pending"><p style="opacity:.8;font-size:.8rem;margin-bottom:4px;">Pending</p><h3 style="font-size:2rem;font-weight:800;margin:0;">{{ $summary['pending'] }}</h3></div></div>
    <div class="col mb-3"><div class="stat-card card-confirmed"><p style="opacity:.8;font-size:.8rem;margin-bottom:4px;">Confirmed</p><h3 style="font-size:2rem;font-weight:800;margin:0;">{{ $summary['confirmed'] }}</h3></div></div>
    <div class="col mb-3"><div class="stat-card card-completed"><p style="opacity:.8;font-size:.8rem;margin-bottom:4px;">Completed</p><h3 style="font-size:2rem;font-weight:800;margin:0;">{{ $summary['completed'] }}</h3></div></div>
    <div class="col mb-3"><div class="stat-card card-cancelled"><p style="opacity:.8;font-size:.8rem;margin-bottom:4px;">Cancelled</p><h3 style="font-size:2rem;font-weight:800;margin:0;">{{ $summary['cancelled'] }}</h3></div></div>
</div>

{{-- By Event --}}
@if($byEvent->count() > 0)
<p class="section-label mb-3">By Event Type</p>
<div class="row mb-4">
    @foreach($byEvent as $eventName => $count)
    <div class="col-md-4 mb-3">
        <div style="background:#fff;border-radius:12px;padding:16px 20px;box-shadow:0 3px 15px rgba(74,0,128,0.07);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:600;color:#2d0057;font-size:0.9rem;">{{ $eventName }}</span>
            <span style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:4px 14px;border-radius:20px;font-weight:700;font-size:0.85rem;">{{ $count }}</span>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Reservations Table --}}
<p class="section-label mb-3">Reservations for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
<div class="table-card card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Guest</th>
                    <th>Event</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Set</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $r->user->name }}</strong></td>
                    <td>{{ $r->event->name }}</td>
                    <td>{{ $r->venue->name }}</td>
                    <td>{{ $r->event_date->format('M d, Y') }}</td>
                    <td>{{ $r->pax_count }}</td>
                    <td><span style="background:#ede7f6;color:#4a0080;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Set {{ $r->food_set }}</span></td>
                    <td>
                        @if($r->status==='pending') <span class="badge-pending">Pending</span>
                        @elseif($r->status==='confirmed') <span class="badge-confirmed">Confirmed</span>
                        @elseif($r->status==='cancelled') <span class="badge-cancelled">Cancelled</span>
                        @else <span class="badge-completed">Completed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No reservations for this month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection