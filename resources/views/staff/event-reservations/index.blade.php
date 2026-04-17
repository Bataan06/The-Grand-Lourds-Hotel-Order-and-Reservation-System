@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .filter-bar { background: white; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; box-shadow: 0 3px 15px rgba(74,0,128,0.07); display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .filter-bar select, .filter-bar input { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; color: #333; outline: none; background: #faf5ff; }
    .filter-bar select:focus, .filter-bar input:focus { border-color: #7b2ff7; }

    /* Stat Cards — gradient colored */
    .stats-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .stat-mini {
        flex: 1; min-width: 120px; border-radius: 14px;
        padding: 18px 20px; color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .stat-mini .lbl { font-size: 0.78rem; font-weight: 600; opacity: 0.9; margin-bottom: 6px; }
    .stat-mini .num { font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-mini.total-b     { background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .stat-mini.pending-b   { background: linear-gradient(135deg, #9333ea, #c084fc); }
    .stat-mini.confirmed-b { background: linear-gradient(135deg, #3b0764, #7c3aed); }
    .stat-mini.completed-b { background: linear-gradient(135deg, #047857, #10b981); }
    .stat-mini.cancelled-b { background: linear-gradient(135deg, #991b1b, #ef4444); }

    /* Table */
    .table-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); overflow: hidden; background: white; }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 14px 15px; font-size: 0.83rem; font-weight: 600; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; font-size: 0.85rem; border-color: #f3e8ff; }
    .table tbody tr:hover { background: #faf5ff; }

    /* Badges */
    .badge-pending   { background: #f3e5f5; color: #7b1fa2; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-confirmed { background: #4a0080; color: white;   padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-cancelled { background: #fce4ec; color: #c62828; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display:inline-block; }

    /* Action buttons */
    .btn-view     { background: #ede7f6; color: #4a0080; border: none; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; transition: all 0.2s; text-decoration: none; display:inline-block; font-weight: 600; }
    .btn-confirm  { background: #7b2ff7; color: white; border: none; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; transition: opacity 0.2s; font-weight: 600; }
    .btn-complete { background: #059669; color: white; border: none; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; transition: opacity 0.2s; font-weight: 600; }
    .btn-view:hover    { background: #d1c4e9; color: #4a0080; }
    .btn-confirm:hover, .btn-complete:hover { opacity: 0.85; }

    .guest-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg,#7c3aed,#9333ea); display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem; margin-right: 8px; flex-shrink: 0; }
    .event-date { font-weight: 600; font-size: 0.85rem; color: #1f2937; }
    .event-time { font-size: 0.73rem; color: #7c3aed; margin-top: 2px; display:flex; align-items:center; gap:4px; }
</style>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title mb-0"><i class="fas fa-calendar-check me-2"></i> Event Reservations</h2>
    <a href="{{ route('staff.reports.index') }}" class="btn text-white"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:10px;font-weight:600;font-size:0.85rem;padding:9px 18px;">
        <i class="fas fa-chart-bar me-1"></i> View Reports
    </a>
</div>

{{-- Stat Cards --}}
<div class="stats-row">
    <div class="stat-mini total-b">
        <div class="lbl">Total</div>
        <div class="num">{{ $reservations->count() }}</div>
    </div>
    <div class="stat-mini pending-b">
        <div class="lbl">Pending</div>
        <div class="num">{{ $reservations->where('status','pending')->count() }}</div>
    </div>
    <div class="stat-mini confirmed-b">
        <div class="lbl">Confirmed</div>
        <div class="num">{{ $reservations->where('status','confirmed')->count() }}</div>
    </div>
    <div class="stat-mini completed-b">
        <div class="lbl">Completed</div>
        <div class="num">{{ $reservations->where('status','completed')->count() }}</div>
    </div>
    <div class="stat-mini cancelled-b">
        <div class="lbl">Cancelled</div>
        <div class="num">{{ $reservations->where('status','cancelled')->count() }}</div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <span style="color:#7b2ff7;font-size:0.85rem;font-weight:600;"><i class="fas fa-filter me-1"></i> Filter:</span>
    <select id="filterStatus" onchange="filterTable()">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <select id="filterEvent" onchange="filterTable()">
        <option value="">All Events</option>
        @foreach($reservations->pluck('event.name')->unique() as $ev)
            <option value="{{ strtolower($ev) }}">{{ $ev }}</option>
        @endforeach
    </select>
    <input type="text" id="searchInput" placeholder="Search guest name..." oninput="filterTable()" style="min-width:200px;">
    <button onclick="resetFilter()"
            style="background:#f5f0ff;color:#7b2ff7;border:1.5px solid #e9d5ff;border-radius:8px;padding:8px 14px;font-size:0.83rem;font-weight:600;cursor:pointer;">
        <i class="fas fa-times me-1"></i> Reset
    </button>
</div>

{{-- Table --}}
<div class="table-card">
    <table class="table mb-0" id="reservationsTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Guest</th>
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
            <tr data-status="{{ $r->status }}"
                data-event="{{ strtolower($r->event->name) }}"
                data-guest="{{ strtolower($r->user->name) }}">

                <td style="color:#9b59b6;font-weight:600;">{{ $loop->iteration }}</td>

                {{-- Guest --}}
                <td>
                    <div class="d-flex align-items-center">
                        <span class="guest-avatar">{{ strtoupper(substr($r->user->name, 0, 1)) }}</span>
                        <div>
                            <div style="font-weight:600;font-size:0.85rem;color:#1f2937;">{{ $r->user->name }}</div>
                            <div style="font-size:0.73rem;color:#9b59b6;">{{ $r->user->email }}</div>
                        </div>
                    </div>
                </td>

                {{-- Event --}}
                <td><span style="font-weight:600;color:#4a0080;font-size:0.85rem;">{{ $r->event->name }}</span></td>

                {{-- Celebrant --}}
                <td style="font-size:0.85rem;">{{ $r->celebrant_name ?? '—' }}</td>

                {{-- Venue --}}
                <td style="font-size:0.85rem;">{{ $r->venue->name }}</td>

                {{-- Date & Time --}}
                <td>
                    <div class="event-date">{{ $r->event_date->format('M d, Y') }}</div>
                    @if($r->event_time_start)
                        <div class="event-time">
                            <i class="fas fa-clock" style="font-size:10px;"></i>
                            {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                        </div>
                    @endif
                </td>

                {{-- Pax --}}
                <td>
                    <span style="font-weight:700;color:#4a0080;">{{ number_format($r->pax_count) }}</span>
                    <span style="color:#9b59b6;font-size:0.75rem;"> pax</span>
                </td>

                {{-- Food Set --}}
                <td>
                    <span style="background:#ede7f6;color:#4a0080;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">
                        Set {{ $r->food_set }}
                    </span>
                </td>

                {{-- Total Bill --}}
                <td>
                    @if($r->total_amount)
                        <div style="font-weight:700;color:#4a0080;font-size:0.88rem;">
                            &#8369;{{ number_format($r->total_amount, 2) }}
                        </div>
                        @if($r->price_per_pax)
                            <div style="font-size:0.72rem;color:#9ca3af;">
                                &#8369;{{ number_format($r->price_per_pax, 2) }}/pax
                            </div>
                        @endif
                    @else
                        <span style="color:#d1d5db;font-size:12px;">—</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    @if($r->status === 'pending')      <span class="badge-pending">Pending</span>
                    @elseif($r->status === 'confirmed') <span class="badge-confirmed">Confirmed</span>
                    @elseif($r->status === 'cancelled') <span class="badge-cancelled">Cancelled</span>
                    @else                               <span class="badge-completed">Completed</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('staff.event-reservations.show', $r->id) }}" class="btn-view">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        @if($r->status === 'pending')
                        <form action="{{ route('staff.event-reservations.confirm', $r->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-confirm">
                                <i class="fas fa-check me-1"></i>Confirm
                            </button>
                        </form>
                        @endif
                        @if($r->status === 'confirmed')
                        <form action="{{ route('staff.event-reservations.complete', $r->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-complete">
                                <i class="fas fa-flag-checkered me-1"></i>Complete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5 text-muted">
                    <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                    No reservations found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    const status = document.getElementById('filterStatus').value;
    const event  = document.getElementById('filterEvent').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#reservationsTable tbody tr[data-status]').forEach(row => {
        const matchStatus = !status || row.dataset.status === status;
        const matchEvent  = !event  || row.dataset.event === event;
        const matchSearch = !search || row.dataset.guest.includes(search);
        row.style.display = (matchStatus && matchEvent && matchSearch) ? '' : 'none';
    });
}
function resetFilter() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterEvent').value  = '';
    document.getElementById('searchInput').value  = '';
    filterTable();
}
</script>
@endsection