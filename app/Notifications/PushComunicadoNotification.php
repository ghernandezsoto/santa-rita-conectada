<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use App\Models\Comunicado;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Illuminate\Support\Facades\Log; // <-- AÑADIR
use Throwable; // <-- AÑADIR

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
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        Log::info('------------------');
        Log::info('[PUSH] Intentando generar notificación push para User ID: ' . $notifiable->id);
        Log::info('[PUSH] Para comunicado ID: ' . $this->comunicado->id);

        try {
            $fcmMessage = FcmMessage::create()
                ->setNotification(FcmNotification::create()
                    ->setTitle($this->comunicado->titulo)
                    ->setBody(substr($this->comunicado->contenido, 0, 150) . '...'))
                ->setData([
                    'comunicado_id' => (string) $this->comunicado->id,
                    'type' => 'comunicado',
                ]);

            Log::info('[PUSH] Notificación push generada exitosamente para User ID: ' . $notifiable->id);

            return $fcmMessage;

        } catch (Throwable $e) {
            Log::error('[PUSH ERROR] Falló al generar la notificación push.');
            Log::error('[PUSH ERROR] Mensaje: ' . $e->getMessage());
            Log::error('[PUSH ERROR] Archivo: ' . $e->getFile() . ' en línea ' . $e->getLine());
            throw $e;
        }
    }
}