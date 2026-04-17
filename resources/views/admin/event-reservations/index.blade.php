@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }

    .stats-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .stat-mini { flex: 1; min-width: 100px; border-radius: 14px; padding: 16px 18px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.1s; }
    .stat-mini:hover { transform: translateY(-2px); }
    .stat-mini .lbl { font-size: 0.75rem; font-weight: 600; opacity: 0.9; margin-bottom: 4px; }
    .stat-mini .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-mini.total-b     { background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .stat-mini.pending-b   { background: linear-gradient(135deg, #9333ea, #c084fc); }
    .stat-mini.confirmed-b { background: linear-gradient(135deg, #3b0764, #7c3aed); }
    .stat-mini.completed-b { background: linear-gradient(135deg, #047857, #10b981); }
    .stat-mini.cancelled-b { background: linear-gradient(135deg, #991b1b, #ef4444); }

    .filter-bar { background: white; border-radius: 14px; padding: 14px 18px; margin-bottom: 20px; box-shadow: 0 3px 15px rgba(74,0,128,0.07); display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filter-bar select, .filter-bar input { border: 1.5px solid #e9d5ff; border-radius: 8px; padding: 7px 10px; font-size: 0.82rem; color: #333; outline: none; background: #faf5ff; }
    .filter-bar select:focus, .filter-bar input:focus { border-color: #7b2ff7; }

    .table-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); overflow: hidden; background: white; }
    .res-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .res-table thead tr { background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .res-table thead th { color: white; border: none; padding: 12px 12px; font-size: 0.78rem; font-weight: 600; white-space: nowrap; }
    .res-table tbody td { padding: 12px 12px; vertical-align: middle; font-size: 0.82rem; border-bottom: 1px solid #f3e8ff; }
    .res-table tbody tr:last-child td { border-bottom: none; }
    .res-table tbody tr:hover { background: #faf5ff; }

    .badge-pending   { background:#f3e5f5;color:#7b1fa2;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-confirmed { background:#4a0080;color:white;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-cancelled { background:#fce4ec;color:#c62828;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-completed { background:#e8f5e9;color:#2e7d32;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-unpaid    { background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-partial   { background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }
    .badge-paid      { background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;display:inline-block;white-space:nowrap; }

    .guest-avatar { width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#9333ea);display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.75rem;margin-right:6px;flex-shrink:0; }
</style>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title mb-0"><i class="fas fa-calendar-check me-2"></i> All Reservations</h2>
    <a href="{{ route('admin.reports.index') }}" class="btn text-white"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:10px;font-weight:600;font-size:0.85rem;padding:9px 18px;">
        <i class="fas fa-chart-bar me-1"></i> View Reports
    </a>
</div>

{{-- Stat Cards --}}
<div class="stats-row">
    <div class="stat-mini total-b" id="card-all" onclick="filterByCard('all')"><div class="lbl">Total</div><div class="num">{{ $reservations->count() }}</div></div>
    <div class="stat-mini pending-b" id="card-pending" onclick="filterByCard('pending')"><div class="lbl">Pending</div><div class="num">{{ $reservations->where('status','pending')->count() }}</div></div>
    <div class="stat-mini confirmed-b" id="card-confirmed" onclick="filterByCard('confirmed')"><div class="lbl">Confirmed</div><div class="num">{{ $reservations->where('status','confirmed')->count() }}</div></div>
    <div class="stat-mini completed-b" id="card-completed" onclick="filterByCard('completed')"><div class="lbl">Completed</div><div class="num">{{ $reservations->where('status','completed')->count() }}</div></div>
    <div class="stat-mini cancelled-b" id="card-cancelled" onclick="filterByCard('cancelled')"><div class="lbl">Cancelled</div><div class="num">{{ $reservations->where('status','cancelled')->count() }}</div></div>
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
        @foreach($reservations->pluck('event.name')->unique()->filter() as $ev)
            <option value="{{ strtolower($ev) }}">{{ $ev }}</option>
        @endforeach
    </select>
    <input type="text" id="searchInput" placeholder="Search guest name..." oninput="filterTable()" style="min-width:180px;">
    <button onclick="resetFilter()" style="background:#f5f0ff;color:#7b2ff7;border:1.5px solid #e9d5ff;border-radius:8px;padding:7px 14px;font-size:0.82rem;font-weight:600;cursor:pointer;">
        <i class="fas fa-times me-1"></i> Reset
    </button>
</div>

{{-- Table --}}
<div class="table-card">
    <div style="overflow-x:auto;">
    <table class="res-table" id="reservationsTable">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th style="width:110px;">Ref #</th>
                <th>Guest</th>
                <th style="width:110px;">Event</th>
                <th style="width:110px;">Venue</th>
                <th style="width:105px;">Date</th>
                <th style="width:55px;">Pax</th>
                <th style="width:60px;">Set</th>
                <th style="width:120px;">Total</th>
                <th style="width:90px;">Status</th>
                <th style="width:80px;">Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
            <tr data-status="{{ $r->status }}"
                data-event="{{ strtolower($r->event->name ?? '') }}"
                data-guest="{{ strtolower($r->guest_name) }}">

                <td style="color:#9b59b6;font-weight:600;">{{ $loop->iteration }}</td>

                <td style="font-weight:700;font-size:0.75rem;color:#5b21b6;">{{ $r->reference_no }}</td>

                <td>
                    <div class="d-flex align-items-center">
                        <span class="guest-avatar">{{ strtoupper(substr($r->guest_name, 0, 1)) }}</span>
                        <div>
                            <div style="font-weight:600;font-size:0.83rem;color:#1f2937;">{{ $r->guest_name }}</div>
                            <div style="font-size:0.72rem;color:#9b59b6;">{{ $r->guest_phone }}</div>
                            <div style="font-size:0.72rem;color:#9b59b6;">{{ $r->guest_email }}</div>
                        </div>
                    </div>
                </td>

                <td style="font-weight:600;color:#4a0080;font-size:0.82rem;">{{ $r->event->name ?? '—' }}</td>

                <td style="font-size:0.82rem;">{{ $r->venue->name ?? '—' }}</td>

                <td>
                    <div style="font-weight:600;font-size:0.82rem;white-space:nowrap;">{{ $r->event_date->format('M d, Y') }}</div>
                    @if($r->event_time_start)
                    <div style="font-size:0.72rem;color:#7c3aed;white-space:nowrap;">
                        <i class="fas fa-clock" style="font-size:10px;"></i>
                        {{ \Carbon\Carbon::parse($r->event_time_start)->format('g:i A') }}
                    </div>
                    @endif
                </td>

                <td style="font-weight:700;color:#4a0080;">{{ number_format($r->pax_count) }}</td>

                <td>
                    <span style="background:#ede7f6;color:#4a0080;padding:2px 8px;border-radius:20px;font-size:0.72rem;font-weight:600;white-space:nowrap;">
                        Set {{ $r->food_set }}
                    </span>
                </td>

                <td>
                    <div style="font-weight:700;color:#4a0080;font-size:0.85rem;">&#8369;{{ number_format($r->total_amount, 2) }}</div>
                    <div style="font-size:0.70rem;color:#9ca3af;">&#8369;{{ number_format($r->price_per_pax, 2) }}/pax</div>
                </td>

                <td>
                    @if($r->status === 'pending')      <span class="badge-pending">Pending</span>
                    @elseif($r->status === 'confirmed') <span class="badge-confirmed">Confirmed</span>
                    @elseif($r->status === 'cancelled') <span class="badge-cancelled">Cancelled</span>
                    @else                               <span class="badge-completed">Completed</span>
                    @endif
                </td>

                <td>
                    @php $ps = $r->payment_status ?? 'unpaid'; @endphp
                    @if($ps === 'paid')        <span class="badge-paid">Paid</span>
                    @elseif($ps === 'partial') <span class="badge-partial">Partial</span>
                    @else                      <span class="badge-unpaid">Unpaid</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5" style="color:#9ca3af;">
                    <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                    No reservations found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
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

function filterByCard(status) {
    document.getElementById('filterStatus').value = status === 'all' ? '' : status;
    filterTable();
}

function resetFilter() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterEvent').value  = '';
    document.getElementById('searchInput').value  = '';
    filterTable();
}
</script>
@endsection