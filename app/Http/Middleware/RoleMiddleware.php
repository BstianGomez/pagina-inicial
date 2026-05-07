<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check both 'role' and 'rol' columns for any of the allowed roles
        foreach ($roles as $role) {
            $check = strtolower($role);
            
            // Si estamos verificando Superadmin, usar el helper del modelo que está blindado
            if (($check === 'superadmin' || $check === 'super_admin') && $user->isSuperAdmin()) {
                return $next($request);
            }

            // Fallback para otros roles
            $stored1 = strtolower($user->role ?? '');
            $stored2 = strtolower($user->rol ?? '');

            if ($stored1 === $check || $stored2 === $check) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
}
