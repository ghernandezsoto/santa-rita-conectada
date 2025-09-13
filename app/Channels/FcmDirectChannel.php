<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
use Throwable;

class FcmDirectChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Primero, obtenemos el token del usuario
        $token = $notifiable->routeNotificationFor('fcm', $notification);

        if (!$token) {
            Log::warning('[FCM DIRECTO] El usuario ID ' . $notifiable->id . ' no tiene token. Saltando.');
            return;
        }

        // Luego, obtenemos el mensaje formateado desde nuestra clase de notificación
        // El método debe llamarse 'toFcmDirect' ahora
        $messagePayload = $notification->toFcmDirect($notifiable);

        try {
            Log::info('[FCM DIRECTO] Intentando enviar notificación a token: ' . substr($token, 0, 15) . '...');
            
            // Usamos el Facade de Firebase para enviar el mensaje directamente
            Firebase::messaging()->send(CloudMessage::withTarget('token', $token)
                ->withNotification($messagePayload['notification'])
                ->withData($messagePayload['data'])
            );

            Log::info('[FCM DIRECTO] ¡NOTIFICACIÓN PUSH ENVIADA DIRECTAMENTE! Éxito para usuario ID: ' . $notifiable->id);

        } catch (Throwable $e) {
            Log::critical('!!!!!!!!!! [FCM DIRECTO ERROR] !!!!!!!!!!!');
            Log::critical('Error al enviar notificación push directa: ' . $e->getMessage());
            Log::critical('Archivo: ' . $e->getFile() . ' Línea: ' . $e->getLine());
        }
    }
}