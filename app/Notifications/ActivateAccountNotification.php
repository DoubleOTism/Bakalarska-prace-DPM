<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;





class ActivateAccountNotification extends Notification
{
    use Queueable;

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', 
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
        ->greeting('Dobrý den!')
        ->subject('Aktivace účtu')
        ->line('Děkujeme za registraci na našem webu. Pro dokončení registrace a aktivaci vašeho účtu, prosím, klikněte na níže uvedené tlačítko.')
        ->action('Aktivovat účet', $verificationUrl)
        ->line('Pokud jste nepožádali o vytvoření účtu, žádné další akce není vyžadováno.')
        ->salutation('S pozdravem, Váš tým dopsimisky');

    }
}
