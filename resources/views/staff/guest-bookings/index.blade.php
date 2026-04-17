@extends('layouts.app')

@section('title', 'Guest Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#2d0a4e;">Guest Bookings</h4>
        <small class="text-muted">Bookings submitted via the website</small>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px;background:linear-gradient(135deg,#4a0080,#7b2ff7);">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['total'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['pending'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px;background:linear-gradient(135deg,#10b981,#059669);">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['confirmed'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Confirmed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px;background:linear-gradient(135deg,#6b7280,#4b5563);">
            <div style="font-size:1.6rem;font-weight:800;color:white;">{{ $stats['completed'] }}</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.8);">Completed</div>
        </div>
    </div>
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
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Conflict</th>
                    <th style="padding:12px 16px;color:#4a0080;font-weight:700;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
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
                            $color = $colors[$b->status] ?? '#6b7280';
                            $labels = ['pending'=>'Pending','pencil'=>'✏️ Pencil','confirmed'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled'];
                            $label = $labels[$b->status] ?? ucfirst($b->status);
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
                        @if($b->has_conflict)
                        <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            ⚠️ Conflict
                        </span>
                        @else
                        <span style="background:#f0fdf4;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            ✓ Clear
                        </span>
                        @endif
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
                    <td colspan="11" class="text-center py-5" style="color:#9ca3af;">
                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No guest bookings yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>
@endsection