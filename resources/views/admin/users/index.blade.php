@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.1); }
    .card-header-violet { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border-radius:15px 15px 0 0 !important; padding:15px 20px; font-weight:600; }
    .form-label { font-weight:600; color:#6a0dad; font-size:0.88rem; }
    .form-control { border:1.5px solid #e9d5ff; border-radius:8px; font-size:0.88rem; }
    .form-control:focus { border-color:#7b2ff7; box-shadow:none; }
    .role-card { border:2px solid #e9d5ff; border-radius:12px; padding:16px; cursor:pointer; transition:all 0.2s; }
    .role-card:hover { border-color:#a78bfa; background:#faf5ff; }
    .role-card.selected { border-color:#7b2ff7; background:#ede7f6; }
    .role-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:white; flex-shrink:0; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="fas fa-user-edit me-2"></i>
        {{ isset($user) ? 'Edit Account' : 'Add New Account' }}
    </h2>
    <a href="{{ route('admin.users.index') }}" class="btn" style="background:#ede7f6;color:#4a0080;border-radius:8px;font-weight:600;">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3" style="border-radius:10px;">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
    <div class="card-header-violet">
        <i class="fas fa-user-circle me-2"></i>
        {{ isset($user) ? 'Edit: ' . $user->name : 'Account Information' }}
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}"
              method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name ?? '') }}" required placeholder="Enter full name">
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email ?? '') }}" required placeholder="Enter email">
            </div>

            <div class="mb-4">
                <label class="form-label">Role *</label>
                <div class="row g-2">
                    @foreach(['admin' => ['icon' => 'fa-shield-halved', 'color' => 'linear-gradient(135deg,#991b1b,#ef4444)', 'desc' => 'Full system access'],
                              'staff' => ['icon' => 'fa-user-tie',      'color' => 'linear-gradient(135deg,#1d4ed8,#60a5fa)', 'desc' => 'Manage reservations']] as $role => $info)
                    @php $selected = old('role', $user->role ?? '') === $role; @endphp
                    <div class="col-6">
                        <label class="role-card {{ $selected ? 'selected' : '' }} w-100 d-flex gap-2 align-items-center"
                               onclick="document.querySelectorAll('.role-card').forEach(c=>c.classList.remove('selected'));this.classList.add('selected')">
                            <input type="radio" name="role" value="{{ $role }}" {{ $selected ? 'checked' : '' }} class="d-none">
                            <div class="role-icon" style="background:{{ $info['color'] }}">
                                <i class="fas {{ $info['icon'] }}"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#2d0057;font-size:0.85rem;">{{ ucfirst($role) }}</div>
                                <div style="font-size:0.72rem;color:#9b59b6;">{{ $info['desc'] }}</div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr style="border-color:#f3e8ff;">
            <p style="font-weight:700;color:#6a0dad;font-size:0.88rem;">
                {{ isset($user) ? 'Change Password (leave blank to keep current)' : 'Password *' }}
            </p>

            <div class="mb-3">
                <label class="form-label">Password {{ isset($user) ? '' : '*' }}</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" {{ isset($user) ? '' : 'required' }}>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirm Password {{ isset($user) ? '' : '*' }}</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat password" {{ isset($user) ? '' : 'required' }}>
            </div>

            <button type="submit" class="btn text-white w-100"
                    style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:700;padding:12px;">
                <i class="fas fa-save me-2"></i>
                {{ isset($user) ? 'Update Account' : 'Create Account' }}
            </button>
        </form>
    </div>
</div>
</div>
</div>
@endsection