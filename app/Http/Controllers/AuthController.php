<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showStaffLogin()
    {
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if (!$user->isAdmin() && !$user->isStaff()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Staff only.']);
            }

            $request->session()->regenerate();

            ActivityLog::log('login', "{$user->name} ({$user->role}) logged in via Staff Portal", 'Auth');

            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'staff' => redirect()->route('staff.dashboard'),
                default => redirect('/')
            };
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            ActivityLog::log(
                'login',
                "{$user->name} ({$user->role}) logged in",
                'Auth'
            );

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'staff' => redirect()->route('staff.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        Auth::login($user);

        ActivityLog::log(
            'register',
            "New user registered: {$user->name} ({$user->email})",
            'Auth'
        );

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::log(
                'logout',
                "{$user->name} ({$user->role}) logged out",
                'Auth'
            );
        }

        $role = $user ? $user->role : null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Admin and staff go back to staff login
        if (in_array($role, ["admin", "staff"])) {
            return redirect()->route("staff.login");
        }

        return redirect("/");
    }
}