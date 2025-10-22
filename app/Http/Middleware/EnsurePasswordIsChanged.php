<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /** // <-- MODIFICATION: Added missing /** to start the docblock
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se comprueba si el usuario está autenticado, tiene el rol 'Socio'
        // y nunca ha cambiado su contraseña (el campo está a null).
        if ($request->user() &&
            $request->user()->hasRole('Socio') &&
            is_null($request->user()->password_changed_at)) {

            // Si además está intentando acceder a cualquier ruta que no sea
            // la de editar su perfil o la de cerrar sesión, lo redirigimos.
            // Esto evita un bucle de redirección infinito.
            // We also need to allow the logout route
            if (! $request->routeIs('profile.edit') && ! $request->routeIs('profile.update') && ! $request->routeIs('logout')) {
                return redirect()->route('profile.edit')
                                 ->with('info', 'Por favor, actualiza tu contraseña para continuar.');
            }
        }

        return $next($request);
    }
}