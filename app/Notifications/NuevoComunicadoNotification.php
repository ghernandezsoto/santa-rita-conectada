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

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [BrevoDirectChannel::class, FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('[DEBUG] Preparando email para User ID: ' . $notifiable->id . ' Email: ' . $notifiable->email);

        try {
            // --- CORRECTION: Use $notifiable->name as $notifiable is the User model ---
            $userName = $notifiable->name;

            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $userName . '!') // <-- Corrected line
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/')) // Consider a direct link if feasible
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            Log::error('[ERROR toMail] Fallo al construir MailMessage para User ID: ' . $notifiable->id . ' Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Define the content for Firebase Cloud Messaging (FCM).
     */
    public function toFcm(object $notifiable): array
    {
        // Re-use the same excerpt logic as PushComunicadoNotification
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

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error(
            '[FALLO DE ENVÍO NOTIFICACIÓN] User ID: ' . ($this->comunicado->user_id ?? 'N/A') .
            ' Comunicado ID: ' . $this->comunicado->id .
            ' Causa: ' . $exception->getMessage() .
            ' Trace: ' . $exception->getTraceAsString() // More detailed trace if needed
        );
    }
}