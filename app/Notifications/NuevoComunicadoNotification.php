<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Channels\BrevoDirectChannel;
use NotificationChannels\Fcm\FcmChannel;

class NuevoComunicadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comunicado;

    public function __construct(Comunicado $comunicado)
    {
        $this->comunicado = $comunicado;
    }

    public function via(object $notifiable): array
    {
        return [BrevoDirectChannel::class, FcmChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // --- ADDED LOGGING ---
        Log::info('[DEBUG toMail] Intentando enviar a User ID: ' . $notifiable->id . ' Email: ' . $notifiable->email . ' Nombre Obtenido: "' . $notifiable->name . '"');
        // --- END ADDED LOGGING ---

        try {
            $userName = $notifiable->name; // Use the name attribute from the User model

            // If name is unexpectedly empty, provide a default to avoid Brevo error
            if (empty($userName)) {
                Log::warning('[WARN toMail] User ID: ' . $notifiable->id . ' tiene nombre vacío. Usando email como nombre para Brevo.');
                $userName = $notifiable->email; // Fallback to email
            }

            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $userName . '!') // Use the potentially corrected $userName
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/'))
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            Log::error('[ERROR toMail] Fallo al construir MailMessage para User ID: ' . $notifiable->id . ' Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toFcm(object $notifiable): array
    {
        $cleanBody = trim((string) ($this->comunicado->contenido ?? ''));
        $maxLength = 150;
        $excerpt = (mb_strlen($cleanBody, 'UTF-8') > $maxLength)
                   ? mb_substr($cleanBody, 0, $maxLength, 'UTF-8') . '...'
                   : $cleanBody;
        $title = (string) ($this->comunicado->titulo ?? '');

        return [
            'notification' => [
                'title' => $title,
                'body'  => $excerpt,
            ],
            'data' => [
                'comunicado_id' => (string) $this->comunicado->id,
                'type' => 'comunicado',
            ],
        ];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error(
            '[FALLO DE ENVÍO NOTIFICACIÓN] User ID: ' . ($this->comunicado->user_id ?? 'N/A') .
            ' Comunicado ID: ' . $this->comunicado->id .
            ' Causa: ' . $exception->getMessage() .
            ' Trace: ' . $exception->getTraceAsString()
        );
    }
}