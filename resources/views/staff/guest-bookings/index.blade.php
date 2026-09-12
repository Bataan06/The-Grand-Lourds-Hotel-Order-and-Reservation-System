@extends('layouts.app')

@section('title', 'Guest Bookings')

@section('content')
<style>
    .stat-filter-card { cursor: pointer; transition: all 0.2s; border: 3px solid transparent !important; }
    .stat-filter-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important; }
    .stat-filter-card.active-filter { border: 3px solid white !important; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important; }
    .filter-bar { background:#fff; border-radius:12px; padding:14px 16px; border:1px solid #e9d5ff; margin-bottom:16px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-input { border:1.5px solid #e9d5ff; border-radius:8px; padding:7px 12px; font-size:12px; color:#3b0764; outline:none; transition:border .2s; background:#faf7ff; }
    .filter-input:focus { border-color:#7c3aed; }
    .filter-select { border:1.5px solid #e9d5ff; border-radius:8px; padding:7px 12px; font-size:12px; color:#3b0764; outline:none; background:#faf7ff; cursor:pointer; }
    .filter-select:focus { border-color:#7c3aed; }
    .btn-clear-filters { background:none; border:1.5px solid #e9d5ff; border-radius:8px; padding:7px 14px; font-size:12px; color:#9b59b6; cursor:pointer; transition:all .2s; }
    .btn-clear-filters:hover { background:#f5f0ff; border-color:#a78bfa; color:#7c3aed; }
    .btn-print { background:linear-gradient(135deg,#4a0080,#7b2ff7); border:none; border-radius:8px; padding:7px 16px; font-size:12px; color:white; cursor:pointer; transition:all .2s; font-weight:600; display:inline-flex; align-items:center; gap:6px; }
    .btn-print:hover { opacity:0.88; transform:translateY(-1px); box-shadow:0 4px 14px rgba(74,0,128,0.25); }

    /* ── Print Styles ── */
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: fixed; top: 0; left: 0; width: 100%; padding: 24px; }

        .print-header { text-align: center; margin-bottom: 20px; }
        .print-header h2 { font-size: 18px; font-weight: 800; color: #2d0a4e; margin: 0; }
        .print-header p { font-size: 12px; color: #6b7280; margin: 4px 0 0; }
        .print-meta { display: flex; justify-content: space-between; font-size: 11px; color: #6b7280; margin-bottom: 14px; border-bottom: 2px solid #4a0080; padding-bottom: 8px; }

        .print-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .print-table th { background: #4a0080 !important; color: white !important; padding: 8px 10px; text-align: left; font-weight: 700; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .print-table td { padding: 7px 10px; border-bottom: 1px solid #e9d5ff; color: #111; vertical-align: top; }
        .print-table tr:nth-child(even) td { background: #faf7ff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        .print-summary { margin-top: 16px; display: flex; gap: 20px; font-size: 11px; }
        .print-summary-item { padding: 8px 14px; border-radius: 6px; }

        .status-badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; }
        .print-footer { margin-top: 24px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e9d5ff; padding-top: 10px; }
    }
</style>

<div class="mb-4">
    <h4 class="fw-bold mb-0" style="color:#2d0a4e;">{{ !empty($historyMode) ? 'Reservation History' : 'Guest Bookings' }}</h4>
    <small class="text-muted">{{ !empty($historyMode) ? 'Completed, cancelled, and past event bookings' : 'Bookings submitted via the website' }}</small>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 stat-filter-card" id="card-all"
             style="border-radius:12px;background:linear-gradient(135deg,#4a0080,#7b2ff7);"
             onclick="filterByStatus('all')">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['total'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 stat-filter-card" id="card-pending"
             style="border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);"
             onclick="filterByStatus('pending')">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['pending'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 stat-filter-card" id="card-confirmed"
             style="border-radius:12px;background:linear-gradient(135deg,#10b981,#059669);"
             onclick="filterByStatus('confirmed')">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['confirmed'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Confirmed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 stat-filter-card" id="card-completed"
             style="border-radius:12px;background:linear-gradient(135deg,#6b7280,#4b5563);"
             onclick="filterByStatus('completed')">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['completed'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Completed</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    {{-- Event Type --}}
    <div style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-calendar-alt" style="color:#a78bfa;font-size:12px;"></i>
        <span style="font-size:12px;color:#9b59b6;white-space:nowrap;">Event Type</span>
        <select id="eventFilter" class="filter-select" onchange="applyFilters()">
            <option value="">All</option>
            @foreach($bookings->pluck('event.name')->unique()->filter() as $eventName)
                <option value="{{ strtolower($eventName) }}">{{ $eventName }}</option>
            @endforeach
        </select>
    </div>

    {{-- Month --}}
    <div style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-calendar" style="color:#a78bfa;font-size:12px;"></i>
        <span style="font-size:12px;color:#9b59b6;white-space:nowrap;">Month</span>
        <input type="month" id="monthFilter" class="filter-input" onchange="applyFilters()">
    </div>

    {{-- Clear --}}
    <button class="btn-clear-filters" onclick="clearAllFilters()">
        <i class="fas fa-times me-1"></i> Clear Filters
    </button>

    {{-- Print Button --}}
    <button class="btn-print" onclick="printBookings()">
        <i class="fas fa-print"></i> Print
    </button>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px;">
            <thead style="background:#f5f0ff;">
                <tr>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Ref #</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Guest</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Event</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Venue</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Date</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Pax</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Total</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Status</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Payment</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Action</th>
                </tr>
            </thead>
            <tbody id="bookingsTable">
                @forelse($bookings as $b)
                <tr class="booking-row"
                    data-status="{{ $b->display_status }}"
                    data-event="{{ strtolower($b->event->name) }}"
                    data-event-label="{{ $b->event->name }}"
                    data-month="{{ $b->event_date->format('Y-m') }}"
                    data-ref="{{ $b->reference_no }}"
                    data-guest="{{ $b->guest_name }}"
                    data-phone="{{ $b->guest_phone }}"
                    data-email="{{ $b->guest_email }}"
                    data-venue="{{ $b->venue->name }}"
                    data-date="{{ $b->event_date->format('M d, Y') }}"
                    data-time="{{ $b->event_time_start ? \Carbon\Carbon::parse($b->event_time_start)->format('h:i A') : '' }}"
                    data-pax="{{ number_format($b->pax_count) }}"
                    data-total="₱{{ number_format($b->total_amount, 2) }}"
                    data-payment="{{ $b->payment_status ?? 'unpaid' }}">
                    <td style="padding:12px 16px;">
                        <span style="font-weight:700;color:#4a0080;font-size:11px;">{{ $b->reference_no }}</span>
                    </td>
                    <td style="padding:12px 16px;">
                        <div style="font-weight:600;">{{ $b->guest_name }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $b->guest_phone }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $b->guest_email }}</div>
                    </td>
                    <td style="padding:12px 16px;">
                        <div>{{ $b->event->name }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $b->celebrant_name }}</div>
                    </td>
                    <td style="padding:12px 16px;">{{ $b->venue->name }}</td>
                    <td style="padding:12px 16px;">
                        <div>{{ $b->event_date->format('M d, Y') }}</div>
                        @if($b->event_time_start)
                        <div style="font-size:11px;color:#9ca3af;">{{ \Carbon\Carbon::parse($b->event_time_start)->format('h:i A') }}</div>
                        @endif
                    </td>
                    <td style="padding:12px 16px;">{{ number_format($b->pax_count) }}</td>
                    <td style="padding:12px 16px;font-weight:700;color:#4a0080;">₱{{ number_format($b->total_amount, 2) }}</td>
                    <td style="padding:12px 16px;">
                        @php
                            $colors = ['pending'=>'#f59e0b','pencil'=>'#92400e','confirmed'=>'#10b981','completed'=>'#6b7280','cancelled'=>'#ef4444'];
                            $displayStatus = $b->display_status;
                            $colors['ongoing'] = '#2563eb';
                            $color = $colors[$displayStatus] ?? '#6b7280';
                            $labels = ['pending'=>'Pending','pencil'=>' Pencil','confirmed'=>'Confirmed','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled'];
                            $label = $labels[$displayStatus] ?? ucfirst($displayStatus);
                        @endphp
                        <span style="background:{{ $color }}20;color:{{ $color }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            {{ $label }}
                        </span>
                    </td>
                    <td style="padding:12px 16px;">
                        @php
                            $pColors = ['unpaid'=>'#ef4444','partial'=>'#f59e0b','paid'=>'#10b981'];
                            $pLabels = ['unpaid'=>'Unpaid','partial'=>'Partial','paid'=>'Paid'];
                            $pStatus = $b->payment_status ?? 'unpaid';
                            $pColor  = $pColors[$pStatus] ?? '#ef4444';
                            $pLabel  = $pLabels[$pStatus] ?? 'Unpaid';
                        @endphp
                        <span style="background:{{ $pColor }}20;color:{{ $pColor }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            {{ $pLabel }}
                        </span>
                    </td>
                    <td style="padding:12px 16px;">
                        <a href="{{ route('staff.guest-bookings.show', $b->id) }}"
                           class="btn btn-sm" style="background:#f0e6ff;color:#4a0080;border:none;font-size:11px;font-weight:600;">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5" style="color:#9ca3af;">
                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No guest bookings yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div id="emptyFilter" style="display:none;text-align:center;padding:40px;color:#9ca3af;">
            <i class="fas fa-inbox fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
            <div>No bookings match your filters.</div>
        </div>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>

{{-- Hidden Print Area --}}
<div id="print-area" style="display:none;"></div>

<script>
let currentStatus = 'all';

function filterByStatus(status) {
    currentStatus = status;
    document.querySelectorAll('.stat-filter-card').forEach(c => c.classList.remove('active-filter'));
    const activeCard = document.getElementById('card-' + status);
    if (activeCard) activeCard.classList.add('active-filter');
    applyFilters();
}

function applyFilters() {
    const event = document.getElementById('eventFilter').value.toLowerCase();
    const month = document.getElementById('monthFilter').value;

    const rows = document.querySelectorAll('.booking-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const matchStatus = currentStatus === 'all' || row.dataset.status === currentStatus;
        const matchEvent  = !event || row.dataset.event.includes(event);
        const matchMonth  = !month || row.dataset.month === month;

        if (matchStatus && matchEvent && matchMonth) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('emptyFilter').style.display = visibleCount === 0 ? 'block' : 'none';
}

function clearAllFilters() {
    currentStatus = 'all';
    document.getElementById('eventFilter').value = '';
    document.getElementById('monthFilter').value = '';
    document.querySelectorAll('.stat-filter-card').forEach(c => c.classList.remove('active-filter'));
    document.getElementById('card-all').classList.add('active-filter');
    document.querySelectorAll('.booking-row').forEach(r => r.style.display = '');
    document.getElementById('emptyFilter').style.display = 'none';
}

function printBookings() {
    const eventFilter   = document.getElementById('eventFilter');
    const monthFilter   = document.getElementById('monthFilter').value;
    const eventLabel    = eventFilter.options[eventFilter.selectedIndex].text;
    const monthLabel    = monthFilter
        ? new Date(monthFilter + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
        : 'All Months';

    // Collect visible rows
    const visibleRows = Array.from(document.querySelectorAll('.booking-row'))
        .filter(r => r.style.display !== 'none');

    if (visibleRows.length === 0) {
        alert('No bookings to print based on current filters.');
        return;
    }

    // Status colors for print
    const statusColors = {
        pending:   { bg: '#fff7ed', text: '#d97706' },
        confirmed: { bg: '#ecfdf5', text: '#059669' },
        completed: { bg: '#f3f4f6', text: '#4b5563' },
        cancelled: { bg: '#fef2f2', text: '#ef4444' },
        pencil:    { bg: '#fefce8', text: '#92400e' },
    };
    const paymentColors = {
        unpaid:  { bg: '#fef2f2', text: '#ef4444' },
        partial: { bg: '#fff7ed', text: '#d97706' },
        paid:    { bg: '#ecfdf5', text: '#059669' },
    };

    // Build summary counts from visible rows
    const counts = { total: visibleRows.length, pending: 0, confirmed: 0, completed: 0, cancelled: 0 };
    let totalRevenue = 0;
    visibleRows.forEach(r => {
        const s = r.dataset.status;
        if (counts[s] !== undefined) counts[s]++;
        // Parse total amount
        const amt = parseFloat(r.dataset.total.replace(/[₱,]/g, '')) || 0;
        if (s === 'confirmed' || s === 'completed') totalRevenue += amt;
    });

    // Build table rows HTML
    const rowsHtml = visibleRows.map((r, i) => {
        const s  = r.dataset.status;
        const sc = statusColors[s] || { bg: '#f3f4f6', text: '#4b5563' };
        const p  = r.dataset.payment;
        const pc = paymentColors[p] || paymentColors.unpaid;
        return `
        <tr>
            <td>${i + 1}</td>
            <td style="font-weight:700;color:#4a0080;">${r.dataset.ref}</td>
            <td>
                <div style="font-weight:600;">${r.dataset.guest}</div>
                <div style="font-size:10px;color:#6b7280;">${r.dataset.phone}</div>
            </td>
            <td>${r.dataset.eventLabel}</td>
            <td>${r.dataset.venue}</td>
            <td>
                <div>${r.dataset.date}</div>
                ${r.dataset.time ? `<div style="font-size:10px;color:#6b7280;">${r.dataset.time}</div>` : ''}
            </td>
            <td style="text-align:center;">${r.dataset.pax}</td>
            <td style="font-weight:700;color:#4a0080;">${r.dataset.total}</td>
            <td>
                <span style="background:${sc.bg};color:${sc.text};padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">
                    ${s.charAt(0).toUpperCase() + s.slice(1)}
                </span>
            </td>
            <td>
                <span style="background:${pc.bg};color:${pc.text};padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">
                    ${p.charAt(0).toUpperCase() + p.slice(1)}
                </span>
            </td>
        </tr>`;
    }).join('');

    const now = new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });

    const printHtml = `
        <div class="print-header">
            <h2>The Grand Lourds Hotel</h2>
            <p>Guest Bookings Report</p>
        </div>
        <div class="print-meta">
            <div>
                <strong>Event Type:</strong> ${eventLabel} &nbsp;|&nbsp;
                <strong>Month:</strong> ${monthLabel} &nbsp;|&nbsp;
                <strong>Status:</strong> ${currentStatus === 'all' ? 'All' : currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1)}
            </div>
            <div>Printed: ${now}</div>
        </div>

        {{-- Summary Row --}}
        <div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
            <div style="background:#ede9fe;color:#4a0080;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Total: ${counts.total}
            </div>
            <div style="background:#fff7ed;color:#d97706;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Pending: ${counts.pending}
            </div>
            <div style="background:#ecfdf5;color:#059669;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Confirmed: ${counts.confirmed}
            </div>
            <div style="background:#f3f4f6;color:#4b5563;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Completed: ${counts.completed}
            </div>
            <div style="background:#fef2f2;color:#ef4444;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Cancelled: ${counts.cancelled}
            </div>
            <div style="background:#1e3a5f;color:white;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;">
                Revenue: ₱${totalRevenue.toLocaleString('en-PH', {minimumFractionDigits:2})}
            </div>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ref #</th>
                    <th>Guest</th>
                    <th>Event</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>${rowsHtml}</tbody>
        </table>

        <div class="print-footer">
            Grand Lourds Hotel &mdash; Calasiao, Pangasinan &mdash; Generated on ${now}
        </div>
    `;

    const printArea = document.getElementById('print-area');
    printArea.innerHTML = printHtml;
    printArea.style.display = 'block';
    window.print();
    printArea.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('card-all').classList.add('active-filter');
});
</script>
@endsection
