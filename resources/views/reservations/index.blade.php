@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 15px; }
    .table tbody tr:hover { background: #f3e5f5; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; }
    .badge-pending { background: #ede7f6; color: #6a0dad; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-confirmed { background: #4a0080; color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-cancelled { background: #d500f9; color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-completed { background: #9c27b0; color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .btn-new { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; }
    .btn-new:hover { opacity: 0.9; color: white; }
    .welcome-banner { background: linear-gradient(135deg, #2d0057, #4a0080, #7b2ff7); border-radius: 15px; padding: 25px 35px; color: white; margin-bottom: 25px; }
    .section-title { font-weight: 700; color: #4a0080; margin-bottom: 15px; border-left: 4px solid #7b2ff7; padding-left: 12px; }
</style>

<div class="welcome-banner">
    <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i> My Reservations</h4>
    <p class="mb-0 mt-1" style="opacity:0.75;">Track all your bookings at The Grand Lourds Hotel.</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="section-title mb-0"><i class="fas fa-list me-2"></i> All Reservations</p>
    <a href="{{ route('user.rooms.index') }}" class="btn btn-new">
        <i class="fas fa-plus me-2"></i> New Reservation
    </a>
</div>

@if(session('success'))
    <div class="alert border-0 rounded-3" style="background:#ede7f6; color:#4a0080;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $reservation->room->name }}</strong></td>
                    <td>{{ $reservation->check_in->format('M d, Y') }}</td>
                    <td>{{ $reservation->check_out->format('M d, Y') }}</td>
                    <td>{{ $reservation->guests }}</td>
                    <td>
                        @if($reservation->status === 'pending')
                            <span class="badge-pending"><i class="fas fa-clock me-1"></i>Pending</span>
                        @elseif($reservation->status === 'confirmed')
                            <span class="badge-confirmed"><i class="fas fa-check me-1"></i>Confirmed</span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge-cancelled"><i class="fas fa-times me-1"></i>Cancelled</span>
                        @else
                            <span class="badge-completed"><i class="fas fa-flag me-1"></i>Completed</span>
                        @endif
                    </td>
                    <td>
                        @if($reservation->status === 'pending')
                        <form action="{{ route('user.reservations.destroy', $reservation) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Cancel this reservation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#ede7f6; color:#6a0dad; border:none;">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </form>
                        @else
                            <span class="text-muted" style="font-size:0.85rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fas fa-calendar fa-3x mb-3 d-block" style="color:#ce93d8;"></i>
                        No reservations yet.
                        <a href="{{ route('user.rooms.index') }}" style="color:#7b2ff7; font-weight:600;">Browse rooms now!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection