<?php

namespace App\Services;

use App\Models\Comunicado;
use App\Models\Socio;
use App\Models\User; 
use App\Notifications\NuevoComunicadoNotification;
use App\Notifications\PushComunicadoNotification; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ComunicadoService
{
    /**
     * Centraliza el envío de notificaciones (Email y Push) para un nuevo comunicado.
     *
     * @param Comunicado $comunicado
     * @return void
     */
    public function enviar(Comunicado $comunicado): void
    {
        // Marcar el comunicado como enviado (si no lo está ya)
        if (is_null($comunicado->fecha_envio)) {
            $comunicado->update(['fecha_envio' => now()]);
        }

        // --- BLOQUE 1: ENVÍO DE EMAILS (vía Socios) ---
        // Se notifica a los modelos Socio, que tienen 'email'.
        // NuevoComunicadoNotification ahora SOLO enviará por MailChannel.
        $sociosParaEmail = Socio::whereRaw("LOWER(estado) = 'activo'")
                                ->whereNotNull('email')
                                ->get();

        if ($sociosParaEmail->isNotEmpty()) {
            Notification::send($sociosParaEmail, new NuevoComunicadoNotification($comunicado));
            Log::info('[ComunicadoService] Encolando Emails (vía Socio) para ' . $sociosParaEmail->count() . ' socios.');
        } else {
            Log::info('[ComunicadoService] No se encontraron socios activos con email para notificar.');
        }

        // --- BLOQUE 2: ENVÍO DE PUSH (vía Users) ---
        // Buscamos usuarios que tengan CUALQUIER rol (Socio, Presidente, etc.)
        // PushComunicadoNotification AHORA enviará por el FcmChannel nativo.
        $usuariosParaPush = User::whereHas('roles')
                                ->whereNotNull('fcm_token')
                                ->get();

        if ($usuariosParaPush->isNotEmpty()) {
            Notification::send($usuariosParaPush, new PushComunicadoNotification($comunicado));
            Log::info('[ComunicadoService] Encolando Notificaciones Push (vía User) para ' . $usuariosParaPush->count() . ' usuarios.');
        } else {
            Log::info('[ComunicadoService] No se encontraron usuarios (rol Socio) con fcm_token para notificar.');
        }
    }
}