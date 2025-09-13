<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
// ¡IMPORTANTE! Importamos nuestro nuevo canal personalizado que creamos antes.
use App\Channels\FcmDirectChannel;

class PushComunicadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comunicado;

    public function __construct(Comunicado $comunicado)
    {
        $this->comunicado = $comunicado;
    }

    /**
     * Obtiene los canales de notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Le decimos a Laravel que use nuestro nuevo canal en lugar del paquete externo.
        return [FcmDirectChannel::class];
    }

    /**
     * Obtiene la representación del mensaje para nuestro FcmDirectChannel.
     * ¡IMPORTANTE! El método ahora se llama 'toFcmDirect' y devuelve un array.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toFcmDirect($notifiable)
    {

        // Ya no construimos un objeto FcmMessage.
        // Simplemente devolvemos un array con la estructura que nuestro canal espera.
        return [
            'notification' => [
                'title' => $this->comunicado->titulo,
                'body' => substr($this->comunicado->contenido, 0, 150) . '...',
            ],
            'data' => [
                'comunicado_id' => (string) $this->comunicado->id,
                'type' => 'comunicado',
            ]
        ];
    }
}