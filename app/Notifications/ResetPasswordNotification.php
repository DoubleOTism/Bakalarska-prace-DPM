<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;


class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/password/reset/' . $this->token);

        return(new MailMessage)
            ->greeting('Dobrý den!')
            ->subject(Lang::get('Resetování hesla'))
            ->line(Lang::get('Tento mail byl automaticky vygenerován po podání žádosti o resetování hesla k vašemu účtu.'))
            ->action(Lang::get('Resetovat heslo'), $url)
            ->line(Lang::get('Odkaz pro resetování je platný po dobu :count minut.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('Pokud jste o resetování hesla nepožádali, můžete tento mail ignorovat.'))
            ->salutation('S pozdravem, Váš tým dopsimisky');
    }
}
