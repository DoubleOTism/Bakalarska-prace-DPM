<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class userActivateNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $activationToken;

    public function __construct($activationToken)
    {
        $this->activationToken = $activationToken;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/verify-email/' . $this->activationToken);

        // Log that the email is being sent
        Log::error('Sending activation email to ' . $notifiable->email);

        return (new MailMessage)
            ->greeting('Dobrý den!')
            ->subject('Aktivace účtu')
            ->line('Děkujeme za registraci na našem webu. Pro dokončení registrace a aktivaci vašeho účtu, prosím, klikněte na níže uvedené tlačítko.')
            ->action('Aktivovat účet', $url)
            ->line('Pokud jste nepožádali o vytvoření účtu, žádné další akce není vyžadováno.')
            ->salutation('S pozdravem, Váš tým dopsimisky');
    }
}


