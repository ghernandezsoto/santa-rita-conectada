<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
// --- IMPORTANTE: Añadimos la excepción específica de "No Encontrado" ---
use Kreait\Firebase\Exception\Messaging\NotFound;
use Throwable;

class FcmDirectChannel
{
    public function send($notifiable, Notification $notification)
    {
        $token = $notifiable->routeNotificationFor('fcm', $notification);

        if (!$token) {
            Log::warning('[FCM DIRECTO] El usuario ID ' . $notifiable->id . ' no tiene token. Saltando.');
            return;
        }

        $messagePayload = $notification->toFcmDirect($notifiable);
        $cloudMessage = CloudMessage::withTarget('token', $token)
            ->withNotification($messagePayload['notification'])
            ->withData($messagePayload['data']);

        try {
            Log::info('[FCM DIRECTO] Intentando enviar notificación a token: ' . substr($token, 0, 15) . '...');
            Firebase::messaging()->send($cloudMessage);
            Log::info('[FCM DIRECTO] ¡NOTIFICACIÓN PUSH ENVIADA DIRECTAMENTE! Éxito para usuario ID: ' . $notifiable->id);

        } catch (NotFound $e) {
            // --- ESTE ES EL NUEVO BLOQUE INTELIGENTE ---
            // Si Firebase nos dice que el token no existe, lo manejamos aquí.
            Log::warning('[FCM DIRECTO] Token no encontrado o inválido para User ID ' . $notifiable->id . '. Se procederá a eliminarlo.');
            
            // Eliminamos el token inválido de la base de datos para no volver a intentarlo.
            $notifiable->update(['fcm_token' => null]);

        } catch (Throwable $e) {
            // El bloque para otros errores críticos se mantiene.
            Log::critical('!!!!!!!!!! [FCM DIRECTO ERROR] !!!!!!!!!!!');
            Log::critical('Error al enviar notificación push directa: ' . $e->getMessage());
            Log::critical('Archivo: ' . $e->getFile() . ' Línea: ' . $e->getLine());
        }
    }
}