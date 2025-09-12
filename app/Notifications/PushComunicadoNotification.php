<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use App\Models\Comunicado;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;


// class PushComunicadoNotification extends Notification implements ShouldQueue
class PushComunicadoNotification extends Notification

{
    // use Queueable;

    protected $comunicado;

    public function __construct(Comunicado $comunicado)
    {
        $this->comunicado = $comunicado;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification(FcmNotification::create()
                ->setTitle($this->comunicado->titulo)
                ->setBody(substr($this->comunicado->contenido, 0, 150) . '...'))
            ->setData([
                'comunicado_id' => (string) $this->comunicado->id,
                'type' => 'comunicado',
            ]);
    }
}