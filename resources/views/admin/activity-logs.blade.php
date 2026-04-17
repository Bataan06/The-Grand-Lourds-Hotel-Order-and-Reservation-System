@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 15px; }
    .table tbody tr:hover { background: #f3e5f5; }
    .table tbody td { padding: 12px 15px; vertical-align: middle; }

    .action-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
    .action-login                      { background: #e8f5e9; color: #2e7d32; }
    .action-logout                     { background: #fce4ec; color: #c62828; }
    .action-register                   { background: #e3f2fd; color: #1565c0; }
    .action-reservation_created        { background: #e3f2fd; color: #1565c0; }
    .action-reservation_cancelled      { background: #fff3e0; color: #e65100; }
    .action-reservation_confirmed      { background: #e8f5e9; color: #2e7d32; }
    .action-reservation_completed      { background: #ede7f6; color: #4a148c; }
    .action-reservation_deleted        { background: #fce4ec; color: #c62828; }
    .action-reservation_status_updated { background: #fff8e1; color: #f57f17; }
    .action-receipt_printed            { background: #e0f2f1; color: #00695c; }
    .action-profile_updated            { background: #f3e5f5; color: #6a1b9a; }

    .role-badge { padding: 3px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 600; }
    .role-admin { background: #4a0080; color: white; }
    .role-staff { background: #1565c0; color: white; }
    .role-user  { background: #ede7f6; color: #4a0080; }

    .filter-bar { background: white; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(123,47,247,0.08); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-clipboard-list me-2"></i> Activity Logs</h2>
    <span style="font-size:0.85rem;color:#9b59b6;">
        Total: <strong>{{ $logs->total() }}</strong> records
    </span>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label style="font-size:0.75rem;font-weight:600;color:#9b59b6;">Search User</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Type a name...">
        </div>
        <div class="col-md-4">
            <label style="font-size:0.75rem;font-weight:600;color:#9b59b6;">Filter by Action</label>
            <select name="action" class="form-select form-select-sm">
                <option value="">All Actions</option>
                <option value="login"                      {{ request('action')==='login'                      ?'selected':'' }}>Login</option>
                <option value="logout"                     {{ request('action')==='logout'                     ?'selected':'' }}>Logout</option>
                <option value="register"                   {{ request('action')==='register'                   ?'selected':'' }}>Register</option>
                <option value="reservation_created"        {{ request('action')==='reservation_created'        ?'selected':'' }}>Reservation Created</option>
                <option value="reservation_cancelled"      {{ request('action')==='reservation_cancelled'      ?'selected':'' }}>Reservation Cancelled</option>
                <option value="reservation_confirmed"      {{ request('action')==='reservation_confirmed'      ?'selected':'' }}>Reservation Confirmed</option>
                <option value="reservation_completed"      {{ request('action')==='reservation_completed'      ?'selected':'' }}>Reservation Completed</option>
                <option value="reservation_deleted"        {{ request('action')==='reservation_deleted'        ?'selected':'' }}>Reservation Deleted</option>
                <option value="reservation_status_updated" {{ request('action')==='reservation_status_updated' ?'selected':'' }}>Status Updated</option>
                <option value="receipt_printed"            {{ request('action')==='receipt_printed'            ?'selected':'' }}>Receipt Printed</option>
                <option value="profile_updated"            {{ request('action')==='profile_updated'            ?'selected':'' }}>Profile Updated</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-sm text-white w-100" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;">
                <i class="fas fa-search me-1"></i> Filter
            </button>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm w-100" style="border:1px solid #ce93d8;color:#7b2ff7;border-radius:8px;">
                <i class="fas fa-times me-1"></i> Clear
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color:#9b59b6;font-size:0.8rem;">{{ $log->id }}</td>
                    <td><strong>{{ $log->user_name ?? '—' }}</strong></td>
                    <td>
                        <span class="role-badge role-{{ $log->user_role ?? 'user' }}">
                            {{ ucfirst($log->user_role ?? '—') }}
                        </span>
                    </td>
                    <td>
                        <span class="action-badge action-{{ $log->action }}">
                            {{ ucwords(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td style="font-size:0.85rem;color:#374151;">{{ $log->description }}</td>
                    <td style="font-size:0.82rem;color:#374151;">
                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                        <div style="font-size:0.75rem;color:#9b59b6;">{{ $log->created_at->format('g:i A') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No activity logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($logs->hasPages())
<div class="d-flex justify-content-between align-items-center mt-4" style="font-size:0.85rem;">
    <span style="color:#9b59b6;">
        Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} records
    </span>
    <div class="d-flex gap-1">
        @if($logs->onFirstPage())
            <span style="background:#f5f0ff;color:#d1d5db;padding:6px 14px;border-radius:8px;font-weight:600;">← Prev</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" style="background:#ede7f6;color:#4a0080;padding:6px 14px;border-radius:8px;font-weight:600;text-decoration:none;">← Prev</a>
        @endif

        @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
            @if($page == $logs->currentPage())
                <span style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:6px 12px;border-radius:8px;font-weight:700;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="background:#f5f0ff;color:#4a0080;padding:6px 12px;border-radius:8px;font-weight:600;text-decoration:none;">{{ $page }}</a>
            @endif
        @endforeach

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" style="background:#ede7f6;color:#4a0080;padding:6px 14px;border-radius:8px;font-weight:600;text-decoration:none;">Next →</a>
        @else
            <span style="background:#f5f0ff;color:#d1d5db;padding:6px 14px;border-radius:8px;font-weight:600;">Next →</span>
        @endif
    </div>
</div>
@endif
@endsection