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
// --- Asegúrate de que estas líneas estén presentes ---
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotificationResource;
// --- Fin de las líneas a asegurar ---

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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // --- Asegúrate de que FcmChannel::class esté aquí ---
        return [BrevoDirectChannel::class, FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Esta lógica se mantiene intacta
        Log::info('[DEBUG] Preparando email para el Socio ID: ' . $notifiable->id);

        try {
            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $notifiable->nombre . '!') // Asumiendo que $notifiable (User) tiene acceso al nombre del Socio
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/')) // Idealmente, un enlace directo al comunicado si es posible
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            // Loguear el error específico puede ser útil aquí también
            Log::error('[ERROR toMail] Fallo al construir MailMessage: ' . $e->getMessage());
            throw $e;
        }
    }

    // --- Asegúrate de que este método completo exista ---
    /**
     * Define el contenido de la notificación para Firebase Cloud Messaging.
     *
     * @param  object  $notifiable
     * @return \NotificationChannels\Fcm\FcmMessage
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(FcmNotificationResource::create()
                ->setTitle('Nuevo Comunicado: ' . $this->comunicado->titulo)
                ->setBody(substr($this->comunicado->contenido, 0, 100) . '...') // Cuerpo corto para push
            )
            ->setData(['comunicado_id' => (string)$this->comunicado->id]); // Dato extra para la app
    }
    // --- Fin del método toFcm ---


    public function failed(\Throwable $exception): void
    {
        // Esta lógica se mantiene intacta
        // Añadimos más contexto al log de error
        Log::error(
            '[FALLO DE ENVÍO NOTIFICACIÓN] User ID: ' . ($this->comunicado->user_id ?? 'N/A') .
            ' Comunicado ID: ' . $this->comunicado->id .
            ' Causa: ' . $exception->getMessage() .
            ' Trace: ' . $exception->getTraceAsString() // Opcional: traza completa si necesitas depurar más
        );
    }
}