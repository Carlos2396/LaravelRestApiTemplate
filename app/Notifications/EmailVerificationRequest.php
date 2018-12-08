<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationRequest extends Notification
{
    use Queueable;

    protected $confirmation_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($confirmation_code)
    {
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = env('FRONTEND_ENDPOINT', '127.0.0.1').'/confirmar/'.$this->confirmation_code;

        return (new MailMessage)
                    ->subject('Confirma tu cuenta')
                    ->greeting('¡Hola!')
                    ->line('Para confirmar tu cuenta de correo electrónico, por da click en la siguiente liga.')
                    ->action('Confirmar cuenta', url($url))
                    ->line('¡Gracias por usar nuestra aplicación!')
                    ->salutation('Saludos.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
