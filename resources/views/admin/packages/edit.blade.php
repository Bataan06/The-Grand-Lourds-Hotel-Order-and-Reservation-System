@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .form-card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.08); background:white; padding:32px; }
    .form-label { font-weight:600; color:#4a0080; font-size:0.85rem; }
    .form-control, .form-select { border:1.5px solid #e9d5ff; border-radius:8px; font-size:0.85rem; padding:9px 12px; }
    .form-control:focus, .form-select:focus { border-color:#7b2ff7; box-shadow:0 0 0 3px rgba(123,47,247,0.1); }
    .btn-save { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border:none; border-radius:8px; padding:10px 28px; font-weight:600; font-size:0.9rem; }
    .btn-save:hover { opacity:0.9; color:white; }
    .btn-back { background:#ede7f6; color:#4a0080; border:none; border-radius:8px; padding:10px 20px; font-weight:600; font-size:0.9rem; text-decoration:none; display:inline-block; }
    .section-label { font-size:0.75rem; letter-spacing:1.5px; text-transform:uppercase; color:#a78bfa; font-weight:700; margin-bottom:12px; margin-top:24px; }
    .inc-input-row { display:flex; gap:8px; margin-bottom:8px; }
    .inc-input-row input { flex:1; }
    .btn-add-inc { background:#f5f0ff; color:#7c3aed; border:1px solid #e9d5ff; border-radius:8px; padding:8px 16px; font-size:0.82rem; font-weight:600; cursor:pointer; white-space:nowrap; }
    .btn-remove-inc { background:#fee2e2; color:#dc2626; border:none; border-radius:8px; padding:8px 12px; font-size:0.82rem; cursor:pointer; }
    .tier-row { display:flex; gap:8px; margin-bottom:8px; align-items:center; }
    .tier-row input { flex:1; }
</style>

{{-- Breadcrumb --}}
<nav style="font-size:0.83rem;color:#9b59b6;margin-bottom:12px;">
    <a href="{{ route('admin.events.index') }}" style="color:#7b2ff7;text-decoration:none;">Events</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.events.packages', $eventId) }}" style="color:#7b2ff7;text-decoration:none;">Packages</a>
    <span class="mx-2">/</span>
    <span style="color:#4a0080;font-weight:600;">Edit Package</span>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-pen me-2"></i> Edit Package</h2>
    <a href="{{ route('admin.events.packages', $eventId) }}" class="btn-back">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3" style="border-radius:10px;">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.events.packages.update', [$eventId, $package->id]) }}" method="POST">
@csrf @method('PUT')
<div class="form-card">

    {{-- Venue --}}
    <div class="section-label">Venue & Capacity</div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Venue *</label>
            <select name="venue_id" class="form-select" required>
                <option value="">— Select Venue —</option>
                @foreach($venues as $venue)
                <option value="{{ $venue->id }}"
                    {{ old('venue_id', $package->venue_id) == $venue->id ? 'selected' : '' }}>
                    {{ $venue->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pax Range *</label>
            <input type="text" name="pax_range" class="form-control" placeholder="e.g. 100-200"
                   value="{{ old('pax_range', $package->pax_range) }}" required>
        </div>
    </div>

    {{-- Price Tiers --}}
    <div class="section-label">Price Tiers (per pax)</div>
    <div id="tiersWrap">
        @php $tiers = $package->price_tiers ?? []; @endphp
        @if(count($tiers) > 0)
            @foreach($tiers as $pax => $price)
            <div class="tier-row">
                <input type="number" name="tier_pax[]" class="form-control" placeholder="Pax" value="{{ $pax }}" min="1">
                <input type="number" name="tier_price[]" class="form-control" placeholder="Price per pax" value="{{ $price }}" min="0">
                <button type="button" class="btn-remove-inc" onclick="removeTier(this)"><i class="fas fa-times"></i></button>
            </div>
            @endforeach
        @else
            <div class="tier-row">
                <input type="number" name="tier_pax[]" class="form-control" placeholder="Pax (e.g. 100)" min="1">
                <input type="number" name="tier_price[]" class="form-control" placeholder="Price per pax (e.g. 730)" min="0">
                <button type="button" class="btn-remove-inc" onclick="removeTier(this)"><i class="fas fa-times"></i></button>
            </div>
        @endif
    </div>
    <button type="button" class="btn-add-inc mt-1" onclick="addTier()">
        <i class="fas fa-plus me-1"></i> Add Price Tier
    </button>

    {{-- Inclusions --}}
    <div class="section-label">Inclusions / Amenities</div>
    <div id="inclusionsWrap">
        @php $inclusions = $package->amenities ?? []; @endphp
        @if(count($inclusions) > 0)
            @foreach($inclusions as $inc)
            <div class="inc-input-row">
                <input type="text" name="inclusions[]" class="form-control" value="{{ $inc }}">
                <button type="button" class="btn-remove-inc" onclick="removeInc(this)"><i class="fas fa-times"></i></button>
            </div>
            @endforeach
        @else
            <div class="inc-input-row">
                <input type="text" name="inclusions[]" class="form-control" placeholder="e.g. Free flowing drinks">
                <button type="button" class="btn-remove-inc" onclick="removeInc(this)"><i class="fas fa-times"></i></button>
            </div>
        @endif
    </div>
    <button type="button" class="btn-add-inc mt-1" onclick="addInclusion()">
        <i class="fas fa-plus me-1"></i> Add Inclusion
    </button>

    {{-- Status --}}
    <div class="section-label">Status</div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
               {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="isActive" style="color:#4a0080;font-weight:600;">Active (visible to guests)</label>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-save"><i class="fas fa-save me-1"></i> Update Package</button>
        <a href="{{ route('admin.events.packages', $eventId) }}" class="btn-back">Cancel</a>
    </div>
</div>
</form>

<script>
function addInclusion() {
    const wrap = document.getElementById('inclusionsWrap');
    const row = document.createElement('div');
    row.className = 'inc-input-row';
    row.innerHTML = `<input type="text" name="inclusions[]" class="form-control" placeholder="e.g. Free flowing drinks">
                     <button type="button" class="btn-remove-inc" onclick="removeInc(this)"><i class="fas fa-times"></i></button>`;
    wrap.appendChild(row);
}
function removeInc(btn) {
    const rows = document.querySelectorAll('#inclusionsWrap .inc-input-row');
    if (rows.length > 1) btn.closest('.inc-input-row').remove();
}
function addTier() {
    const wrap = document.getElementById('tiersWrap');
    const row = document.createElement('div');
    row.className = 'tier-row';
    row.innerHTML = `<input type="number" name="tier_pax[]" class="form-control" placeholder="Pax (e.g. 150)" min="1">
                     <input type="number" name="tier_price[]" class="form-control" placeholder="Price per pax (e.g. 830)" min="0">
                     <button type="button" class="btn-remove-inc" onclick="removeTier(this)"><i class="fas fa-times"></i></button>`;
    wrap.appendChild(row);
}
function removeTier(btn) {
    const rows = document.querySelectorAll('#tiersWrap .tier-row');
    if (rows.length > 1) btn.closest('.tier-row').remove();
}
</script>
@endsection
