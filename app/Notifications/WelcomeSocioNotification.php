<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeSocioNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * La contraseña temporal que se enviará al nuevo usuario.
     *
     * @var string
     */
    private string $password;

    /**
     * Create a new notification instance.
     * Se pasa la contraseña temporal al momento de crear la notificación.
     */
    public function __construct(string $temporaryPassword)
    {
        $this->password = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Se personaliza completamente el mensaje de correo electrónico.
        return (new MailMessage)
                    ->subject('¡Bienvenido/a a Santa Rita Conectada!')
                    ->greeting('¡Hola, ' . $notifiable->name . '!')
                    ->line('Te damos la bienvenida a la plataforma digital de la Junta de Vecinos Santa Rita.')
                    ->line('Se ha creado una cuenta para que puedas acceder a nuestro portal de socios.')
                    ->line('Aquí están tus credenciales de acceso:')
                    ->line('**Email:** ' . $notifiable->email)
                    ->line('**Contraseña Temporal:** ' . $this->password)
                    ->action('Iniciar Sesión', route('login'))
                    ->line('**Importante:** Por tu seguridad, te pedimos que cambies tu contraseña inmediatamente después de iniciar sesión por primera vez.')
                    ->line('¡Gracias por ser parte de nuestra comunidad!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}