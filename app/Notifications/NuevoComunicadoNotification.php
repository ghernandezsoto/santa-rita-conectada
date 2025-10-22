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
// Use the standard FcmChannel for consistency
use NotificationChannels\Fcm\FcmChannel;
// We no longer need FcmMessage or FcmNotificationResource if returning an array

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
     * Use both your custom Brevo channel and the standard FCM channel.
     */
    public function via(object $notifiable): array
    {
        return [BrevoDirectChannel::class, FcmChannel::class];
    }

    /**
     * Get the mail representation (Unchanged).
     */
    public function toMail(object $notifiable): MailMessage
    {
        // ... (your existing toMail logic remains unchanged) ...
        Log::info('[DEBUG] Preparando email para el Socio ID: ' . $notifiable->id); // Assuming $notifiable is User linked to Socio

        try {
            // Attempt to get Socio name if $notifiable is User
            $socioName = $notifiable->name; // Default to user name

            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $socioName . '!')
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/')) // Consider a direct link if feasible
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            Log::error('[ERROR toMail] Fallo al construir MailMessage: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Define the content for Firebase Cloud Messaging (FCM).
     *
     * IMPORTANT: This now returns a PHP array, mirroring the structure
     * used in your working PushComunicadoNotification::toFcmDirect method.
     *
     * @param  object  $notifiable
     * @return array The payload structure FCM expects.
     */
    public function toFcm(object $notifiable): array
    {
        // Re-use the same excerpt logic as PushComunicadoNotification
        $cleanBody = trim((string) ($this->comunicado->contenido ?? ''));
        $maxLength = 150; // Or match PushComunicadoNotification's length
        $excerpt = (mb_strlen($cleanBody, 'UTF-8') > $maxLength)
                   ? mb_substr($cleanBody, 0, $maxLength, 'UTF-8') . '...'
                   : $cleanBody;

        $title = (string) ($this->comunicado->titulo ?? '');

        // Return the array payload
        return [
            'notification' => [
                'title' => $title,
                'body'  => $excerpt,
            ],
            'data' => [
                'comunicado_id' => (string) $this->comunicado->id,
                 // You might want 'type' => 'comunicado' here too, like in PushComunicadoNotification
                'type' => 'comunicado',
            ],
        ];
    }

    /**
     * Handle a job failure (Unchanged).
     */
    public function failed(\Throwable $exception): void
    {
        // ... (your existing failed logic remains unchanged) ...
        Log::error(
            '[FALLO DE ENVÍO NOTIFICACIÓN] User ID: ' . ($this->comunicado->user_id ?? 'N/A') .
            ' Comunicado ID: ' . $this->comunicado->id .
            ' Causa: ' . $exception->getMessage() .
            ' Trace: ' . $exception->getTraceAsString()
        );
    }
}