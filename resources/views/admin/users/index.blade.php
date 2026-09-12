@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .table-card { border:none; border-radius:15px; box-shadow:0 5px 20px rgba(123,47,247,0.08); overflow:hidden; background:white; }
    .table thead th { background:linear-gradient(135deg,#4a0080,#7b2ff7); color:white; border:none; padding:14px 15px; font-size:0.83rem; font-weight:600; white-space:nowrap; }
    .table tbody td { padding:13px 15px; vertical-align:middle; font-size:0.85rem; border-color:#f0eaff; }
    .table tbody tr:hover { background:#faf5ff; }
    .badge-admin  { background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; display:inline-block; }
    .badge-staff  { background:#dbeafe; color:#1d4ed8; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; display:inline-block; }
    .btn-edit   { background:#ede7f6; color:#4a0080; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; text-decoration:none; display:inline-block; }
    .btn-edit:hover { background:#d8b4fe; color:#3b0764; }
    .btn-del    { background:#fee2e2; color:#dc2626; border:none; border-radius:7px; padding:6px 12px; font-size:0.78rem; font-weight:600; cursor:pointer; }
    .btn-del:hover { background:#fecaca; }
    .avatar-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; color:white; flex-shrink:0; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <i class="fas fa-users me-2"></i> User Accounts
    </h2>
    <a href="{{ route('admin.users.create') }}" class="btn text-white"
       style="background:linear-gradient(135deg,#4a0080,#7b2ff7);border-radius:8px;font-weight:600;">
        <i class="fas fa-plus me-1"></i> Add Account
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="color:#9b59b6;font-weight:600;">{{ $loop->iteration }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle"
                             style="background:{{ $user->role === 'admin' ? 'linear-gradient(135deg,#991b1b,#ef4444)' : 'linear-gradient(135deg,#1d4ed8,#60a5fa)' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-weight:700;color:#2d0057;">{{ $user->name }}</span>
                        @if($user->id === Auth::id())
                            <span style="font-size:0.7rem;background:#f5f0ff;color:#7c3aed;padding:2px 8px;border-radius:20px;font-weight:600;">You</span>
                        @endif
                    </div>
                </td>
                <td style="color:#6b7280;">{{ $user->email }}</td>
                <td>
                    @if($user->role === 'admin')
                        <span class="badge-admin"><i class="fas fa-shield-halved me-1"></i>Admin</span>
                    @else
                        <span class="badge-staff"><i class="fas fa-user-tie me-1"></i>Staff</span>
                    @endif
                </td>
                <td style="color:#9ca3af;font-size:0.8rem;">
                    {{ $user->created_at->format('M d, Y') }}
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit">
                            <i class="fas fa-pen me-1"></i> Edit
                        </a>
                        @if($user->id !== Auth::id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Delete account of {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-users fa-2x mb-2 d-block" style="color:#ce93d8;"></i>
                    No accounts found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $users->withQueryString()->links() }}
</div>
@endif

@endsection
