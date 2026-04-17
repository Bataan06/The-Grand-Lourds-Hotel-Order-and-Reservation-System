<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: #f5f0ff; font-family: 'Segoe UI', sans-serif; display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 240px; min-height: 100vh; flex-shrink: 0;
            background: #3b0764; display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200; transition: transform 0.3s ease;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
        }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 199; }
        .sidebar-overlay.show { display: block; }
        .sidebar-brand { padding: 20px 18px 16px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
        .sidebar-brand img { width: 38px; height: 38px; object-fit: contain; filter: brightness(1.2); }
        .sidebar-brand .brand-text .name { font-size: 0.88rem; font-weight: 700; color: #f3e8ff; line-height: 1.2; }
        .sidebar-brand .brand-text .sub  { font-size: 0.7rem; color: rgba(255,255,255,0.45); }
        .sidebar-close-btn { display: none; margin-left: auto; background: rgba(255,255,255,0.1); border: none; color: white; border-radius: 8px; width: 30px; height: 30px; font-size: 14px; cursor: pointer; align-items: center; justify-content: center; }
        @media (max-width: 768px) { .sidebar-close-btn { display: flex; } }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: hidden; }
        .nav-section-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.35); padding: 14px 18px 6px; }
        .nav-item-link { display: flex; align-items: center; gap: 12px; padding: 10px 18px; margin: 2px 10px; border-radius: 10px; text-decoration: none; color: rgba(255,255,255,0.6); font-size: 0.88rem; font-weight: 500; transition: all 0.2s; }
        .nav-item-link:hover  { background: rgba(255,255,255,0.1); color: white; }
        .nav-item-link.active { background: linear-gradient(135deg,#7c3aed,#9333ea); color: white; font-weight: 600; }
        .nav-item-link i { width: 18px; text-align: center; font-size: 0.9rem; opacity: 0.8; }
        .nav-item-link.active i { opacity: 1; }

        /* User bottom */
        .sidebar-user { padding: 14px 16px; border-top: 1px solid rgba(255,255,255,0.1); position: relative; }
        .user-trigger { display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px 10px; border-radius: 10px; transition: all 0.2s; }
        .user-trigger:hover { background: rgba(255,255,255,0.08); }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,#7c3aed,#9333ea); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; color: white; flex-shrink: 0; }
        .user-info .user-name { font-size: 0.83rem; font-weight: 700; color: #f3e8ff; line-height: 1.2; }
        .user-info .user-role { font-size: 0.7rem; color: rgba(255,255,255,0.4); }
        .user-chevron { margin-left: auto; color: rgba(255,255,255,0.35); font-size: 0.75rem; transition: transform 0.2s; }
        .user-chevron.open { transform: rotate(180deg); }

        /* Dropdown */
        .user-dropdown { display: none; position: absolute; bottom: 80px; left: 12px; right: 12px; background: #fff; border-radius: 14px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid #e9d5ff; z-index: 300; }
        .user-dropdown.show { display: block; }
        .dropdown-user-header { padding: 14px 16px; background: #faf5ff; border-bottom: 1px solid #f3e8ff; display: flex; align-items: center; gap: 10px; }
        .dropdown-user-header .dh-av { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,#7c3aed,#9333ea); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: white; flex-shrink: 0; }
        .dh-name  { font-size: 0.82rem; font-weight: 700; color: #3b0764; line-height: 1.2; }
        .dh-email { font-size: 0.7rem; color: #9b59b6; }
        .dd-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #4b5563; font-size: 0.85rem; text-decoration: none; cursor: pointer; border: none; background: none; width: 100%; text-align: left; transition: background 0.15s; }
        .dd-item:hover { background: #f5f0ff; color: #7c3aed; }
        .dd-item i { width: 18px; text-align: center; color: #9333ea; font-size: 1rem; }
        .dd-item:hover i { color: #7c3aed; }
        .dd-divider { border: none; border-top: 1px solid #f3e8ff; margin: 4px 0; }
        .dd-item.danger { color: #dc2626; }
        .dd-item.danger i { color: #dc2626; }
        .dd-item.danger:hover { background: #fff1f2; color: #dc2626; }

        /* MAIN */
        .main-wrapper { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        @media (max-width: 768px) { .main-wrapper { margin-left: 0; } }
        .topbar { background: white; padding: 13px 20px; border-bottom: 1px solid #e9d5ff; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; gap: 12px; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title { font-size: 0.95rem; font-weight: 700; color: #3b0764; }
        .topbar-date  { font-size: 0.78rem; color: #9b59b6; white-space: nowrap; }
        .hamburger-btn { display: none; background: #f5f0ff; border: none; color: #7c3aed; border-radius: 8px; width: 36px; height: 36px; font-size: 16px; cursor: pointer; align-items: center; justify-content: center; flex-shrink: 0; }
        @media (max-width: 768px) { .hamburger-btn { display: flex; } .topbar-date { display: none; } }
        .content-area { padding: 24px; flex: 1; overflow-x: hidden; }
        @media (max-width: 576px) { .content-area { padding: 16px; } }
        .alert { border-radius: 12px; border: none; }

        /* MODALS */
        .modal-backdrop-custom { display: none; position: fixed; inset: 0; background: rgba(59,7,100,0.55); z-index: 9999; align-items: center; justify-content: center; padding: 16px; }
        .modal-backdrop-custom.show { display: flex; }
        .modal-box { background: #fff; border-radius: 18px; width: 100%; max-width: 440px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto; }
        .modal-gradient-header { background: linear-gradient(135deg,#7c3aed,#9333ea); padding: 22px 24px 18px; display: flex; align-items: center; gap: 14px; }
        .modal-big-avatar { width: 54px; height: 54px; border-radius: 50%; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem; color: white; border: 2px solid rgba(255,255,255,0.4); flex-shrink: 0; }
        .modal-header-title { font-size: 1rem; font-weight: 700; color: white; }
        .modal-header-sub   { font-size: 0.78rem; color: rgba(255,255,255,0.75); }
        .modal-role-badge { margin-top: 5px; display: inline-block; background: rgba(255,255,255,0.2); color: white; font-size: 0.7rem; font-weight: 600; padding: 2px 10px; border-radius: 20px; }
        .modal-body-pad { padding: 20px 24px; }
        .modal-section-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9333ea; margin-bottom: 10px; }
        .info-card { background: #faf5ff; border-radius: 12px; padding: 14px 16px; margin-bottom: 16px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid #f3e8ff; gap: 8px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-size: 0.82rem; color: #9b59b6; flex-shrink: 0; }
        .info-value { font-size: 0.82rem; font-weight: 600; color: #3b0764; text-align: right; word-break: break-all; }
        .edit-field { width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid #e9d5ff; font-size: 0.85rem; color: #3b0764; background: #fff; outline: none; transition: border 0.2s; }
        .edit-field:focus { border-color: #9333ea; }
        .edit-field:disabled { background: #faf5ff; color: #9b59b6; cursor: not-allowed; }
        .field-group { margin-bottom: 12px; }
        .field-label { font-size: 0.75rem; font-weight: 600; color: #9b59b6; margin-bottom: 4px; display: block; }
        .pw-wrap { position: relative; }
        .pw-wrap .edit-field { padding-right: 40px; }
        .pw-toggle { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9b59b6; cursor: pointer; font-size: 14px; padding: 0; }
        .pw-toggle:hover { color: #7c3aed; }
        .pw-hints { margin-top: 6px; display: flex; flex-direction: column; gap: 3px; }
        .pw-hint { font-size: 0.72rem; display: flex; align-items: center; gap: 6px; color: #9ca3af; }
        .pw-hint.valid { color: #059669; }
        .pw-hint i { font-size: 0.7rem; }
        .modal-action-btn { display: flex; align-items: center; gap: 10px; padding: 11px 14px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none; cursor: pointer; border: none; width: 100%; transition: background 0.15s; margin-bottom: 8px; }
        .modal-action-btn i { width: 16px; text-align: center; }
        .modal-action-btn.purple { background: #f5f0ff; color: #4a0080; }
        .modal-action-btn.purple:hover { background: #ede9fe; }
        .modal-action-btn.save { background: linear-gradient(135deg,#7c3aed,#9333ea); color: white; justify-content: center; }
        .modal-action-btn.save:hover { opacity: 0.9; }
        .modal-divider { border: none; border-top: 1px solid #f3e8ff; margin: 14px 0; }
        .back-btn { display: flex; align-items: center; gap: 8px; background: none; border: none; color: #9b59b6; font-size: 0.82rem; cursor: pointer; padding: 0 0 14px; }
        .back-btn:hover { color: #7c3aed; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

@auth
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="brand-text">
            <div class="name">Grand Lourds Hotel</div>
            <div class="sub">Calasiao, Pangasinan</div>
        </div>
        <button class="sidebar-close-btn" onclick="closeSidebar()"><i class="fas fa-times"></i></button>
    </div>

    <nav class="sidebar-nav">
        @if(Auth::user()->role === 'admin')
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <div class="nav-section-label">Reservations</div>
        <a href="{{ route('admin.event-reservations.index') }}" class="nav-item-link {{ request()->routeIs('admin.event-reservations.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-check"></i> All Reservations
        </a>
        <div class="nav-section-label">Manage</div>
        <a href="{{ route('admin.events.index') }}" class="nav-item-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{ route('admin.offers.index') }}" class="nav-item-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-tags"></i> Special Offers
        </a>
        <div class="nav-section-label">Reports</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-item-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-chart-bar"></i> Reports
        </a>

        @elseif(Auth::user()->role === 'staff')
        <div class="nav-section-label">Main</div>
        <a href="{{ route('staff.dashboard') }}" class="nav-item-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <div class="nav-section-label">Reservations</div>
        <a href="{{ route('staff.guest-bookings.index') }}" class="nav-item-link {{ request()->routeIs('staff.guest-bookings.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-check"></i> Reservations
        </a>
        <div class="nav-section-label">Messages</div>
        <a href="{{ route('staff.guest-messages.index') }}" class="nav-item-link {{ request()->routeIs('staff.guest-messages.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-comments"></i> Guest Messages
        </a>

        @elseif(Auth::user()->role === 'user')
        <div class="nav-section-label">Main</div>
        <a href="{{ route('user.dashboard') }}" class="nav-item-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <div class="nav-section-label">Events</div>
        <a href="{{ route('user.events.index') }}" class="nav-item-link {{ request()->routeIs('user.events.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-alt"></i> Browse Events
        </a>
        <a href="{{ route('user.reservations.index') }}" class="nav-item-link {{ request()->routeIs('user.reservations.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-check"></i> My Reservations
        </a>
        @endif
    </nav>

    <div class="sidebar-user">
        <div class="user-dropdown" id="userDropdown">
            <div class="dropdown-user-header">
                <div class="dh-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div>
                    <div class="dh-name">{{ Auth::user()->name }}</div>
                    <div class="dh-email">{{ Auth::user()->email }}</div>
                </div>
            </div>
            @if(Auth::user()->role === 'admin')
            <button class="dd-item" onclick="openModal('profileModal')">
                <i class="fas fa-user-circle"></i> My Profile
            </button>
            <button class="dd-item" onclick="openModal('settingsModal')">
                <i class="fas fa-cog"></i> Settings
            </button>
            <a href="{{ route('admin.activity-logs.index') }}" class="dd-item" onclick="closeDropdown()">
                <i class="fas fa-clipboard-list"></i> Activity Logs
            </a>
            @endif
            <hr class="dd-divider">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dd-item danger">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>

        <div class="user-trigger" onclick="toggleUserMenu()">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <i class="fas fa-chevron-up user-chevron" id="userChevron"></i>
        </div>
    </div>
</aside>
@endauth

<div class="main-wrapper">
    @auth
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger-btn" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <span class="topbar-title">The Grand Lourds Hotel</span>
        </div>
        <span class="topbar-date"><i class="fas fa-calendar me-1"></i> {{ now()->format('l, F d, Y') }}</span>
    </div>
    @endauth

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

@auth

{{-- MODAL 1: MY PROFILE --}}
<div class="modal-backdrop-custom" id="profileModal" onclick="if(event.target===this)closeModal('profileModal')">
    <div class="modal-box">
        <div class="modal-gradient-header">
            <div class="modal-big-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="modal-header-title">{{ Auth::user()->name }}</div>
                <div class="modal-header-sub">{{ Auth::user()->email }}</div>
                <span class="modal-role-badge">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
        </div>
        <div class="modal-body-pad">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-section-label">Personal Information</div>
                <div class="info-card">
                    <div class="field-group">
                        <label class="field-label">Full Name</label>
                        <input type="text" name="name" class="edit-field" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Email Address</label>
                        <input type="email" name="email" class="edit-field" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="field-group" style="margin-bottom:0;">
                        <label class="field-label">Role</label>
                        <input type="text" class="edit-field" value="{{ ucfirst(Auth::user()->role) }}" disabled>
                    </div>
                </div>
                <button type="submit" class="modal-action-btn save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: SETTINGS --}}
<div class="modal-backdrop-custom" id="settingsModal" onclick="if(event.target===this)closeModal('settingsModal')">
    <div class="modal-box">
        <div class="modal-gradient-header">
            <div class="modal-big-avatar"><i class="fas fa-cog" style="font-size:1.2rem;"></i></div>
            <div>
                <div class="modal-header-title">Settings</div>
                <div class="modal-header-sub">Manage your account preferences</div>
            </div>
        </div>
        <div class="modal-body-pad">
            <div class="modal-section-label">Security</div>
            <div class="info-card" style="padding:8px;">
                <button type="button" class="modal-action-btn purple" style="margin-bottom:0;"
                        onclick="closeModal('settingsModal'); openModal('changePasswordModal');">
                    <i class="fas fa-key"></i>
                    <div style="text-align:left;">
                        <div style="font-weight:700;font-size:0.85rem;">Change Password</div>
                        <div style="font-size:0.72rem;color:#9b59b6;font-weight:400;">Update your account password</div>
                    </div>
                    <i class="fas fa-chevron-right" style="margin-left:auto;font-size:0.75rem;color:#c084fc;"></i>
                </button>
            </div>
            <div class="modal-section-label">About</div>
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">System</span>
                    <span class="info-value">Grand Lourds Hotel</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location</span>
                    <span class="info-value">Calasiao, Pangasinan</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Version</span>
                    <span class="info-value">1.0.0</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 3: CHANGE PASSWORD --}}
<div class="modal-backdrop-custom" id="changePasswordModal" onclick="if(event.target===this)closeModal('changePasswordModal')">
    <div class="modal-box">
        <div class="modal-gradient-header">
            <div class="modal-big-avatar"><i class="fas fa-lock" style="font-size:1.2rem;"></i></div>
            <div>
                <div class="modal-header-title">Change Password</div>
                <div class="modal-header-sub">Update your account password</div>
            </div>
        </div>
        <div class="modal-body-pad">
            <button type="button" class="back-btn"
                    onclick="closeModal('changePasswordModal'); openModal('settingsModal');">
                <i class="fas fa-arrow-left"></i> Back to Settings
            </button>
            @if($errors->has('current_password'))
                <div style="background:#fee2e2;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:0.83rem;margin-bottom:14px;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('current_password') }}
                </div>
            @endif
            @if($errors->has('password'))
                <div style="background:#fee2e2;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:0.83rem;margin-bottom:14px;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('password') }}
                </div>
            @endif
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="field-group">
                    <label class="field-label">Current Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="current_password" id="currentPw"
                               class="edit-field" placeholder="Enter current password" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('currentPw',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">New Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="password" id="newPw"
                               class="edit-field" placeholder="Min. 8 characters" required
                               oninput="checkPwStrength(this.value)">
                        <button type="button" class="pw-toggle" onclick="togglePw('newPw',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="pw-hints">
                        <div class="pw-hint" id="hint-len"><i class="fas fa-circle"></i> At least 8 characters</div>
                        <div class="pw-hint" id="hint-upper"><i class="fas fa-circle"></i> One uppercase letter</div>
                        <div class="pw-hint" id="hint-num"><i class="fas fa-circle"></i> One number</div>
                        <div class="pw-hint" id="hint-special"><i class="fas fa-circle"></i> One special character (!@#$%)</div>
                    </div>
                </div>
                <div class="field-group" style="margin-bottom:20px;">
                    <label class="field-label">Confirm New Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" id="confirmPw"
                               class="edit-field" placeholder="Repeat new password" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('confirmPw',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="modal-action-btn save">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>

@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('mobile-open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar')?.classList.remove('mobile-open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
        closeDropdown();
    }
    function toggleUserMenu() {
        document.getElementById('userDropdown').classList.toggle('show');
        document.getElementById('userChevron').classList.toggle('open');
    }
    function closeDropdown() {
        document.getElementById('userDropdown')?.classList.remove('show');
        document.getElementById('userChevron')?.classList.remove('open');
    }
    document.addEventListener('click', function(e) {
        const su = document.querySelector('.sidebar-user');
        if (su && !su.contains(e.target)) closeDropdown();
    });
    function openModal(id) {
        closeDropdown();
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }
    @if($errors->has('current_password') || $errors->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            openModal('changePasswordModal');
        });
    @endif
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye','fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash','fa-eye');
        }
    }
    function checkPwStrength(val) {
        const set = (id, ok) => document.getElementById(id).classList.toggle('valid', ok);
        set('hint-len',     val.length >= 8);
        set('hint-upper',   /[A-Z]/.test(val));
        set('hint-num',     /[0-9]/.test(val));
        set('hint-special', /[!@#$%^&*]/.test(val));
    }
</script>

@auth
@if(Auth::user()->role === 'user')
<style>
    .chat-float-btn {
        position: fixed; bottom: 28px; right: 28px; z-index: 8888;
        width: 58px; height: 58px; border-radius: 50%;
        background: linear-gradient(135deg,#4a0080,#7b2ff7);
        border: none; color: white; font-size: 1.3rem;
        box-shadow: 0 6px 24px rgba(74,0,128,0.4);
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .chat-float-btn:hover { transform: scale(1.08); box-shadow: 0 8px 30px rgba(74,0,128,0.5); }
    .chat-notif-badge {
        position: absolute; top: -3px; right: -3px;
        background: #ef4444; color: white; font-size: 0.68rem; font-weight: 800;
        width: 20px; height: 20px; border-radius: 50%;
        display: none; align-items: center; justify-content: center;
        border: 2px solid white;
    }
    .chat-notif-badge.show { display: flex; }
    .chat-panel {
        position: fixed; bottom: 96px; right: 28px; z-index: 8887;
        width: 340px; border-radius: 18px; overflow: hidden;
        box-shadow: 0 12px 40px rgba(74,0,128,0.25);
        display: none; flex-direction: column;
        background: white; max-height: 480px;
    }
    .chat-panel.open { display: flex; }
    .chat-panel-header {
        background: linear-gradient(135deg,#4a0080,#7b2ff7);
        padding: 14px 18px; display: flex; align-items: center; gap: 12px; color: white;
    }
    .chat-panel-header .ch-icon { font-size: 1.1rem; }
    .chat-panel-header .ch-title { font-weight: 700; font-size: 0.9rem; flex: 1; }
    .chat-panel-header .ch-sub   { font-size: 0.72rem; opacity: 0.75; }
    .chat-panel-close { background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.85rem; flex-shrink: 0; }
    .chat-panel-close:hover { background: rgba(255,255,255,0.35); }
    .chat-panel-body {
        flex: 1; overflow-y: auto; padding: 14px;
        display: flex; flex-direction: column; gap: 8px;
        background: #faf5ff; min-height: 280px; max-height: 320px;
    }
    .chat-msg-wrap { display: flex; flex-direction: column; }
    .chat-msg-bubble {
        max-width: 82%; padding: 9px 13px; border-radius: 14px;
        font-size: 0.83rem; line-height: 1.5; word-break: break-word;
    }
    .chat-msg-bubble.mine   { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; align-self: flex-end; border-bottom-right-radius: 3px; }
    .chat-msg-bubble.theirs { background: white; color: #1f2937; align-self: flex-start; border-bottom-left-radius: 3px; box-shadow: 0 2px 6px rgba(0,0,0,0.07); }
    .chat-msg-time { font-size: 0.65rem; margin-top: 3px; color: #9ca3af; }
    .chat-msg-time.mine   { text-align: right; }
    .chat-msg-sender { font-size: 0.7rem; font-weight: 700; color: #9b59b6; margin-bottom: 2px; }
    .chat-empty { flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 8px; color: #ce93d8; font-size: 0.82rem; padding: 20px 0; }
    .chat-panel-footer { padding: 12px 14px; border-top: 1px solid #f3e8ff; background: white; }
    .chat-input-row { display: flex; gap: 8px; align-items: flex-end; }
    .chat-textarea {
        flex: 1; border: 1.5px solid #e9d5ff; border-radius: 10px;
        padding: 8px 12px; font-size: 0.83rem; resize: none;
        outline: none; font-family: inherit; max-height: 80px;
    }
    .chat-textarea:focus { border-color: #7b2ff7; }
    .chat-send-btn {
        background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white;
        border: none; border-radius: 10px; width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; flex-shrink: 0; font-size: 0.85rem;
    }
    .chat-send-btn:hover { opacity: 0.9; }
    .chat-toast {
        position: fixed; bottom: 100px; right: 28px; z-index: 8886;
        background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white;
        border-radius: 12px; padding: 12px 16px;
        box-shadow: 0 6px 20px rgba(74,0,128,0.35);
        display: none; align-items: center; gap: 10px;
        font-size: 0.83rem; max-width: 280px; cursor: pointer;
        animation: slideInRight 0.3s ease;
    }
    .chat-toast.show { display: flex; }
    @keyframes slideInRight {
        from { transform: translateX(100px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
    @media (max-width: 576px) {
        .chat-panel { width: calc(100vw - 32px); right: 16px; bottom: 88px; }
        .chat-float-btn { right: 16px; bottom: 16px; }
    }
</style>

<button class="chat-float-btn" id="chatFloatBtn" onclick="toggleChat()" title="Message Staff">
    <i class="fas fa-comment-dots" id="chatBtnIcon"></i>
    <span class="chat-notif-badge" id="chatBadge">0</span>
</button>

<div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
        <div class="ch-icon"><i class="fas fa-headset"></i></div>
        <div class="flex-grow-1">
            <div class="ch-title">Grand Lourds Support</div>
            <div class="ch-sub">Message our staff</div>
        </div>
        <button class="chat-panel-close" onclick="toggleChat()"><i class="fas fa-times"></i></button>
    </div>
    <div class="chat-panel-body" id="chatBody">
        <div class="chat-empty" id="chatEmpty">
            <i class="fas fa-comment-dots fa-2x"></i>
            <span>No messages yet.<br>Say hi to our staff!</span>
        </div>
    </div>
    <div class="chat-panel-footer">
        <div class="chat-input-row">
            <textarea class="chat-textarea" id="chatInput" rows="1"
                      placeholder="Type a message..."
                      onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"></textarea>
            <button class="chat-send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<div class="chat-toast" id="chatToast" onclick="openChat()">
    <i class="fas fa-comment-dots"></i>
    <div id="chatToastMsg">New message from staff!</div>
</div>

<script>
const CHAT_API = { messages:'/user/messages', send:'/user/messages', unread:'/user/messages/unread' };
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
let chatOpen=false, lastMsgId=0, toastTimeout=null;
function toggleChat() {
    chatOpen=!chatOpen;
    document.getElementById('chatPanel').classList.toggle('open',chatOpen);
    document.getElementById('chatBtnIcon').className=chatOpen?'fas fa-times':'fas fa-comment-dots';
    if(chatOpen){clearBadge();loadMessages();}
}
function openChat(){if(!chatOpen)toggleChat();}
function clearBadge(){
    document.getElementById('chatBadge').classList.remove('show');
    document.getElementById('chatBadge').textContent='0';
    document.getElementById('chatToast').classList.remove('show');
}
function renderMessage(msg){
    const wrap=document.createElement('div');
    wrap.className='chat-msg-wrap';
    wrap.innerHTML=`${!msg.is_mine?`<div class="chat-msg-sender">${msg.sender_name}</div>`:''}
        <div class="chat-msg-bubble ${msg.is_mine?'mine':'theirs'}">${msg.body}</div>
        <div class="chat-msg-time ${msg.is_mine?'mine':''}">${msg.time}</div>`;
    return wrap;
}
function loadMessages(){
    fetch(CHAT_API.messages,{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json()).then(msgs=>{
            const body=document.getElementById('chatBody');
            const empty=document.getElementById('chatEmpty');
            if(msgs.length===0){empty.style.display='flex';return;}
            empty.style.display='none';
            body.innerHTML='';
            msgs.forEach(msg=>body.appendChild(renderMessage(msg)));
            if(msgs.length)lastMsgId=msgs[msgs.length-1].id;
            body.scrollTop=body.scrollHeight;
        });
}
function sendMessage(){
    const input=document.getElementById('chatInput');
    const body=input.value.trim();
    if(!body)return;
    input.value='';
    fetch(CHAT_API.send,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({body})})
        .then(r=>r.json()).then(msg=>{
            const chatBody=document.getElementById('chatBody');
            document.getElementById('chatEmpty').style.display='none';
            chatBody.appendChild(renderMessage(msg));
            chatBody.scrollTop=chatBody.scrollHeight;
            lastMsgId=msg.id;
        });
}
function pollMessages(){
    if(chatOpen){loadMessages();return;}
    fetch(CHAT_API.unread,{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json()).then(data=>{
            if(data.count>0){
                const badge=document.getElementById('chatBadge');
                badge.textContent=data.count;
                badge.classList.add('show');
                const toast=document.getElementById('chatToast');
                if(!toast.classList.contains('show')){
                    toast.classList.add('show');
                    clearTimeout(toastTimeout);
                    toastTimeout=setTimeout(()=>toast.classList.remove('show'),5000);
                }
            }
        });
}
document.addEventListener('DOMContentLoaded',function(){pollMessages();setInterval(pollMessages,8000);});
</script>
@endif

@if(Auth::check() && in_array(Auth::user()->role,['staff','admin']))
<script>
function pollStaffMessages(){
    fetch('/staff/messages/unread',{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json()).then(data=>{
            const badge=document.getElementById('staffMsgBadge');
            if(!badge)return;
            if(data.count>0){badge.textContent=data.count;badge.style.display='inline-block';}
            else{badge.style.display='none';}
        }).catch(()=>{});
}
document.addEventListener('DOMContentLoaded',function(){pollStaffMessages();setInterval(pollStaffMessages,8000);});
</script>
@endif
@endauth

</body>
</html>