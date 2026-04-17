@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.1); margin-bottom:20px; }
    .card-header-violet { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border-radius:15px 15px 0 0 !important; padding:15px 20px; font-weight:600; }
    .form-label { font-weight:600; color:#6a0dad; font-size:0.88rem; }
    .form-control, .form-select { border:1.5px solid #e9d5ff; border-radius:8px; font-size:0.88rem; }
    .form-control:focus, .form-select:focus { border-color:#7b2ff7; box-shadow:none; }

    /* Package editor */
    .pkg-editor { background:#faf5ff; border:1.5px solid #e9d5ff; border-radius:14px; padding:20px; margin-bottom:16px; }
    .pkg-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #e9d5ff; }
    .pkg-title { font-weight:800; color:#4a0080; font-size:0.95rem; display:flex; align-items:center; gap:8px; }
    .venue-badge { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; padding:3px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; }
    .pax-badge  { background:#ede7f6; color:#4a0080; padding:3px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; }

    .price-tabs { display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap; }
    .price-tab  { border:2px solid #e9d5ff; border-radius:8px; padding:6px 14px; cursor:pointer; font-weight:700; color:#4a0080; background:#fff; font-size:0.82rem; transition:all 0.2s; }
    .price-tab.active { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border-color:#4a0080; }
    .price-panel { display:none; }
    .price-panel.active { display:block; }

    .set-block  { background:white; border:1.5px solid #e9d5ff; border-radius:10px; padding:14px; margin-bottom:10px; }
    .set-label  { background:#4a0080; color:white; padding:3px 10px; border-radius:6px; font-size:0.78rem; font-weight:700; display:inline-block; margin-bottom:10px; }
    .item-row   { display:flex; gap:8px; margin-bottom:7px; }
    .item-row input { flex:1; border:1.5px solid #e9d5ff; border-radius:7px; padding:6px 10px; font-size:0.82rem; outline:none; }
    .item-row input:focus { border-color:#7b2ff7; }
    .amenity-row { display:flex; gap:8px; margin-bottom:7px; }
    .amenity-row input { flex:1; border:1.5px solid #e9d5ff; border-radius:7px; padding:6px 10px; font-size:0.82rem; outline:none; }
    .amenity-row input:focus { border-color:#7b2ff7; }
    .btn-add-item { background:#ede7f6; color:#4a0080; border:none; border-radius:7px; padding:5px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .btn-add-item:hover { background:#d1c4e9; }
    .btn-rm { background:#fee2e2; color:#dc2626; border:none; border-radius:6px; width:26px; height:26px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.75rem; }
    .section-lbl { font-weight:700; color:#4a0080; font-size:0.82rem; border-left:3px solid #7b2ff7; padding-left:8px; margin:12px 0 8px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-edit me-2"></i> Edit Event — {{ $event->name }}</h2>
    <a href="{{ route('admin.events.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- EVENT INFO --}}
<div class="card">
    <div class="card-header-violet"><i class="fas fa-calendar-alt me-2"></i> Event Information</div>
    <div class="card-body p-4">
        <form action="{{ route('admin.events.update', $event) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Event Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="is_active" id="isActive" {{ $event->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActive" style="color:#4a0080;">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ $event->description }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;padding:9px 24px;">
                    <i class="fas fa-save me-2"></i> Update Event Info
                </button>
            </div>
        </form>
    </div>
</div>

{{-- PACKAGES --}}
@php $packages = \App\Models\Package::with('venue')->where('event_id', $event->id)->orderBy('pax_min')->get(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 style="color:#4a0080;font-weight:800;margin:0;"><i class="fas fa-box-open me-2"></i> Packages ({{ $packages->count() }})</h5>
    <a href="{{ route('admin.events.packages.create', $event->id) }}" class="btn text-white btn-sm"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;">
        <i class="fas fa-plus me-1"></i> Add Package
    </a>
</div>

@forelse($packages as $pkg)
@php
    $tiers = $pkg->price_tiers ?? [];
    $activePrices = array_keys($tiers);
    $prices = [570, 630, 730];
    $sets = ['A', 'B', 'C', 'D'];
@endphp

<div class="pkg-editor">
    <div class="pkg-header">
        <div class="pkg-title">
            <i class="fas fa-box-open" style="color:#7b2ff7;"></i>
            Package {{ $loop->iteration }}
            <span class="venue-badge">{{ $pkg->venue->name }}</span>
            <span class="pax-badge">{{ $pkg->pax_range }} pax</span>
            @if(!$pkg->is_active) <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;">Hidden</span> @endif
        </div>
        <div class="d-flex gap-1">
            <a href="{{ route('admin.events.packages.edit', [$event->id, $pkg->id]) }}"
               class="btn btn-sm" style="background:#ede7f6;color:#4a0080;border-radius:7px;font-weight:600;font-size:0.78rem;">
                <i class="fas fa-expand me-1"></i> Full Edit
            </a>
            <form action="{{ route('admin.events.packages.destroy', [$event->id, $pkg->id]) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Delete this package?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;border-radius:7px;font-weight:600;font-size:0.78rem;">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.events.packages.update', [$event->id, $pkg->id]) }}" method="POST">
        @csrf @method('PUT')
        <input type="hidden" name="venue_id" value="{{ $pkg->venue_id }}">
        <input type="hidden" name="pax_min" value="{{ $pkg->pax_min }}">
        <input type="hidden" name="pax_max" value="{{ $pkg->pax_max }}">
        <input type="hidden" name="is_active" value="{{ $pkg->is_active ? 1 : 0 }}">

        {{-- Inclusions --}}
        <div class="section-lbl">Package Inclusions</div>
        <div id="amenities-{{ $pkg->id }}">
            @foreach($pkg->amenities ?? [] as $amenity)
            <div class="amenity-row">
                <input type="text" name="amenities[]" value="{{ $amenity }}">
                <button type="button" class="btn-rm" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
            </div>
            @endforeach
        </div>
        <button type="button" class="btn-add-item mb-3"
                onclick="addAmenityTo('amenities-{{ $pkg->id }}')">
            <i class="fas fa-plus me-1"></i> Add Inclusion
        </button>

        {{-- Food Sets --}}
        <div class="section-lbl">Food Menu Sets</div>
        <div class="price-tabs">
            @foreach($prices as $pi => $price)
            <button type="button"
                    class="price-tab {{ $pi === 0 ? 'active' : '' }}"
                    onclick="switchPkgTab('{{ $pkg->id }}', {{ $price }}, this)">
                ₱{{ number_format($price) }}/pax
                @if(!in_array((string)$price, $activePrices)) <span style="font-size:0.65rem;opacity:0.6;">(empty)</span> @endif
            </button>
            @endforeach
        </div>

        @foreach($prices as $pi => $price)
        <div class="price-panel {{ $pi === 0 ? 'active' : '' }}" id="pkgtab-{{ $pkg->id }}-{{ $price }}">
            <div style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:7px 14px;border-radius:8px;font-weight:700;font-size:0.83rem;margin-bottom:10px;">
                ₱{{ number_format($price) }}/pax — Food Sets
            </div>
            @foreach($sets as $set)
            @php $items = $tiers[$price][$set]['items'] ?? ['']; @endphp
            <div class="set-block">
                <span class="set-label">Set {{ $set }}</span>
                <div id="sitems-{{ $pkg->id }}-{{ $price }}-{{ $set }}">
                    @foreach($items as $item)
                    <div class="item-row">
                        <input type="text" name="sets[{{ $price }}][{{ $set }}][]"
                               value="{{ $item }}" placeholder="e.g. Honey Buttered Chicken">
                        <button type="button" class="btn-rm" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-item"
                        onclick="addFoodItem('sitems-{{ $pkg->id }}-{{ $price }}-{{ $set }}', 'sets[{{ $price }}][{{ $set }}][]')">
                    <i class="fas fa-plus me-1"></i> Add Item
                </button>
            </div>
            @endforeach
        </div>
        @endforeach

        <div class="mt-3">
            <button type="submit" class="btn text-white btn-sm"
                    style="background:linear-gradient(135deg,#059669,#10b981);border-radius:8px;font-weight:700;padding:8px 20px;">
                <i class="fas fa-save me-2"></i> Save Package Changes
            </button>
        </div>
    </form>
</div>
@empty
<div class="text-center py-4" style="color:#ce93d8;">
    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
    No packages yet.
    <a href="{{ route('admin.events.packages.create', $event->id) }}" style="color:#7b2ff7;">Add one!</a>
</div>
@endforelse

<script>
function switchPkgTab(pkgId, price, btn) {
    const pkg = btn.closest('.pkg-editor');
    pkg.querySelectorAll('.price-tab').forEach(t => t.classList.remove('active'));
    pkg.querySelectorAll('.price-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('pkgtab-' + pkgId + '-' + price).classList.add('active');
}
function addFoodItem(containerId, name) {
    const row = document.createElement('div');
    row.className = 'item-row';
    row.innerHTML = `<input type="text" name="${name}" placeholder="Add food item">
        <button type="button" class="btn-rm" onclick="removeRow(this)"><i class="fas fa-times"></i></button>`;
    document.getElementById(containerId).appendChild(row);
}
function addAmenityTo(containerId) {
    const row = document.createElement('div');
    row.className = 'amenity-row';
    row.innerHTML = `<input type="text" name="amenities[]" placeholder="Add inclusion">
        <button type="button" class="btn-rm" onclick="removeRow(this)"><i class="fas fa-times"></i></button>`;
    document.getElementById(containerId).appendChild(row);
}
function removeRow(btn) {
    btn.closest('.item-row, .amenity-row').remove();
}
</script>
@endsection