<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;

class PushComunicadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comunicado;

    public function __construct(Comunicado $comunicado)
    {
        $this->comunicado = $comunicado;
    }

    public function via($notifiable)
    {
        // Se usa el canal nativo de FCM
        return [FcmChannel::class];
    }

    // Renombrado de 'toFcmDirect' a 'toFcm'
    // Esta es la función que FcmChannel busca por defecto.
    public function toFcm(object $notifiable): array
    {
        // Obtenemos el contenido y quitamos espacios en blanco al inicio/final.
        $cleanBody = trim((string) ($this->comunicado->contenido ?? ''));

        // Cortamos el texto de forma segura para crear un extracto para la notificación.
        $maxLength = 150;
        if (mb_strlen($cleanBody, 'UTF-8') > $maxLength) {
            $excerpt = mb_substr($cleanBody, 0, $maxLength, 'UTF-8') . '...';
        } else {
            $excerpt = $cleanBody;
        }

        // Obtenemos el título (ya no necesita limpieza de HTML).
        $title = (string) ($this->comunicado->titulo ?? '');

        // Este es el payload que FcmChannel espera.
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
}