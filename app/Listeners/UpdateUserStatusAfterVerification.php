<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class UpdateUserStatusAfterVerification
{
    public function handle(Verified $event)
    {
        // Uživatel, jehož email byl právě ověřen
        $user = $event->user;

        // Aktualizujte stav uživatele na 'activated'
        $user->status = 'activated';
        $user->save();
    }
}
