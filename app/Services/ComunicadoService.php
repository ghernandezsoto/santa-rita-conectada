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
     * Esta lógica se toma de la implementación de la API (routes/api.php)
     * que es la más completa y correcta.
     *
     * @param Comunicado $comunicado
     * @return void
     */
    public function enviar(Comunicado $comunicado): void
    {
        // Marcar el comunicado como enviado (si no lo está ya)
        // Esto es idempotente; si ya tiene fecha, no la sobrescribe.
        if (is_null($comunicado->fecha_envio)) {
            $comunicado->update(['fecha_envio' => now()]);
        }

        // Enviar por Email (y FcmChannel) a los Socios Activos con Email
        // (Usando el modelo Socio y la notificación dual)
        $sociosParaEmail = Socio::whereRaw("LOWER(estado) = 'activo'")
                                ->whereNotNull('email')
                                ->get();

        if ($sociosParaEmail->isNotEmpty()) {
            // NuevoComunicadoNotification envía a BrevoDirectChannel y FcmChannel
            Notification::send($sociosParaEmail, new NuevoComunicadoNotification($comunicado));
            Log::info('[ComunicadoService] Intentando enviar Email+Push (vía Socio) a ' . $sociosParaEmail->count() . ' socios.');
        } else {
            Log::info('[ComunicadoService] No se encontraron socios activos con email para notificar.');
        }

        // Enviar Notificación Push (directa) a Usuarios (rol Socio) con Token FCM
        // (Usando el modelo User y la notificación Push dedicada)
        $usuariosParaPush = User::role('Socio')
                                ->whereNotNull('fcm_token')
                                ->get();

        if ($usuariosParaPush->isNotEmpty()) {
            // PushComunicadoNotification envía solo a FcmDirectChannel
            Notification::send($usuariosParaPush, new PushComunicadoNotification($comunicado));
            Log::info('[ComunicadoService] Intentando enviar Push (vía User) a ' . $usuariosParaPush->count() . ' usuarios.');
        } else {
            Log::info('[ComunicadoService] No se encontraron usuarios (rol Socio) con fcm_token para notificar.');
        }
    }
}