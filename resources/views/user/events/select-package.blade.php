@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #2d0057; font-weight: 800; }
    .breadcrumb-item a { color: #7b2ff7; text-decoration: none; }
    .pkg-card { border: 2px solid #e9d5ff; border-radius: 18px; background: #fff; margin-bottom: 28px; overflow: hidden; transition: all 0.3s; }
    .pkg-card:hover { border-color: #7b2ff7; box-shadow: 0 8px 30px rgba(123,47,247,0.12); }
    .pkg-topbar { background: linear-gradient(135deg, #2d0057, #4a0080); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .pax-badge { background: rgba(255,255,255,0.2); color: white; padding: 7px 18px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.3); }
    .venue-badge { background: rgba(255,255,255,0.15); color: #e9d5ff; padding: 5px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 600; }
    .btn-book-pkg { background: white; color: #4a0080; border: none; border-radius: 10px; padding: 10px 22px; font-weight: 700; transition: all 0.3s; text-decoration: none; display: inline-block; font-size: 0.88rem; }
    .btn-book-pkg:hover { background: #ede7f6; color: #4a0080; }
    .pkg-body { display: grid; grid-template-columns: 180px 1px 1fr 1px 1.6fr; }
    .pkg-divider { background: #f3e5f5; }
    .pkg-col { padding: 20px 22px; }
    .col-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9b59b6; margin-bottom: 14px; }
    .price-btn { border: 2px solid #e1bee7; border-radius: 12px; padding: 14px 16px; cursor: pointer; transition: all 0.2s; text-align: center; background: #faf5ff; margin-bottom: 10px; width: 100%; display: block; }
    .price-btn:hover { border-color: #7b2ff7; background: #f3e5f5; }
    .price-btn.active { border-color: #4a0080; background: linear-gradient(135deg, #4a0080, #7b2ff7); }
    .price-btn .amt { font-size: 1.4rem; font-weight: 800; color: #4a0080; display: block; }
    .price-btn.active .amt { color: white; }
    .price-btn .lbl { font-size: 0.72rem; color: #9b59b6; }
    .price-btn.active .lbl { color: rgba(255,255,255,0.8); }
    .amenity-item { background: #f9f5ff; border-radius: 7px; padding: 5px 10px; margin-bottom: 5px; color: #4a0080; font-size: 0.79rem; display: flex; align-items: flex-start; gap: 7px; }
    .price-panel { display: none; }
    .price-panel.active { display: block; }
    .set-tabs { display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap; }
    .set-tab { border: 2px solid #e1bee7; border-radius: 8px; padding: 6px 16px; cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #4a0080; background: #faf5ff; transition: all 0.2s; }
    .set-tab.active { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-color: #4a0080; }
    .set-panel { display: none; }
    .set-panel.active { display: block; }
    .menu-item { padding: 5px 0; font-size: 0.83rem; color: #555; border-bottom: 1px solid #f5f0ff; }
    .menu-item:last-child { border: none; }
    .menu-item.choice { color: #9b59b6; font-style: italic; font-size: 0.78rem; }
</style>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.events.index') }}">Events</a></li>
        <li class="breadcrumb-item active">{{ $event->name }}</li>
    </ol>
</nav>

<h2 class="page-title mb-1"><i class="fas fa-box-open me-2"></i> Select Package</h2>
<p class="text-muted mb-4">Choose a package for your <strong>{{ $event->name }}</strong></p>

@forelse($packages as $pi => $package)
@php $tiers = $package->price_tiers; $prices = array_keys($tiers); @endphp
<div class="pkg-card">
    <div class="pkg-topbar">
        <div class="d-flex align-items-center gap-3">
            <span class="pax-badge"><i class="fas fa-users me-1"></i> {{ $package->pax_range }} PAX</span>
            <span class="venue-badge"><i class="fas fa-map-marker-alt me-1"></i> {{ $package->venue->name }}</span>
        </div>
        <a href="{{ route('user.events.create', $package->id) }}" class="btn-book-pkg">
            <i class="fas fa-calendar-plus me-1"></i> Book This Package
        </a>
    </div>

    <div class="pkg-body">
        {{-- Col 1: Price Buttons --}}
        <div class="pkg-col">
            <div class="col-label">💰 Price/Pax</div>
            @foreach($prices as $si => $price)
            <button class="price-btn {{ $si===0 ? 'active' : '' }}"
                onclick="selectPriceTier('p{{ $pi }}', '{{ $price }}', this)">
                <span class="amt">₱{{ number_format($price) }}</span>
                <span class="lbl">/pax</span>
            </button>
            @endforeach
        </div>

        <div class="pkg-divider"></div>

        {{-- Col 2: Inclusions --}}
        <div class="pkg-col">
            <div class="col-label">✅ Package Inclusions</div>
            @foreach($package->amenities as $amenity)
                <div class="amenity-item">
                    <i class="fas fa-check" style="color:#7b2ff7;font-size:0.7rem;margin-top:3px;flex-shrink:0;"></i>
                    {{ $amenity }}
                </div>
            @endforeach
        </div>

        <div class="pkg-divider"></div>

        {{-- Col 3: Menu Sets per price --}}
        <div class="pkg-col">
            <div class="col-label">🍽️ Food Menu Sets</div>
            @foreach($prices as $si => $price)
            @php $sets = $tiers[$price]; @endphp
            <div class="price-panel {{ $si===0 ? 'active' : '' }}" id="p{{ $pi }}-tier-{{ $price }}">
                <div class="set-tabs">
                    @foreach($sets as $setKey => $setData)
                    <button class="set-tab {{ $loop->first ? 'active' : '' }}"
                        onclick="selectSet('p{{ $pi }}-tier-{{ $price }}', '{{ $setKey }}', this)">
                        Set {{ $setKey }}
                    </button>
                    @endforeach
                </div>
                @foreach($sets as $setKey => $setData)
                <div class="set-panel {{ $loop->first ? 'active' : '' }}" id="p{{ $pi }}-tier-{{ $price }}-set-{{ $setKey }}">
                    @foreach($setData['items'] as $item)
                        <div class="menu-item {{ str_contains($item,'Choice of') ? 'choice' : '' }}">
                            {{ str_contains($item,'Choice of') ? '✦ '.$item : $item }}
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="fas fa-box-open fa-3x mb-3 d-block" style="color:#ce93d8;"></i>
    No packages available.
</div>
@endforelse

<script>
function selectPriceTier(pkg, price, btn) {
    const card = btn.closest('.pkg-card');
    card.querySelectorAll('.price-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    card.querySelectorAll('.price-panel').forEach(p => p.classList.remove('active'));
    const target = document.getElementById(pkg + '-tier-' + price);
    if (target) target.classList.add('active');
}

function selectSet(prefix, set, btn) {
    const panel = document.getElementById(prefix);
    panel.querySelectorAll('.set-tab').forEach(b => b.classList.remove('active'));
    panel.querySelectorAll('.set-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    const target = document.getElementById(prefix + '-set-' + set);
    if (target) target.classList.add('active');
}
</script>
@endsection