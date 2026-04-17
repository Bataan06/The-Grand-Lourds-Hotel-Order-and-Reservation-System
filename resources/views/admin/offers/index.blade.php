@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); }
    .offer-preview { height: 80px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
    .badge-active   { background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-inactive { background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .table thead th { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; padding: 14px 16px; font-size: 0.83rem; }
    .table tbody td { padding: 13px 16px; vertical-align: middle; border-color: #f3e8ff; }
    .table tbody tr:hover { background: #faf5ff; }
    .btn-edit   { background: #ede7f6; color: #4a0080; border: none; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; font-weight: 600; text-decoration: none; display:inline-block; }
    .btn-delete { background: #fee2e2; color: #dc2626; border: none; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; font-weight: 600; cursor: pointer; }
    .btn-toggle { background: #f5f0ff; color: #7c3aed; border: 1px solid #e9d5ff; border-radius: 7px; padding: 6px 12px; font-size: 0.78rem; font-weight: 600; cursor: pointer; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-tags me-2"></i> Special Offers</h2>
    <a href="{{ route('admin.offers.create') }}" class="btn text-white"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:10px;font-weight:600;font-size:0.85rem;padding:9px 18px;">
        <i class="fas fa-plus me-1"></i> Add Offer
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius:10px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Badge</th>
                    <th>Highlight</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($offers as $offer)
                <tr>
                    <td>
                        @if($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" style="width:80px;height:60px;object-fit:cover;border-radius:8px;">
                        @else
                        <div class="offer-preview" style="background:$offer->gradient }};width:80px;">
                            <i class="fas fa-tags" style="color:rgba(255,255,255,0.5);"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;color:#1f2937;">{{ $offer->title }}</div>
                        <div style="font-size:0.75rem;color:#9b59b6;">{{ Str::limit($offer->description, 60) }}</div>
                    </td>
                    <td><span style="background:#ede7f6;color:#4a0080;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">{{ $offer->badge }}</span></td>
                    <td style="font-size:0.83rem;color:#374151;">{{ Str::limit($offer->highlight, 50) }}</td>
                    <td>
                        @if($offer->is_active)
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-inactive">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="btn-edit">
                                <i class="fas fa-pen me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.offers.toggle', $offer) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-toggle">
                                    <i class="fas fa-eye{{ $offer->is_active ? '-slash' : '' }} me-1"></i>
                                    {{ $offer->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this offer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-tags fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                        No special offers yet. <a href="{{ route('admin.offers.create') }}" style="color:#7b2ff7;">Add one!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection