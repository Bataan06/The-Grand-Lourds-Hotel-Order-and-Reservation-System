@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.08); }
    .card-header-violet { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; font-weight: 600; }
    .form-label { font-weight: 600; color: #6a0dad; font-size: 0.88rem; }
    .form-control, .form-select { border: 1.5px solid #e9d5ff; border-radius: 8px; font-size: 0.88rem; }
    .form-control:focus, .form-select:focus { border-color: #7b2ff7; box-shadow: none; }
    .preview-box { height: 160px; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; transition: all 0.3s; position: relative; }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
    .preview-box .no-img { font-size: 3rem; color: rgba(255,255,255,0.5); }
    .gradient-option { display: inline-block; width: 40px; height: 24px; border-radius: 6px; cursor: pointer; border: 2px solid transparent; margin: 3px; }
    .gradient-option.selected { border-color: #1f2937; transform: scale(1.1); }
    .img-upload-area { border: 2px dashed #e9d5ff; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.2s; background: #faf5ff; }
    .img-upload-area:hover { border-color: #7b2ff7; background: #f3e8ff; }
    .img-upload-area input[type=file] { display: none; }
    .img-preview-thumb { width: 100%; max-height: 140px; object-fit: cover; border-radius: 8px; margin-top: 10px; display: none; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="fas fa-tags me-2"></i>
        {{ isset($offer) ? 'Edit Offer' : 'Add Special Offer' }}
    </h2>
    <a href="{{ route('admin.offers.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-violet">
                <i class="fas fa-edit me-2"></i> Offer Details
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($offer) ? route('admin.offers.update', $offer) : route('admin.offers.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($offer)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Offer Title *</label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $offer->title ?? '') }}"
                                   placeholder="e.g. Summer Celebration Package" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Badge Label *</label>
                            <input type="text" name="badge" class="form-control"
                                   value="{{ old('badge', $offer->badge ?? '') }}"
                                   placeholder="e.g. Summer Deal" required>
                        </div>

                        {{-- Image Upload --}}
                        <div class="col-12">
                            <label class="form-label">Offer Image <span class="text-muted fw-normal">(JPG, PNG, WebP — max 2MB)</span></label>
                            <div class="img-upload-area" onclick="document.getElementById('imageInput').click()">
                                <input type="file" id="imageInput" name="image" accept="image/*" onchange="previewImage(this)">
                                <i class="fas fa-cloud-upload-alt" style="font-size:1.5rem;color:#a78bfa;"></i>
                                <div style="font-size:0.85rem;color:#9b59b6;margin-top:6px;">Click to upload image</div>
                                <div style="font-size:0.75rem;color:#d1d5db;">or drag and drop</div>
                                @if(isset($offer) && $offer->image)
                                    <img src="{{ asset('storage/' . $offer->image) }}"
                                         id="imageThumb" class="img-preview-thumb" style="display:block;">
                                @else
                                    <img id="imageThumb" class="img-preview-thumb">
                                @endif
                            </div>
                            @if(isset($offer) && $offer->image)
                                <div style="font-size:0.75rem;color:#9b59b6;margin-top:6px;">
                                    <i class="fas fa-image me-1"></i> Current image saved. Upload new to replace.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $offer->is_active ?? 1) == 1 ? 'selected' : '' }}>Active (Visible)</option>
                                <option value="0" {{ old('is_active', $offer->is_active ?? 1) == 0 ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Highlight Background Color</label>
                            <input type="text" name="highlight_bg" class="form-control"
                                   value="{{ old('highlight_bg', $offer->highlight_bg ?? '#fff3e0') }}"
                                   placeholder="#fff3e0">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Describe your special offer..." required>{{ old('description', $offer->description ?? '') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Highlight Text *</label>
                            <input type="text" name="highlight" class="form-control"
                                   value="{{ old('highlight', $offer->highlight ?? '') }}"
                                   placeholder="e.g. Free grazing table upgrade for 50+ pax" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Highlight Text Color</label>
                            <input type="text" name="highlight_color" class="form-control"
                                   value="{{ old('highlight_color', $offer->highlight_color ?? '#e65100') }}"
                                   placeholder="#e65100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Card Background Gradient <span class="text-muted fw-normal">(used when no image)</span></label>
                            <div class="mb-2">
                                @php
                                $gradients = [
                                    'linear-gradient(135deg,#ff6f00,#ff8f00)',
                                    'linear-gradient(135deg,#880e4f,#e91e63)',
                                    'linear-gradient(135deg,#1a237e,#3f51b5)',
                                    'linear-gradient(135deg,#4a0080,#7b2ff7)',
                                    'linear-gradient(135deg,#047857,#10b981)',
                                    'linear-gradient(135deg,#991b1b,#ef4444)',
                                    'linear-gradient(135deg,#1d4ed8,#60a5fa)',
                                    'linear-gradient(135deg,#92400e,#f59e0b)',
                                    'linear-gradient(135deg,#2d0057,#7c3aed)',
                                    'linear-gradient(135deg,#064e3b,#34d399)',
                                ];
                                $current = old('gradient', $offer->gradient ?? $gradients[0]);
                                @endphp
                                @foreach($gradients as $g)
                                    <span class="gradient-option {{ $current === $g ? 'selected' : '' }}"
                                          style="background:{{ $g }};"
                                          onclick="selectGradient(this, '{{ $g }}')"></span>
                                @endforeach
                            </div>
                            <input type="hidden" name="gradient" id="gradientInput" value="{{ $current }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn text-white"
                                style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;padding:10px 28px;">
                            <i class="fas fa-save me-2"></i> {{ isset($offer) ? 'Update Offer' : 'Save Offer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Preview --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header-violet"><i class="fas fa-eye me-2"></i> Live Preview</div>
            <div class="card-body p-4">
                <div class="preview-box mb-3" id="previewBox" style="background:{{ $current }};">
                    @if(isset($offer) && $offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" id="previewImg" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div class="no-img" id="previewNoImg"><i class="fas fa-image"></i></div>
                        <img id="previewImg" style="display:none;width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
                <p style="font-size:0.75rem;color:#9b59b6;text-align:center;">Preview of offer card</p>
            </div>
        </div>
    </div>
</div>

<script>
function selectGradient(el, gradient) {
    document.querySelectorAll('.gradient-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('gradientInput').value = gradient;
    document.getElementById('previewBox').style.background = gradient;
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const thumb = document.getElementById('imageThumb');
            const prev  = document.getElementById('previewImg');
            const noImg = document.getElementById('previewNoImg');
            thumb.src = e.target.result;
            thumb.style.display = 'block';
            prev.src = e.target.result;
            prev.style.display = 'block';
            if (noImg) noImg.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection