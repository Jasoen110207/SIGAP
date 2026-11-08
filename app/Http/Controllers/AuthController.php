<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        // Menampilkan form login dan mengarahkan pengguna yang sudah login ke dashboard untuk keamanan akses.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Memvalidasi email, password, dan token petugas untuk proses autentikasi yang aman.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'login_token' => ['nullable', 'string', 'max:255'],
        ]);

        // Mencegah login petugas tanpa token yang valid agar otorisasi lebih aman.
        $emailAttempt = mb_substr((string) $credentials['email'], 0, 150);
        $tokenAttempt = mb_substr((string) ($credentials['login_token'] ?? ''), 0, 255);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->role === User::ROLE_PETUGAS && ! empty($credentials['login_token'])) {
            if ($user->login_token !== $credentials['login_token']) {
                app(AuditLogger::class)->log(
                    'auth.login_failed',
                    'user',
                    $user->id,
                    'Login petugas gagal karena token tidak sesuai.',
                    null,
                    ['email' => $emailAttempt, 'login_token' => $tokenAttempt, 'ip_address' => $request->ip()]
                );

                return back()
                    ->withErrors(['login_token' => 'Token login petugas tidak valid untuk akun ini.'])
                    ->onlyInput('email');
            }
        }

        // Membandingkan password input dengan hash password di database untuk verifikasi login.
        if (! Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            app(AuditLogger::class)->log(
                'auth.login_failed',
                'user',
                $user?->id,
                'Login gagal untuk email yang dicoba.',
                null,
                ['email' => $emailAttempt, 'login_token' => $tokenAttempt, 'ip_address' => $request->ip()]
            );

            return back()
                ->withErrors(['email' => 'Email atau password salah, atau akun dinonaktifkan.'])
                ->onlyInput('email');
        }

        // Menyimpan sesi login pengguna setelah autentikasi berhasil.
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && $user->role === User::ROLE_PETUGAS && empty($user->login_token)) {
            return back()->withErrors(['login_token' => 'Akun petugas belum memiliki token login yang aktif.']);
        }

        if ($user instanceof User && $user->passwordExpiredForDemo()) {
            return redirect()->route('password.change')
                ->with('error', 'Password Anda sudah melewati masa berlaku demo 5 detik. Silakan ganti password terlebih dahulu.');
        }

        app(AuditLogger::class)->log(
            'auth.login',
            'user',
            $user->id,
            'Login berhasil.',
            null,
            ['email' => $user->email]
        );

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function showForgotPassword(): View
    {
        // Menampilkan halaman lupa password agar pengguna dapat memulihkan akun secara aman.
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        // Mengirim link reset password ke email pengguna yang terdaftar untuk proses pemulihan akun.
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token): View
    {
        // Menampilkan form password baru berdasarkan token reset agar pengguna dapat mengubah password yang aman.
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        // Memvalidasi token dan password baru, lalu menyimpan password baru dalam bentuk hash yang aman.
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                if ($user->hasUsedPassword($password)) {
                    throw new \RuntimeException('Password baru tidak boleh sama dengan password lama atau riwayat password sebelumnya.');
                }

                $user->forceFill([
                    'password' => Hash::make($password),
                    'password_changed_at' => now(),
                ])->setRememberToken(Str::random(60));

                $user->save();
                $user->recordPasswordHistory($password);

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showRegister(): View|RedirectResponse
    {
        // Menampilkan halaman pendaftaran dan mencegah pengguna yang sudah login mendaftar ulang.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    // Menyimpan password pengguna dalam bentuk hash dan validasi minimum 8 karakter sesuai kebijakan keamanan.
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'password_changed_at' => now(),
            'role' => User::ROLE_USER,
        ]);

        $user->recordPasswordHistory($data['password']);

        // Membuat sesi login otomatis setelah registrasi untuk memudahkan akses pengguna baru.
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

    public function showChangePassword(): View
    {
        // Menampilkan halaman ubah password bagi pengguna yang sudah login.
        return view('auth.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        // Memvalidasi input dan mengubah password pengguna yang sudah terautentikasi.
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi Anda tidak valid. Silakan login kembali.']);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        if ($user->hasUsedPassword($request->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama atau riwayat password sebelumnya.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        $user->save();
        $user->recordPasswordHistory($request->password);

        app(AuditLogger::class)->log(
            'auth.password_changed',
            'user',
            $user->id,
            'Password diubah oleh pengguna.',
            null,
            ['email' => $user->email]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diubah.');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Mengakhiri sesi pengguna agar akses ke sistem berhenti dengan aman setelah logout.
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

        $loginRoute = match ($user?->role) {
            'petugas' => 'petugas.login',
            'admin' => 'admin.login',
            default => 'login',
        };

        return redirect()->route($loginRoute)->with('success', 'Anda telah keluar.');
    }
}
