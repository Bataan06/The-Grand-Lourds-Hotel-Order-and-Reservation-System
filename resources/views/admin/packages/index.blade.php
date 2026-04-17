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
    .btn-edit   { background:#ede7f6; color:#4a0080; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; text-decoration:none; display:inline-block; }
    .btn-toggle { background:#f5f0ff; color:#7c3aed; border:1px solid #e9d5ff; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .btn-del    { background:#fee2e2; color:#dc2626; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .price-tag  { background:#ede7f6; color:#4a0080; padding:2px 8px; border-radius:6px; font-size:0.75rem; font-weight:700; display:inline-block; margin:2px; }
    .inc-pill   { background:#f3e5f5; color:#6a1b9a; padding:2px 8px; border-radius:6px; font-size:0.72rem; display:inline-block; margin:2px; }
</style>

{{-- Breadcrumb --}}
<nav style="font-size:0.83rem;color:#9b59b6;margin-bottom:12px;">
    <a href="{{ route('admin.events.index') }}" style="color:#7b2ff7;text-decoration:none;">Events</a>
    <span class="mx-2">/</span>
    <span style="color:#4a0080;font-weight:600;">{{ $event->name }} — Packages</span>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="fas fa-box-open me-2"></i> {{ $event->name }} Packages
    </h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <a href="{{ route('admin.events.packages.create', $event->id) }}" class="btn text-white"
           style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;">
            <i class="fas fa-plus me-1"></i> Add Package
        </a>
    </div>
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
                <th>Venue</th>
                <th>Pax Range</th>
                <th>Price Tiers</th>
                <th>Inclusions</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($packages as $pkg)
            <tr>
                <td style="color:#9b59b6;font-weight:600;">{{ $loop->iteration }}</td>
                <td style="font-weight:600;color:#2d0057;">{{ $pkg->venue->name }}</td>
                <td>
                    <span style="font-weight:700;color:#4a0080;">{{ $pkg->pax_range }}</span>
                    <span style="font-size:0.75rem;color:#9b59b6;"> pax</span>
                </td>
                <td>
                    @foreach(array_keys($pkg->price_tiers ?? []) as $price)
                        <span class="price-tag">₱{{ number_format($price) }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach(array_slice($pkg->amenities ?? [], 0, 3) as $inc)
                        <span class="inc-pill">{{ $inc }}</span>
                    @endforeach
                    @if(count($pkg->amenities ?? []) > 3)
                        <span style="font-size:0.72rem;color:#9b59b6;">+{{ count($pkg->amenities) - 3 }} more</span>
                    @endif
                </td>
                <td>
                    @if($pkg->is_active)
                        <span class="badge-active">Active</span>
                    @else
                        <span class="badge-inactive">Hidden</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.events.packages.edit', [$event->id, $pkg->id]) }}" class="btn-edit">
                            <i class="fas fa-pen me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.events.packages.toggle', [$event->id, $pkg->id]) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle">
                                <i class="fas fa-eye{{ $pkg->is_active ? '-slash' : '' }} me-1"></i>
                                {{ $pkg->is_active ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.events.packages.destroy', [$event->id, $pkg->id]) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this package?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                    No packages yet.
                    <a href="{{ route('admin.events.packages.create', $event->id) }}" style="color:#7b2ff7;">Add one!</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection