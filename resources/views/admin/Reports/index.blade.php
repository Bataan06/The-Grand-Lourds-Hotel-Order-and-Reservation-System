@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #2d0057; font-weight: 800; }
    .stat-card { border: none; border-radius: 15px; padding: 22px; color: white; box-shadow: 0 5px 20px rgba(0,0,0,0.1); height: 100%; min-height: 110px; }
    .stat-card p { opacity:.8; font-size:.8rem; margin-bottom:4px; }
    .stat-card h3 { font-size: 2rem; font-weight: 800; margin: 0; }
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
    .chart-card { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); padding: 24px; }
    .chart-legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 600; color: #4a0080; }
    .legend-dot { width: 13px; height: 13px; border-radius: 50%; flex-shrink: 0; }
    .status-mini { background: #faf5ff; border-radius: 10px; padding: 12px 16px; border-left: 3px solid; }
    .status-mini .s-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #9b59b6; margin-bottom: 2px; }
    .status-mini .s-value { font-size: 1.4rem; font-weight: 800; color: #4a0080; line-height: 1; }
    .status-mini .s-pct   { font-size: 0.7rem; color: #9ca3af; margin-top: 2px; }
    .revenue-card { background: linear-gradient(135deg, #1d4ed8, #60a5fa); border-radius: 15px; padding: 22px; color: white; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }

    /* Report Type Tabs */
    .report-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
    .report-tab { padding: 8px 20px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; border: 2px solid #e9d5ff; color: #7b2ff7; background: white; text-decoration: none; transition: all 0.2s; }
    .report-tab.active { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border-color: transparent; }
    .report-tab:hover:not(.active) { background: #f5f0ff; color: #4a0080; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-title"><i class="fas fa-chart-bar me-2"></i>
        {{ $type === 'daily' ? 'Daily Report' : 'Monthly Report' }}
    </h2>
</div>

{{-- Report Type Tabs --}}
<div class="report-tabs">
    <a href="{{ route('admin.reports.index', ['type'=>'daily', 'date'=>$date]) }}"
       class="report-tab {{ $type === 'daily' ? 'active' : '' }}">
        <i class="fas fa-calendar-day me-1"></i> Daily
    </a>
    <a href="{{ route('admin.reports.index', ['type'=>'monthly', 'month'=>$month]) }}"
       class="report-tab {{ $type === 'monthly' ? 'active' : '' }}">
        <i class="fas fa-calendar-alt me-1"></i> Monthly
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" class="d-flex gap-2 align-items-center mb-4">
    <input type="hidden" name="type" value="{{ $type }}">
    @if($type === 'daily')
        <input type="date" name="date" value="{{ $date }}"
               class="form-control form-control-sm"
               style="border-color:#7b2ff7;border-radius:8px;font-size:0.82rem;width:160px;">
    @else
        <input type="month" name="month" value="{{ $month }}"
               class="form-control form-control-sm"
               style="border-color:#7b2ff7;border-radius:8px;font-size:0.82rem;width:160px;">
    @endif
    <button type="submit" class="btn btn-sm text-white"
            style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;font-size:0.82rem;padding:6px 14px;">
        <i class="fas fa-search me-1"></i> Filter
    </button>
</form>

{{-- Stats Row --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-4">
    <div class="col"><div class="stat-card card-total"><p>Total</p><h3>{{ $summary['total'] }}</h3></div></div>
    <div class="col"><div class="stat-card card-pending"><p>Pending</p><h3>{{ $summary['pending'] }}</h3></div></div>
    <div class="col"><div class="stat-card card-confirmed"><p>Confirmed</p><h3>{{ $summary['confirmed'] }}</h3></div></div>
    <div class="col"><div class="stat-card card-completed"><p>Completed</p><h3>{{ $summary['completed'] }}</h3></div></div>
    <div class="col"><div class="stat-card card-cancelled"><p>Cancelled</p><h3>{{ $summary['cancelled'] }}</h3></div></div>
    <div class="col"><div class="stat-card" style="background:linear-gradient(135deg,#0d47a1,#1976d2);"><p>Total Pax</p><h3>{{ $summary['total_pax'] }}</h3></div></div>
</div>

{{-- Pie Chart + Revenue Row --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <p class="section-label">Reservation Status Breakdown</p>
        <div class="chart-card">
            <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                <div style="position:relative;width:210px;height:210px;flex-shrink:0;">
                    <canvas id="statusPieChart" width="210" height="210"></canvas>
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                        <div style="font-size:1.8rem;font-weight:800;color:#4a0080;">{{ $summary['total'] }}</div>
                        <div style="font-size:0.7rem;color:#9b59b6;font-weight:600;">TOTAL</div>
                    </div>
                </div>
                <div style="flex:1;width:100%;">
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <div class="chart-legend-item"><div class="legend-dot" style="background:#9333ea;"></div> Pending</div>
                        <div class="chart-legend-item"><div class="legend-dot" style="background:#4a0080;"></div> Confirmed</div>
                        <div class="chart-legend-item"><div class="legend-dot" style="background:#10b981;"></div> Completed</div>
                        <div class="chart-legend-item"><div class="legend-dot" style="background:#ef4444;"></div> Cancelled</div>
                    </div>
                    @php
                        $total = $summary['total'];
                        $statusList = [
                            ['label'=>'Pending',   'value'=>$summary['pending'],   'color'=>'#9333ea'],
                            ['label'=>'Confirmed', 'value'=>$summary['confirmed'], 'color'=>'#4a0080'],
                            ['label'=>'Completed', 'value'=>$summary['completed'], 'color'=>'#10b981'],
                            ['label'=>'Cancelled', 'value'=>$summary['cancelled'], 'color'=>'#ef4444'],
                        ];
                    @endphp
                    <div class="row g-2">
                        @foreach($statusList as $s)
                        <div class="col-6">
                            <div class="status-mini" style="border-left-color:{{ $s['color'] }};">
                                <div class="s-label">{{ $s['label'] }}</div>
                                <div class="s-value">{{ $s['value'] }}</div>
                                <div class="s-pct">{{ $total > 0 ? round(($s['value']/$total)*100) : 0 }}% of total</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <p class="section-label">Revenue Summary</p>
        @php
            $totalRevenue = $reservations->whereIn('status',['confirmed','completed'])->sum('total_amount');
            $avgPax = $reservations->count() > 0 ? round($reservations->avg('pax_count')) : 0;
            $completionRate = $total > 0 ? round(($summary['completed']/$total)*100) : 0;
        @endphp
        <div class="revenue-card mb-3">
            <p style="opacity:.8;font-size:.8rem;margin-bottom:4px;"><i class="fas fa-peso-sign me-1"></i> Total Revenue</p>
            <h3 style="font-size:1.8rem;font-weight:800;margin:0;">&#8369;{{ number_format($totalRevenue, 2) }}</h3>
            <p style="opacity:.7;font-size:.75rem;margin-top:4px;margin-bottom:0;">Confirmed + Completed</p>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <div style="background:white;border-radius:12px;padding:14px 16px;box-shadow:0 3px 15px rgba(74,0,128,0.07);border-left:3px solid #7b2ff7;">
                    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;color:#9b59b6;">Avg. Pax</div>
                    <div style="font-size:1.5rem;font-weight:800;color:#4a0080;">{{ $avgPax }}</div>
                    <div style="font-size:0.7rem;color:#9ca3af;">Per reservation</div>
                </div>
            </div>
            <div class="col-6">
                <div style="background:white;border-radius:12px;padding:14px 16px;box-shadow:0 3px 15px rgba(74,0,128,0.07);border-left:3px solid #10b981;">
                    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;color:#9b59b6;">Completion</div>
                    <div style="font-size:1.5rem;font-weight:800;color:#4a0080;">{{ $completionRate }}%</div>
                    <div style="font-size:0.7rem;color:#9ca3af;">Rate</div>
                </div>
            </div>
        </div>
    </div>
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
<p class="section-label mb-3">
    Reservations for
    @if($type === 'daily')
        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
    @else
        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
    @endif
</p>
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
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $r->guest_name }}</strong></td>
                    <td>{{ $r->event->name ?? '—' }}</td>
                    <td>{{ $r->venue->name ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->event_date)->format('M d, Y') }}</td>
                    <td>{{ $r->pax_count }}</td>
                    <td>
                        @if($r->food_set)
                        <span style="background:#ede7f6;color:#4a0080;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Set {{ $r->food_set }}</span>
                        @else <span style="color:#d1d5db;">—</span> @endif
                    </td>
                    <td style="font-weight:700;color:#4a0080;font-size:0.85rem;">
                        @if($r->total_amount) &#8369;{{ number_format($r->total_amount, 2) }}
                        @else <span style="color:#d1d5db;">—</span> @endif
                    </td>
                    <td>
                        @if($r->status==='pending')      <span class="badge-pending">Pending</span>
                        @elseif($r->status==='confirmed') <span class="badge-confirmed">Confirmed</span>
                        @elseif($r->status==='cancelled') <span class="badge-cancelled">Cancelled</span>
                        @else                             <span class="badge-completed">Completed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No reservations for this {{ $type === 'daily' ? 'date' : 'month' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
            datasets: [{
                data: [{{ $summary['pending'] }}, {{ $summary['confirmed'] }}, {{ $summary['completed'] }}, {{ $summary['cancelled'] }}],
                backgroundColor: ['#9333ea', '#4a0080', '#10b981', '#ef4444'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 8,
            }]
        },
        options: {
            cutout: '68%',
            responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const val = context.parsed;
                            const total = {{ $summary['total'] }};
                            const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                            return ` ${context.label}: ${val} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection