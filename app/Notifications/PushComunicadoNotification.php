<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use App\Models\Comunicado;

// class PushComunicadoNotification extends Notification implements ShouldQueue
class PushComunicadoNotification extends Notification
{
    // use Queueable;

    protected $comunicado;

    /**
     * Create a new notification instance.
     */
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
        return [FcmChannel::class];
    }

    /**
     * Get the Fcm representation of the notification.
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->comunicado->titulo)
                ->setBody(substr($this->comunicado->contenido, 0, 100) . '...'))
            ->setData([
                'comunicado_id' => (string) $this->comunicado->id,
                'type' => 'comunicado',
            ]);
    }
}