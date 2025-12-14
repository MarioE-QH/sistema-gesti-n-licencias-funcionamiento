<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/')->withErrors(['error' => 'Debe iniciar sesión']);
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Si no tiene permiso, redirige al inicio
            return redirect('/main')->withErrors(['error' => 'No tiene permisos para acceder a esta página']);
        }

        return $next($request);
    }
}
