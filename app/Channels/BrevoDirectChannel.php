<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BrevoDirectChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // 1. Obtenemos el mensaje de correo construido en la notificación
        $message = $notification->toMail($notifiable);
        
        // 2. Extraemos el email del destinatario
        // Laravel puede tener múltiples 'to', pero tomamos el primero.
        $recipientEmail = $notifiable->routeNotificationFor('mail', $notification);
        $recipientName = $notifiable->nombre ?? '';

        // 3. Obtenemos la API Key desde el archivo de configuración, como debe ser.
        $apiKey = config('mail.mailers.brevo.key');
        if (!$apiKey) {
            Log::error('[BrevoDirectChannel] No se encontró la BREVO_KEY en la configuración.');
            return;
        }

        // 4. Preparamos los datos para la API v3 de Brevo
        $postData = [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $recipientEmail, 'name' => $recipientName],
            ],
            'subject' => $message->subject,
            'htmlContent' => implode("\n", $message->introLines) . "\n" . implode("\n", $message->outroLines),
        ];

        // 5. Ejecutamos la llamada cURL que sabemos que funciona
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // 6. Registramos el resultado en el log para saber qué pasó
        if ($httpCode >= 200 && $httpCode < 300) {
            Log::info('[BrevoDirectChannel] Email enviado exitosamente a ' . $recipientEmail . '. Respuesta: ' . $response);
        } else {
            Log::error('[BrevoDirectChannel] Fallo al enviar email a ' . $recipientEmail . '. Código: ' . $httpCode . '. Error cURL: ' . $curlError . '. Respuesta: ' . $response);
        }
    }
}