<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Deja pasar la petición solo si el usuario autenticado tiene uno de
     * los roles indicados. Uso: ->middleware('role:admin') o
     * ->middleware('role:admin,docente') para varios roles.
     *
     * Si la petición espera JSON (como las llamadas fetch del panel),
     * responde 403 en JSON. Si es una vista normal, redirige al panel
     * con un mensaje de error.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = Auth::user();

        if (!$usuario || !in_array($usuario->rol, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'No tienes permiso para realizar esta acción.'
                ], 403);
            }

            return redirect('/panel')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
