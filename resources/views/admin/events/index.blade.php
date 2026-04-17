@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .table-card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.08); overflow:hidden; background:white; }
    .table thead th { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border:none; padding:14px 15px; font-size:0.83rem; font-weight:600; }
    .table tbody td { padding:13px 15px; vertical-align:middle; font-size:0.85rem; border-color:#f3e8ff; }
    .table tbody tr:hover { background:#faf5ff; }
    .badge-active   { background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; display:inline-block; }
    .badge-inactive { background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; display:inline-block; }
    .btn-edit     { background:#ede7f6; color:#4a0080; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; text-decoration:none; display:inline-block; }
    .btn-toggle   { background:#f5f0ff; color:#7c3aed; border:1px solid #e9d5ff; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .btn-del      { background:#fee2e2; color:#dc2626; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .event-icon   { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-calendar-alt me-2"></i> Manage Events</h2>
    <a href="{{ route('admin.events.create') }}" class="btn text-white"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:10px;font-weight:600;font-size:0.85rem;padding:9px 18px;">
        <i class="fas fa-plus me-1"></i> Add Event
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Event</th>
                <th>Description</th>
                <th>Packages</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td style="color:#9b59b6;font-weight:600;">{{ $loop->iteration }}</td>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="event-icon" style="background:{{ $event->name==='Wedding' ? 'linear-gradient(135deg,#880e4f,#e91e63)' : ($event->name==='Conference' ? 'linear-gradient(135deg,#1a237e,#3f51b5)' : 'linear-gradient(135deg,#4a0080,#7b2ff7)') }};">
                            {{ $event->name==='Wedding' ? '💒' : ($event->name==='Conference' ? '🎓' : '🎂') }}
                        </div>
                        <div style="font-weight:700;color:#2d0057;">{{ $event->name }}</div>
                    </div>
                </td>
                <td style="color:#6b7280;font-size:0.82rem;">{{ Str::limit($event->description, 60) ?? '—' }}</td>

                <td>
                    @if($event->is_active)
                        <span class="badge-active">Active</span>
                    @else
                        <span class="badge-inactive">Hidden</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">

                        <a href="{{ route('admin.events.edit', $event) }}" class="btn-edit">
                            <i class="fas fa-pen me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle">
                                <i class="fas fa-eye{{ $event->is_active ? '-slash' : '' }} me-1"></i>
                                {{ $event->is_active ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete event {{ $event->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-calendar fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                    No events found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection