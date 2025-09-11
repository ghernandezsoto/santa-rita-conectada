<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Comunicado;
use App\Notifications\PushComunicadoNotification;
use Illuminate\Support\Facades\Notification;

class TestPushNotification extends Command
{
    protected $signature = 'app:test-push-notification';
    protected $description = 'Envía una notificación push de prueba al primer usuario con token.';

    public function handle()
    {
        $this->info('Iniciando prueba de notificación push...');

        $user = User::whereNotNull('fcm_token')->first();
        $comunicado = Comunicado::first();

        if (!$user) {
            $this->error('ERROR: No se encontró ningún usuario con un token FCM registrado.');
            return 1;
        }

        if (!$comunicado) {
            $this->error('ERROR: No se encontró ningún comunicado para usar en la prueba. Por favor, crea uno primero.');
            return 1;
        }

        $this->info("Enviando notificación del comunicado '{$comunicado->titulo}' al usuario '{$user->name}'...");

        try {
            Notification::sendNow($user, new PushComunicadoNotification($comunicado));
            $this->info('¡ÉXITO! La notificación fue entregada a Firebase sin errores.');
        } catch (\Exception $e) {
            $this->error('¡FALLO! Se produjo un error al intentar enviar la notificación:');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}