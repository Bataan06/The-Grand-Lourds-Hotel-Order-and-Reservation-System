@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.1); margin-bottom:20px; }
    .card-header-violet { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border-radius:15px 15px 0 0 !important; padding:15px 20px; font-weight:600; }
    .form-label { font-weight:600; color:#6a0dad; font-size:0.88rem; }
    .form-control, .form-select { border:1.5px solid #e9d5ff; border-radius:8px; font-size:0.88rem; }
    .form-control:focus, .form-select:focus { border-color:#7b2ff7; box-shadow:none; }

    /* Package builder */
    .pkg-block { background:#faf5ff; border:1.5px solid #e9d5ff; border-radius:14px; padding:20px; margin-bottom:16px; position:relative; }
    .pkg-block-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .pkg-title { font-weight:800; color:#4a0080; font-size:0.95rem; }
    .btn-remove-pkg { background:#fee2e2; color:#dc2626; border:none; border-radius:8px; padding:5px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .price-tab { border:2px solid #e9d5ff; border-radius:8px; padding:7px 14px; cursor:pointer; font-weight:700; color:#4a0080; background:#fff; font-size:0.83rem; transition:all 0.2s; }
    .price-tab.active { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border-color:#4a0080; }
    .price-panel { display:none; }
    .price-panel.active { display:block; }
    .set-block { background:white; border:1.5px solid #e9d5ff; border-radius:10px; padding:14px; margin-bottom:10px; }
    .set-label { background:#4a0080; color:white; padding:3px 10px; border-radius:6px; font-size:0.78rem; font-weight:700; display:inline-block; margin-bottom:10px; }
    .item-row { display:flex; gap:8px; margin-bottom:7px; }
    .item-row input { flex:1; border:1.5px solid #e9d5ff; border-radius:7px; padding:6px 10px; font-size:0.82rem; outline:none; }
    .item-row input:focus { border-color:#7b2ff7; }
    .btn-add-item { background:#ede7f6; color:#4a0080; border:none; border-radius:7px; padding:5px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .btn-add-item:hover { background:#d1c4e9; }
    .btn-remove-row { background:#fee2e2; color:#dc2626; border:none; border-radius:6px; width:26px; height:26px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.75rem; }
    .amenity-row { display:flex; gap:8px; margin-bottom:7px; }
    .amenity-row input { flex:1; border:1.5px solid #e9d5ff; border-radius:7px; padding:6px 10px; font-size:0.82rem; outline:none; }
    .amenity-row input:focus { border-color:#7b2ff7; }
    .section-lbl { font-weight:700; color:#4a0080; font-size:0.83rem; border-left:3px solid #7b2ff7; padding-left:8px; margin:14px 0 10px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="fas fa-calendar-plus me-2"></i>
        {{ isset($event) ? 'Edit Event' : 'Add New Event' }}
    </h2>
    <a href="{{ route('admin.events.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3" style="border-radius:10px;">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}"
      method="POST" id="eventForm">
    @csrf
    @if(isset($event)) @method('PUT') @endif

    {{-- Event Info --}}
    <div class="card">
        <div class="card-header-violet"><i class="fas fa-calendar-alt me-2"></i> Event Information</div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Event Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $event->name ?? '') }}"
                           placeholder="e.g. Wedding, Birthday / Christening, Conference" required>
                </div>
                <div class="col-md-4 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="is_active" id="isActive"
                               {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActive" style="color:#4a0080;">Active (visible)</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Brief description...">{{ old('description', $event->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    @if(!isset($event))
    {{-- Packages (only on create — edit packages separately) --}}
    <div class="card">
        <div class="card-header-violet">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-box-open me-2"></i> Packages & Menus <span style="opacity:0.75;font-size:0.8rem;">(optional — can add later)</span></span>
                <button type="button" onclick="addPackage()" class="btn btn-sm text-white" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.4);border-radius:8px;">
                    <i class="fas fa-plus me-1"></i> Add Package
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <div id="packagesContainer">
                {{-- Packages added here --}}
            </div>
            <p id="noPackagesMsg" style="color:#9b59b6;font-size:0.85rem;text-align:center;padding:20px 0;">
                <i class="fas fa-box-open me-2"></i> No packages yet. Click "Add Package" to start.
            </p>
        </div>
    </div>
    @endif

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('admin.events.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;padding:10px 24px;">Cancel</a>
        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;padding:10px 28px;">
            <i class="fas fa-save me-2"></i> {{ isset($event) ? 'Update Event' : 'Create Event' }}
        </button>
    </div>
</form>

@php
$venues = \App\Models\Venue::where('is_active', true)->get();
$defaultInclusions = [
    'Standard Physical Arrangement',
    'Standard Centerpiece for all round tables',
    'Free use of Venue for 4 hours',
    'Free Food Tasting for 2 persons',
    'LCD Projector with roll up screen',
    'Sound System with wireless microphone',
    'Led Lights & Red or Green Carpet',
    'Well trained & Uniformed Banquet Staff',
];
@endphp

<script>
let pkgCount = 0;

const VENUES = @json($venues);
const DEFAULT_INCLUSIONS = @json($defaultInclusions);
const PRICES = [570, 630, 730];
const SETS = ['A', 'B', 'C', 'D'];

function addPackage() {
    pkgCount++;
    const i = pkgCount;
    document.getElementById('noPackagesMsg').style.display = 'none';

    const venueOptions = VENUES.map(v => `<option value="${v.id}">${v.name}</option>`).join('');
    const inclusionRows = DEFAULT_INCLUSIONS.map(inc =>
        `<div class="amenity-row">
            <input type="text" name="packages[${i}][amenities][]" value="${inc}" placeholder="Inclusion item">
            <button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
        </div>`
    ).join('');

    const pricePanels = PRICES.map((price, pi) => {
        const setBlocks = SETS.map(set => `
            <div class="set-block">
                <span class="set-label">Set ${set}</span>
                <div id="items-${i}-${price}-${set}">
                    <div class="item-row">
                        <input type="text" name="packages[${i}][sets][${price}][${set}][]" placeholder="e.g. Honey Buttered Chicken">
                        <button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <button type="button" class="btn-add-item" onclick="addItem('items-${i}-${price}-${set}', 'packages[${i}][sets][${price}][${set}][]')">
                    <i class="fas fa-plus me-1"></i> Add Item
                </button>
            </div>`).join('');

        return `<div class="price-panel ${pi === 0 ? 'active' : ''}" id="pp-${i}-${price}">
            <div style="background:linear-gradient(135deg,#4a0080,#7b2ff7);color:white;padding:8px 14px;border-radius:8px;font-weight:700;font-size:0.85rem;margin-bottom:12px;">
                ₱${price.toLocaleString()}/pax — Food Sets
            </div>
            ${setBlocks}
        </div>`;
    }).join('');

    const priceTabs = PRICES.map((price, pi) => `
        <button type="button" class="price-tab ${pi === 0 ? 'active' : ''}" onclick="switchTab(${i}, ${price}, this)">
            ₱${price.toLocaleString()}/pax
        </button>`).join('');

    const html = `
    <div class="pkg-block" id="pkg-${i}">
        <div class="pkg-block-header">
            <span class="pkg-title"><i class="fas fa-box-open me-2" style="color:#7b2ff7;"></i> Package ${i}</span>
            <button type="button" class="btn-remove-pkg" onclick="removePackage(${i})"><i class="fas fa-trash me-1"></i> Remove</button>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Venue *</label>
                <select name="packages[${i}][venue_id]" class="form-select" required>
                    <option value="">-- Select Venue --</option>
                    ${venueOptions}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Min Pax *</label>
                <input type="number" name="packages[${i}][pax_min]" class="form-control" min="1" placeholder="e.g. 50" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Pax *</label>
                <input type="number" name="packages[${i}][pax_max]" class="form-control" min="1" placeholder="e.g. 90" required>
            </div>
        </div>

        <div class="section-lbl">Package Inclusions</div>
        <div id="amenities-${i}">${inclusionRows}</div>
        <button type="button" class="btn-add-item mb-3" onclick="addAmenity(${i})"><i class="fas fa-plus me-1"></i> Add Inclusion</button>

        <div class="section-lbl">Price Tiers & Food Menu Sets</div>
        <div class="d-flex gap-2 mb-3 flex-wrap" id="tabs-${i}">
            ${priceTabs}
        </div>
        ${pricePanels}
    </div>`;

    document.getElementById('packagesContainer').insertAdjacentHTML('beforeend', html);
}

function switchTab(i, price, btn) {
    document.querySelectorAll(`#pkg-${i} .price-tab`).forEach(t => t.classList.remove('active'));
    document.querySelectorAll(`#pkg-${i} .price-panel`).forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(`pp-${i}-${price}`).classList.add('active');
}

function removePackage(i) {
    document.getElementById('pkg-' + i).remove();
    if (!document.querySelector('.pkg-block')) {
        document.getElementById('noPackagesMsg').style.display = 'block';
    }
}

function addItem(containerId, inputName) {
    const row = document.createElement('div');
    row.className = 'item-row';
    row.innerHTML = `<input type="text" name="${inputName}" placeholder="Add menu item"><button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>`;
    document.getElementById(containerId).appendChild(row);
}

function addAmenity(i) {
    const row = document.createElement('div');
    row.className = 'amenity-row';
    row.innerHTML = `<input type="text" name="packages[${i}][amenities][]" placeholder="Add inclusion"><button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>`;
    document.getElementById('amenities-' + i).appendChild(row);
}

function removeRow(btn) {
    btn.closest('.item-row, .amenity-row').remove();
}
</script>
@endsection