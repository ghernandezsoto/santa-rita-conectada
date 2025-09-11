<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    /**
     * Registra o actualiza el token de FCM para el usuario autenticado.
     */
    public function register(Request $request)
    {
        // Validamos que la petición contenga un 'token'
        $request->validate([
            'token' => 'required|string',
        ]);

        // Obtenemos al usuario que está haciendo la petición
        $user = $request->user();

        // Actualizamos su campo 'fcm_token' en la base de datos
        $user->update([
            'fcm_token' => $request->token,
        ]);

        // Devolvemos una respuesta de éxito
        return response()->json(['message' => 'FCM token registered successfully.']);
    }
}