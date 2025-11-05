<?php

namespace App\Services;

use App\Models\Comunicado;
use App\Models\Socio;
use App\Notifications\NuevoComunicadoNotification;
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
            // FcmChannel es inteligente: busca la relación user() en el Socio
            // y envía el push al fcm_token de ese User.
            Notification::send($sociosParaEmail, new NuevoComunicadoNotification($comunicado));
            Log::info('[ComunicadoService] Intentando enviar Email+Push (vía Socio) a ' . $sociosParaEmail->count() . ' socios.');
        } else {
            Log::info('[ComunicadoService] No se encontraron socios activos con email para notificar.');
        }

    }
}