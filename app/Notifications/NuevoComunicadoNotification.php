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
        return [BrevoDirectChannel::class]; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        // DEBUG: log para verificar el ID del socio al que se enviará el correo
        Log::info('[DEBUG] Preparando email para el Socio ID: ' . $notifiable->id);

        try {
            $mailMessage = (new MailMessage)
                ->subject('Nuevo Comunicado de la Junta de Vecinos')
                ->greeting('¡Hola ' . $notifiable->nombre . '!')
                ->line('La directiva ha publicado un nuevo comunicado:')
                ->line('**' . $this->comunicado->titulo . '**')
                ->line(substr($this->comunicado->contenido, 0, 200) . '...')
                ->action('Leer Comunicado Completo', url('/')) // Usamos url('/') para evitar errores de ruta en CLI
                ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');

            return $mailMessage;

        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        // Logueamos el error exacto para saber por qué falló.
        Log::error(
            '[FALLO DE ENVÍO] La notificación por correo falló. Causa: ' . $exception->getMessage()
        );
    }
}