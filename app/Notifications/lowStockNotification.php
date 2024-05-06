<?php
// app/Notifications/LowStockNotification.php

// app/Notifications/LowStockNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        \Log::info('Odesílání e-mailu na ' . $notifiable->email);
    
        return (new MailMessage)
            ->subject('Upozornění na nízké zásoby')
            ->line('Následující produkty mají nízké zásoby:')
            ->view('emails.lowStockAlert', ['products' => $this->products]);
    }
}


