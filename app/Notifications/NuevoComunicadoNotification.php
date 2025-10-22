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
use App\Models\User; // Import User model if needed for type hinting or checks
use App\Models\Socio; // Import Socio model if needed for type hinting or checks

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
        // Esta parte se mantiene igual
        return [BrevoDirectChannel::class, FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // --- INICIO DE LA MODIFICACIÓN: Lógica Mejorada para Obtener Nombre ---

        // Log básico inicial
        $notifiableId = $notifiable->id ?? 'N/A';
        $notifiableEmail = $notifiable->email ?? 'N/A';
        Log::info('[DEBUG toMail] Preparando email para notifiable ID: ' . $notifiableId . ' Email: ' . $notifiableEmail);

        $userName = ''; // Inicializar

        // 1. Intentar obtener nombre desde el atributo 'name' (típico de User)
        if (property_exists($notifiable, 'name') && !empty($notifiable->name)) {
            $userName = $notifiable->name;
            Log::info('[DEBUG toMail] Nombre obtenido desde atributo "name": "' . $userName . '"');
        }
        // 2. Si no se encontró, intentar obtener desde 'nombre' (típico de Socio)
        elseif (property_exists($notifiable, 'nombre') && !empty($notifiable->nombre)) {
            $userName = $notifiable->nombre;
            Log::info('[DEBUG toMail] Nombre obtenido desde atributo "nombre": "' . $userName . '"');
        }

        // 3. Fallback si el nombre sigue vacío
        if (empty($userName)) {
            $fallbackName = $notifiableEmail !== 'N/A' ? $notifiableEmail : 'Estimado/a Socio/a';
            Log::warning('[WARN toMail] Notifiable ID: ' . $notifiableId . ' tiene nombre vacío o no encontrado en atributos esperados. Usando fallback: "' . $fallbackName . '" para Brevo.');
            $userName = $fallbackName;
        }
        // --- FIN DE LA MODIFICACIÓN ---

        try {
            // Construir el mensaje usando el $userName obtenido
            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $userName . '!') // Usar el $userName que ahora siempre tendrá un valor
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/')) // Idealmente, enlace directo si es posible
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            // Loguear el error si la construcción del MailMessage falla
            Log::error('[ERROR toMail] Fallo al construir MailMessage para notifiable ID: ' . $notifiableId . ' Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Define the content for Firebase Cloud Messaging (FCM).
     * Esta parte se mantiene igual, usando el formato de array.
     */
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

    /**
     * Handle a job failure.
     * Esta parte se mantiene igual.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error(
            '[FALLO DE ENVÍO NOTIFICACIÓN] User ID del creador: ' . ($this->comunicado->user_id ?? 'N/A') . // Aclarar que es el user_id del comunicado
            ' Comunicado ID: ' . $this->comunicado->id .
            ' Causa: ' . $exception->getMessage() .
            ' Trace: ' . $exception->getTraceAsString() // Dejar la traza completa es útil para depurar fallos de cola
        );
    }
}