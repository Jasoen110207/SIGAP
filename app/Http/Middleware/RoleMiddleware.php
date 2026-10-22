<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Penggunaan di route: RoleMiddleware::class . ':admin' atau ':admin,petugas'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        $userRole = strtolower((string) $user->role);
        $allowedRoles = array_map(static fn (string $role) => strtolower($role), $roles);

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
