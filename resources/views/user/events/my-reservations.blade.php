@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 15px; }
    .table tbody tr:hover { background: #f3e5f5; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; }

    /* Status Badges */
    .badge-pending   { background: #ede7f6; color: #6a0dad; padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; display: inline-block; }
    .badge-confirmed { background: #4a0080; color: white;   padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; display: inline-block; }
    .badge-cancelled { background: #d500f9; color: white;   padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; display: inline-block; }
    .badge-completed { background: #9c27b0; color: white;   padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; display: inline-block; }

    /* Date & Time */
    .event-date { font-weight: 600; font-size: 0.88rem; color: #1f2937; }
    .event-time { font-size: 0.75rem; color: #7c3aed; margin-top: 3px; display: flex; align-items: center; gap: 4px; }

    /* Summary Cards */
    .summary-card { border: none; border-radius: 12px; padding: 16px 20px; }
    .summary-card .summary-label { font-size: 12px; color: #9ca3af; margin-bottom: 4px; }
    .summary-card .summary-value { font-size: 24px; font-weight: 700; }

    /* Cancel Button */
    .btn-cancel {
        background: transparent;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        line-height: 1;
    }
    .btn-cancel:hover { background: #fee2e2; color: #dc2626; border-color: #f87171; }
</style>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="summary-card" style="background:#f5f3ff;">
            <div class="summary-label">Total Reservations</div>
            <div class="summary-value" style="color:#7c3aed;">{{ $reservations->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card" style="background:#fffbeb;">
            <div class="summary-label">Pending</div>
            <div class="summary-value" style="color:#d97706;">{{ $reservations->where('status','pending')->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card" style="background:#f0fdf4;">
            <div class="summary-label">Confirmed</div>
            <div class="summary-value" style="color:#059669;">{{ $reservations->where('status','confirmed')->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card" style="background:#fff1f2;">
            <div class="summary-label">Cancelled</div>
            <div class="summary-value" style="color:#dc2626;">{{ $reservations->where('status','cancelled')->count() }}</div>
        </div>
    </div>
</div>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="fas fa-calendar-check me-2"></i> My Reservations
    </h2>
    <a href="{{ route('user.events.index') }}" class="btn"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;border-radius:8px;font-weight:600;">
        <i class="fas fa-plus me-2"></i> New Reservation
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Celebrant</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Food Set</th>
                    <th>Total Bill</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                <tr>
                    {{-- # --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- Event --}}
                    <td><strong>{{ $r->event->name }}</strong></td>

                    {{-- Celebrant --}}
                    <td>
                        @if($r->celebrant_name)
                            {{ $r->celebrant_name }}
                        @else
                            <span style="color:#d1d5db;font-size:12px;">—</span>
                        @endif
                    </td>

                    {{-- Venue --}}
                    <td>{{ $r->venue->name }}</td>

                    {{-- Date + Time below --}}
                    <td>
                        <div class="event-date">
                            {{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}
                        </div>
                        @if($r->event_time_start)
                            <div class="event-time">
                                <i class="fas fa-clock" style="font-size:10px;"></i>
                                {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                            </div>
                        @endif
                    </td>

                    {{-- Pax --}}
                    <td>{{ number_format($r->pax_count) }}</td>

                    {{-- Food Set --}}
                    <td>
                        <span class="badge" style="background:#ede7f6;color:#4a0080;">
                            Set {{ $r->food_set }}
                        </span>
                    </td>

                    {{-- Total Bill --}}
                    <td>
                        @if($r->total_amount)
                            <div style="font-weight:700;color:#4a0080;font-size:0.9rem;">
                                &#8369;{{ number_format($r->total_amount, 2) }}
                            </div>
                            @if($r->price_per_pax)
                                <div style="font-size:0.72rem;color:#9ca3af;margin-top:2px;">
                                    &#8369;{{ number_format($r->price_per_pax, 2) }}/pax
                                </div>
                            @endif
                        @else
                            <span style="color:#d1d5db;font-size:12px;">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
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

                    {{-- Actions --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">

                            {{-- View --}}
                            <a href="{{ route('user.reservations.show', $r->id) }}"
                               class="btn btn-sm text-white"
                               style="background:#7b2ff7;border-radius:8px;"
                               title="View details">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Cancel — only if pending or confirmed --}}
                            @if(in_array($r->status, ['pending', 'confirmed']))
                                <form action="{{ route('user.reservations.cancel', $r->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-cancel">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No reservations yet. <a href="{{ route('user.events.index') }}" style="color:#7b2ff7;">Book now!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection