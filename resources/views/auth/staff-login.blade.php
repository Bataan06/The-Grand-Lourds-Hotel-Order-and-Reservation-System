<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login — The Grand Lourds Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; position: relative; overflow: hidden; }
        .bg-image { position: fixed; inset: 0; background: url('{{ asset("images/hotel.jpg") }}') center center / cover no-repeat; z-index: 0; }
        .bg-overlay { position: fixed; inset: 0; background: linear-gradient(135deg, rgba(13,0,24,0.85), rgba(26,0,53,0.82), rgba(45,0,87,0.80)); z-index: 1; }
        .login-wrap { width: 100%; max-width: 420px; padding: 20px; position: relative; z-index: 2; }
        .login-card { background: rgba(255,255,255,0.05); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(192,132,252,0.2); border-radius: 20px; padding: 44px 36px; box-shadow: 0 25px 60px rgba(0,0,0,0.6); }
        .logo-area { text-align: center; margin-bottom: 32px; }
        .logo-area img { width: 68px; height: 68px; object-fit: contain; filter: drop-shadow(0 0 16px rgba(192,132,252,0.6)); margin-bottom: 14px; display: block; margin-left: auto; margin-right: auto; }
        .logo-area .hotel-name { font-size: 1.05rem; font-weight: 700; color: #f0e6ff; letter-spacing: 1px; }
        .logo-area .location { font-size: 0.72rem; color: #a78bfa; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .form-label { color: rgba(255,255,255,0.6); font-size: 0.82rem; font-weight: 500; margin-bottom: 6px; }
        .form-control { background: rgba(255,255,255,0.07); border: 1px solid rgba(192,132,252,0.25); border-radius: 8px; color: white; padding: 11px 14px; font-size: 0.88rem; transition: all 0.2s; }
        .form-control:focus { background: rgba(255,255,255,0.10); border-color: #a78bfa; box-shadow: 0 0 0 3px rgba(167,139,250,0.15); color: white; }
        .form-control::placeholder { color: rgba(255,255,255,0.3); }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: rgba(192,132,252,0.5); font-size: 0.85rem; pointer-events: none; }
        .input-icon .form-control { padding-left: 36px; }
        .btn-signin { background: linear-gradient(135deg, #7c3aed, #a21caf); color: white; border: none; border-radius: 8px; padding: 12px; font-weight: 700; font-size: 0.9rem; width: 100%; transition: all 0.2s; margin-top: 8px; letter-spacing: 0.5px; }
        .btn-signin:hover { opacity: 0.9; transform: translateY(-1px); color: white; box-shadow: 0 8px 20px rgba(124,58,237,0.4); }
        .alert-danger { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; border-radius: 8px; font-size: 0.83rem; padding: 10px 14px; }
        .divider { border-top: 1px solid rgba(192,132,252,0.12); margin: 28px 0 16px 0; }
        .footer-text { text-align: center; font-size: 0.70rem; color: rgba(167,139,250,0.35); letter-spacing: 0.8px; }
        .forgot-link { text-align: center; margin-top: 14px; }
        .forgot-link a { color: rgba(167,139,250,0.7); font-size: 0.78rem; text-decoration: none; transition: color 0.2s; }
        .forgot-link a:hover { color: #a78bfa; }

        /* Forgot Password Modal */
        .fp-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 999; display: none; align-items: center; justify-content: center; }
        .fp-modal-backdrop.show { display: flex; }
        .fp-modal { background: #1a0035; border: 1px solid rgba(192,132,252,0.3); border-radius: 16px; padding: 32px; max-width: 380px; width: 90%; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.8); }
        .fp-modal .fp-icon { width: 60px; height: 60px; border-radius: 50%; background: rgba(124,58,237,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .fp-modal .fp-icon i { font-size: 1.5rem; color: #a78bfa; }
        .fp-modal h5 { color: #f0e6ff; font-weight: 700; margin-bottom: 8px; }
        .fp-modal p { color: rgba(255,255,255,0.6); font-size: 0.83rem; line-height: 1.6; margin-bottom: 20px; }
        .fp-modal .contact-box { background: rgba(255,255,255,0.05); border: 1px solid rgba(192,132,252,0.2); border-radius: 10px; padding: 14px; margin-bottom: 20px; }
        .fp-modal .contact-box .c-item { display: flex; align-items: center; gap: 10px; color: #e9d5ff; font-size: 0.83rem; margin-bottom: 8px; }
        .fp-modal .contact-box .c-item:last-child { margin-bottom: 0; }
        .fp-modal .contact-box .c-item i { color: #a78bfa; width: 16px; }
        .btn-fp-close { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; cursor: pointer; width: 100%; }
        .btn-fp-close:hover { opacity: 0.9; }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="login-wrap">
        <div class="login-card">

            <div class="logo-area">
                <img src="{{ asset('images/logo.png') }}" alt="Grand Lourds Hotel Logo">
                <div class="hotel-name">The Grand Lourds Hotel</div>
                <div class="location">Calasiao · Pangasinan</div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>
                Invalid email or password. Please try again.
            </div>
            @endif

            <form action="{{ route('staff.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}"
                               placeholder="your@email.com"
                               required autofocus>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control"
                               placeholder="••••••••" required>
                    </div>
                    <div style="text-align:right;margin-top:6px;">
                        <a href="#" onclick="document.getElementById('fpModal').classList.add('show'); return false;"
                           style="font-size:0.72rem;color:rgba(167,139,250,0.7);text-decoration:none;">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="btn-signin mt-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <div class="divider"></div>
            <div class="footer-text">
                © {{ date('Y') }} Grand Lourds Hotel. All rights reserved.
            </div>

        </div>
    </div>

    {{-- Forgot Password Modal --}}
    <div class="fp-modal-backdrop" id="fpModal" onclick="if(event.target===this)this.classList.remove('show')">
        <div class="fp-modal">
            <div class="fp-icon"><i class="fas fa-key"></i></div>
            <h5>Forgot Your Password?</h5>
            <p>Password reset is managed by the system administrator. Please contact the admin to have your password reset.</p>
            <div class="contact-box">
                <div class="c-item">
                    <i class="fas fa-phone"></i>
                    <span>0942-483-4680</span>
                </div>
                <div class="c-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>1 De Venecia Avenue, Nalsian, Calasiao</span>
                </div>
            </div>
            <button class="btn-fp-close" onclick="document.getElementById('fpModal').classList.remove('show')">
                <i class="fas fa-times me-1"></i> Close
            </button>
        </div>
    </div>

</body>
</html>