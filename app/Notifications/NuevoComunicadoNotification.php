<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return ['mail']; // Por ahora, solo enviaremos por correo (mail).
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nuevo Comunicado de la Junta de Vecinos')
                    ->greeting('¡Hola!')
                    ->line('La directiva ha publicado un nuevo comunicado:')
                    ->line('**' . $this->comunicado->titulo . '**')
                    ->line(substr($this->comunicado->contenido, 0, 200) . '...') // Muestra un extracto
                    ->action('Leer Comunicado Completo', route('comunicados.show', $this->comunicado->id))
                    ->salutation('Saludos cordiales,
Directiva de la Junta de Vecinos N° 4 de Santa Rita');
    }
}