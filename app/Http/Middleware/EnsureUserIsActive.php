<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Mengeluarkan paksa user yang akunnya dinonaktifkan saat sedang login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            app(AuditLogger::class)->log(
                'auth.blocked_inactive',
                'user',
                $user->id,
                'Akun dinonaktifkan dan sesi diakhiri secara otomatis.',
                ['is_active' => true],
                ['is_active' => false]
            );

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi admin kelurahan.']);
        }

        return $next($request);
    }
}
