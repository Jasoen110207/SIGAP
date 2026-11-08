<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RoleLoginController extends Controller
{
    /**
     * Menampilkan halaman login sesuai role.
     */
    public function create(Request $request, string $role = 'user'): View|RedirectResponse
    {
        $role = $this->normalizeRole($role, $request);

        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof User && $user->role === $role) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('dashboard');
        }

        $routeName = match ($role) {
            User::ROLE_ADMIN => 'admin.login',
            User::ROLE_PETUGAS => 'petugas.login',
            default => 'login',
        };

        $currentRoute = $request->route()?->getName();
        if ($currentRoute && $routeName !== $currentRoute) {
            return redirect()->route($routeName);
        }

        $view = match ($role) {
            User::ROLE_ADMIN => 'auth.login-admin',
            User::ROLE_PETUGAS => 'auth.login-petugas',
            default => 'auth.login-warga',
        };

        return view($view, ['role' => $role]);
    }

    /**
     * Memproses login sesuai role.
     */
    public function store(Request $request, string $role = 'user'): RedirectResponse
    {
        $role = $this->normalizeRole($role, $request);

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'login_token' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('email', $validated['email'])
            ->where('role', $role)
            ->first();

        if ($role === User::ROLE_PETUGAS) {
            $tokenValid = false;

            if ($user instanceof User && filled($user->login_token) && ! empty($validated['login_token'])) {
                $tokenValid = $user->login_token === $validated['login_token']
                    || Hash::check((string) $validated['login_token'], (string) $user->login_token);
            }

            if (! $tokenValid) {
                return back()
                    ->withErrors(['email' => 'Email, password, atau akses tidak sesuai untuk halaman ini.'])
                    ->onlyInput('email');
            }
        }

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'is_active' => true,
        ];

        if ($role === User::ROLE_PETUGAS) {
            $credentials['login_token'] = $validated['login_token'] ?? '';
        }

        $authenticated = Auth::attempt($credentials, $request->boolean('remember'));

        if (! $authenticated && $user instanceof User) {
            $passwordMatches = Hash::check((string) $validated['password'], (string) $user->password);

            if ($passwordMatches) {
                Auth::login($user, $request->boolean('remember'));
                $authenticated = true;
            }
        }

        if (! $authenticated) {
            return back()
                ->withErrors(['email' => 'Email, password, atau akses tidak sesuai untuk halaman ini.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $loggedInUser = Auth::user();

        if ($loggedInUser instanceof User && $loggedInUser->passwordExpiredForDemo()) {
            return redirect()->route('password.change')
                ->with('error', 'Password Anda sudah melewati masa berlaku demo 5 detik. Silakan ganti password terlebih dahulu.');
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $loggedInUser->name . '!');
    }

    protected function normalizeRole(string $role, Request $request): string
    {
        $routeRole = $request->route('role');

        if (is_string($routeRole) && in_array($routeRole, [User::ROLE_USER, User::ROLE_PETUGAS, User::ROLE_ADMIN], true)) {
            return $routeRole;
        }

        return in_array($role, [User::ROLE_USER, User::ROLE_PETUGAS, User::ROLE_ADMIN], true)
            ? $role
            : User::ROLE_USER;
    }
}
