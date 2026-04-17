@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(123,47,247,0.1); }
    .card-header-violet { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; font-weight: 600; }
    .form-control:focus { box-shadow: none; border-color: #7b2ff7; }
    .form-label { font-weight: 600; color: #333; }
    .input-group-text { background: #f8f9fa; border-right: none; color: #888; }
    .form-control { border-left: none; }
    .btn-save { background: linear-gradient(135deg, #4a0080, #7b2ff7); color: white; border: none; border-radius: 8px; padding: 12px 30px; font-weight: 700; }
    .btn-save:hover { opacity: 0.9; color: white; }
    .req-list { list-style: none; padding: 0; margin: 6px 0 0; }
    .req-list li { font-size: 0.78rem; color: #aaa; margin-bottom: 2px; transition: color 0.2s; }
    .req-list li.valid { color: #2e7d32; }
    .req-list li i { margin-right: 5px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title"><i class="fas fa-key me-2"></i> Change Password</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-violet">
                <i class="fas fa-lock me-2"></i> Update Your Password
            </div>
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-3">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Enter current password" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="new_password" class="form-control"
                                placeholder="Min. 8 characters" required oninput="checkReqs()">
                        </div>
                        <ul class="req-list">
                            <li id="req-len"><i class="fas fa-circle-xmark"></i> At least 8 characters</li>
                            <li id="req-upper"><i class="fas fa-circle-xmark"></i> One uppercase letter</li>
                            <li id="req-num"><i class="fas fa-circle-xmark"></i> One number</li>
                            <li id="req-special"><i class="fas fa-circle-xmark"></i> One special character (!@#$%)</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="confirm_password"
                                class="form-control" placeholder="Repeat new password"
                                required oninput="checkMatch()">
                        </div>
                        <small id="matchText" class="mt-1 d-block" style="font-size:0.78rem;"></small>
                    </div>

                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function checkReqs() {
    const pwd = document.getElementById('new_password').value;
    const reqs = {
        'req-len':     pwd.length >= 8,
        'req-upper':   /[A-Z]/.test(pwd),
        'req-num':     /[0-9]/.test(pwd),
        'req-special': /[^A-Za-z0-9]/.test(pwd),
    };
    for (const [id, valid] of Object.entries(reqs)) {
        const el = document.getElementById(id);
        const icon = el.querySelector('i');
        if (valid) {
            el.classList.add('valid');
            icon.className = 'fas fa-circle-check';
        } else {
            el.classList.remove('valid');
            icon.className = 'fas fa-circle-xmark';
        }
    }
}

function checkMatch() {
    const pwd = document.getElementById('new_password').value;
    const confirm = document.getElementById('confirm_password').value;
    const text = document.getElementById('matchText');
    if (confirm.length === 0) { text.textContent = ''; return; }
    if (pwd === confirm) {
        text.textContent = '✓ Passwords match';
        text.style.color = '#2e7d32';
    } else {
        text.textContent = '✗ Passwords do not match';
        text.style.color = '#e53935';
    }
}
</script>
@endsection