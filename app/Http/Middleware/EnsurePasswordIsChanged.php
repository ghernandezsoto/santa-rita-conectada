<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // --- INICIO DE LA MODIFICACIÓN ---
        // Se comprueba si el usuario está autenticado, tiene el rol 'Socio'
        // y nunca ha cambiado su contraseña (el campo está a null).
        if ($request->user() &&
            $request->user()->hasRole('Socio') &&
            is_null($request->user()->password_changed_at)) {

            // Si además está intentando acceder a cualquier ruta que no sea
            // la de editar su perfil o la de cerrar sesión, lo redirigimos.
            // Esto evita un bucle de redirección infinito.
            if (! $request->routeIs('profile.edit') && ! $request->routeIs('profile.update')) {
                return redirect()->route('profile.edit')
                                 ->with('info', 'Por favor, actualiza tu contraseña para continuar.');
            }
        }
        // --- FIN DE LA MODIFICACIÓN ---

        return $next($request);
    }
}