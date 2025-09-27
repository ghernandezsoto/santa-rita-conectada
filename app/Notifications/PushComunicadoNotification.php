<?php

namespace App\Notifications;

use App\Models\Comunicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\FcmDirectChannel; // Mantenemos tu canal personalizado

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
        // NOTA: Inicio de la lógica de limpieza profesional
        
        // 1. Aseguramos que el contenido sea una cadena de texto, aunque venga nulo.
        $rawContent = $this->comunicado->contenido ?? '';

        // 2. Quitamos etiquetas HTML y decodificamos entidades como &aacute; -> á
        $cleanBody = strip_tags($rawContent);
        $cleanBody = html_entity_decode($cleanBody, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleanBody = trim($cleanBody);

        // 3. Cortamos el texto de forma segura para caracteres en español (multibyte)
        $maxLength = 150;
        if (mb_strlen($cleanBody, 'UTF-8') > $maxLength) {
            $excerpt = mb_substr($cleanBody, 0, $maxLength, 'UTF-8') . '...';
        } else {
            $excerpt = $cleanBody;
        }

        // 4. Limpiamos también el título por seguridad.
        $title = strip_tags((string) ($this->comunicado->titulo ?? ''));
        
        // --- Fin de la lógica de limpieza ---

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