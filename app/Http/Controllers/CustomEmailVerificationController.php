<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivationToken;
use Illuminate\Support\Facades\Auth;

class CustomEmailVerificationController extends Controller
{
    public function verify($token)
    {
        $activationToken = ActivationToken::where('token', $token)->where('used', false)->first();

        if (!$activationToken) {
            return redirect('/?activated=false')->with('error', 'Neplatný aktivační odkaz nebo již byl použit.');
        }

        $user = $activationToken->user;
        if ($user && $user->status !== 'activated') {
            $user->status = 'activated';
            $user->save();
            $activationToken->used = true;
            $activationToken->save();

            Auth::login($user);

            return redirect('/?activated=true')->with('success', 'Váš účet byl úspěšně aktivován.');
        }

        return redirect('/?activated=true')->with('info', 'Účet byl již aktivován.');
    }
}
