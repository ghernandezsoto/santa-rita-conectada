<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log; // <-- AÑADIR
use Throwable; // <-- AÑADIR

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
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        Log::info('------------------');
        Log::info('[EMAIL] Intentando generar correo para: ' . $notifiable->email);
        Log::info('[EMAIL] Para comunicado ID: ' . $this->comunicado->id);

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

            Log::info('[EMAIL] Correo generado exitosamente para: ' . $notifiable->email);

            return $mailMessage;

        } catch (Throwable $e) {
            Log::error('[EMAIL ERROR] Falló al generar el correo.');
            Log::error('[EMAIL ERROR] Mensaje: ' . $e->getMessage());
            Log::error('[EMAIL ERROR] Archivo: ' . $e->getFile() . ' en línea ' . $e->getLine());
            throw $e;
        }
    }
}