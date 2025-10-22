<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\FcmDirectChannel;

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
        return [FcmDirectChannel::class];
    }

    public function toFcmDirect($notifiable)
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