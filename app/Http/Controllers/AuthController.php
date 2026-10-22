<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $emailAttempt = mb_substr((string) $credentials['email'], 0, 150);

        if (! Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            app(AuditLogger::class)->log(
                'auth.login_failed',
                'user',
                null,
                'Login gagal untuk email yang dicoba.',
                null,
                ['email' => $emailAttempt, 'ip_address' => $request->ip()]
            );

            return back()
                ->withErrors(['email' => 'Email atau password salah, atau akun dinonaktifkan.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        app(AuditLogger::class)->log(
            'auth.login',
            'user',
            $user?->id,
            'Login berhasil.',
            null,
            ['email' => $user?->email]
        );

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        app(AuditLogger::class)->log(
            'auth.register',
            'user',
            $user->id,
            'Registrasi warga baru berhasil.',
            null,
            ['name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Silakan buat laporan pertama Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app(AuditLogger::class)->log(
            'auth.logout',
            'user',
            $user?->id,
            'Logout berhasil.',
            ['status' => 'logged_in'],
            ['status' => 'logged_out']
        );

        return redirect()->route('landing')->with('success', 'Anda telah keluar.');
    }
}
